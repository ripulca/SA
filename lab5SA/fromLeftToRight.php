<a href="index.php"> НАЗАД </a><br>
<?php
// error_reporting(0);
$node_amount=$_POST['node_amount']; //кол-во вершин
if($_POST['type']==1){
    $type=false;
}
else{
    $type= true;
}
// $arcs=0;
$q=0;
$qotn=0;

reading();
    function reading (){
        // echo $_POST['type'];
        global $node_amount, $type;
        $G_plus=array();
        for($i=0;$i<$node_amount;$i++){
            preg_match_all("/\d+/", $_POST['G-'.$i], $nodes);
            array_push($G_plus, $nodes[0]);
        }
        $i=0;
        echo "Множество правых инциденций:<br>";
        foreach($G_plus as $val){
            echo "Вершина ".$i.": ";
            foreach($val as $el){
                echo " ".$el." ";
            }
            echo "<br>";
            $i++;
        }
        make_matrix_sm($G_plus);
    }

    function make_matrix_sm($G_plus){
        global $node_amount, $type;
        $ready=array();
        for($i=0; $i< $node_amount; $i++){
            $ready[$i]=array();
            for($j=0; $j<$node_amount; $j++){
                $ready[$i][$j]=INF;
            }
        }
        if($type==1){
            for($i=0; $i<$node_amount; $i++){
                foreach($G_plus[$i] as $val){
                    if($ready[$i][$val]!=0){
                        $ready[$i][$val]=1;
                    }
                    // if($ready[$val][$i]!=0){
                    //     $ready[$val][$i]=-1;
                    // }
                }
            }
        }
        else{
            for($i = 0; $i <$node_amount; $i++){
                foreach($G_plus[$i] as $val){
                    $ready[$i][$val]=1;
                }
            }
        }
        res($ready);
    }

    function res($matrix_first){
        global $node_amount, $type, $q, $qotn;
        
        for($i=0;$i<count($matrix_first);$i++){
            for($j=0;$j<count($matrix_first[$i]);$j++){
                echo $matrix_first[$i][$j]." ";
            }
            echo "<br>";
        }
        echo "<br>";
        $graph=array();
        $k=0;
        for($i=0;$i<$node_amount;$i++){
            // echo "<br>i= ".$i."<br>";
            for($j=0;$j<$node_amount;$j++){
                // echo "<br>j= ".$j."<br>";
                $nodes=array(0=>$i, 1=>$j, 2=>$matrix_first[$i][$j]);
                $graph[$k]=$nodes;
                // print_r($graph[$k]);
                $k++;
            }
        }

        // print_r($graph);

        $ways=array();
        for($i=0;$i<$node_amount;$i++){
            // bellman_ford_algorithm($graph, $node_amount, $arcs, $i);
            array_push($ways, bellman_ford_algorithm($graph, $node_amount, $i));
            // print_r($ways[$i]);
        }
        $d=0;
        for($i=0;$i<count($ways);$i++){
            for($j=0;$j<count($ways[$i]);$j++){
                if($i!=$j){
                    $q+=$ways[$i][$j];
                }
                if($d<$ways[$i][$j] && $ways[$i][$j]!=INF){
                    $d=$ways[$i][$j];
                }
            }
        }
        // foreach($ways as $matrix){
        //     foreach($matrix as $m){
        //         $d+=$m;
        //     }
        // }
        echo "d= ".$d."<br>";
        echo "q= ".$q."<br>";
        $qotn=($q/($node_amount*($node_amount-1)))-1;
        echo "q otn= ".$qotn."<br>";
        
        make_matrix($ways);
    }

    function bellman_ford_algorithm($graph, $V, $src){
        $dis=array();
        for ($i = 0; $i < $V; $i++){
            $dis[$i] = INF;
            // echo $dis[$i]." ";
        }
        $dis[$src] = 0;
        // echo "E= ".$E."<br>";
        // echo count($graph)."<br>";
        for (; ; ) {
            $any=false;
            for ($j = 0; $j < count($graph); ++$j) {
                if($dis[$graph[$j][0]]<INF){
                    // echo $dis[$graph[$j][0]]."(".$graph[$j][0].")"." + ".$graph[$j][2]." < ".$dis[$graph[$j][1]]."(".$graph[$j][1].")"."<br>";
                    if ($dis[$graph[$j][0]] + $graph[$j][2] < $dis[$graph[$j][1]]){
                        $dis[$graph[$j][1]] = $dis[$graph[$j][0]] + $graph[$j][2];
                        $any=true;
                    }
                }
            }
            // echo "<br>";
            // echo "<br>";
            if(!$any){
                break;
            }
        }
        // echo "Vertex Distance from Source ".$src."<br>";
        // for ($i = 0; $i < $V; $i++){
        //     echo $i." ".$dis[$i]."<br>";
        // }
        return $dis;
    }
    
    function make_matrix($matrix){
        echo "Матрица наим.расст:<br>";
        echo "<table>";
        echo "<tr>"; 
        echo "<th style='padding: 6px'> B</th>";
        for($j = 0; $j <count($matrix); $j++) {
            echo "<th style='padding: 6px'>".$j."</th>";
        }
        echo "</tr>";    
        for($i = 0; $i<count($matrix); $i++){
            echo "<tr style='display: table-row'>";
            echo "<td><b> ".$i."</b></td>";
            for($j = 0; $j <count($matrix[$i]); $j++) {
                echo "<td> ".$matrix[$i][$j]."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
    }
