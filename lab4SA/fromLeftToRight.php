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

reading();

    function reading(){
        global $node_amount, $type;
        $matrix_first=array();
        if($type==1){
            for($i=0;$i<$node_amount;$i++){
                $matrix_first[$i]=array();
                for($j=0;$j<$node_amount;$j++){
                    $matrix_first[$i][$j]=(int)$_POST["V-".$i."-".$j];
                    if($i==$j){
                        $matrix_first[$i][$j]=0;
                    }
                    if($_POST["V-".$i."-".$j]=="INF"){
                        $matrix_first[$i][$j]=INF;
                    }
                    echo $matrix_first[$i][$j]." ";
                }
                echo "<br>";
            }
        }
        else{
            for($i=0;$i<$node_amount;$i++){
                $matrix_first[$i]=array();
                for($j=0;$j<=$i;$j++){
                    $matrix_first[$i][$j]=(int)$_POST["V-".$i."-".$j];
                    if($i==$j){
                        $matrix_first[$i][$j]=0;
                    }
                    if($_POST["V-".$i."-".$j]=="INF"){
                        $matrix_first[$i][$j]=INF;
                    }
                    // echo $matrix_first[$i][$j]." ";
                    $matrix_first[$j][$i]=$matrix_first[$i][$j];
                }
                // echo "<br>";
            }
        }
        for($i=0;$i<count($matrix_first);$i++){
            for($j=0;$j<count($matrix_first[$i]);$j++){
                echo $matrix_first[$i][$j]." ";
            }
            echo "<br>";
        }
        echo "<br>";
        // for($i=0;$i<$node_amount;$i++){
        //     for($j=0;$j<$node_amount;$j++){
        //         if($matrix_first[$i][$j]!==0){
        //             $arcs++;
        //         }
        //     }
        // }
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
