<a href="index.php"> НАЗАД </a><br>
<?php
// error_reporting(0);
$node_amount=$_POST['node_amount']; //кол-во вершин
$arc_amount=0;
$matched=array();
for($i=0;$i< $node_amount; $i++){
    $matched[$i]=false;
}
if($_POST['type']==1){
    // echo "неор";
    $type=false;
}
else{
    // echo "ор";
    $type= true;
}
$g=array();
$gr=array();
$arcs=array();
for($i=0;$i<$node_amount; $i++){
    $g[$i]=array();
    $gr[$i]=array();
    $arcs[$i]=array();
}
$used=array();
$order=array();
$component=array();
$G=array();
$E=array();

reading();

    function reading (){
        // echo $_POST['type'];
        global $node_amount, $type;
        $G_minus=array();
        for($i=0;$i<$node_amount;$i++){
            preg_match_all("/\d+/", $_POST['G-'.$i], $nodes);
            array_push($G_minus, $nodes[0]);
        }
        $i=0;
        echo "Множество левых инциденций:<br>";
        foreach($G_minus as $val){
            echo "Вершина ".$i.": ";
            foreach($val as $el){
                echo " ".$el." ";
            }
            echo "<br>";
            $i++;
        }
        make_right($G_minus);
    }

    function make_right($G_minus){
        global $node_amount, $type;
        echo "<br>";
        echo "Множество правых инциденций:<br>";
        if($type==1){
            $G_plus = array();
            for($i=0; $i<$node_amount; $i++){
                $G_plus[$i]=array();
            }
            for($i=0; $i<$node_amount; $i++){
                foreach($G_minus[$i] as $val){
                    // echo $val."<br>";
                    array_push($G_plus[(int)$val], $i);
                }
            }
        }
        else{
            $G_plus=$G_minus;
        }
        $i=0;
        foreach($G_plus as $val){
            echo "Вершина ".$i.": ";
            asort($val);
            foreach($val as $el){
                echo " ".$el." ";
            }
            echo "<br>";
            $i++;
        }
        echo "<br>";
        
        make_matrix($G_plus);
    }
    
    function make_matrix_sm($G_plus){
        global $node_amount, $type;
        $ready=array();
        for($i=0; $i< $node_amount; $i++){
            $ready[$i]=array();
            for($j=0; $j<$node_amount; $j++){
                $ready[$i][$j]=0;
            }
        }
        if($type==1){
            for($i=0; $i<$node_amount; $i++){
                foreach($G_plus[$i] as $val){
                    if($ready[$i][$val]==0){
                        $ready[$i][$val]=1;
                    }
                    // if($ready[$val][$i]==0){
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
        echo "<br>";
        for($i = 0; $i <$node_amount; $i++){
            for($j = 0; $j < $node_amount; $j++){
                echo $ready[$i][$j]." ";
            }
            echo "<br>";
        }
        return $ready;
    }

    function make_matrix($G_plus){
        global $node_amount, $type, $arc_amount;
        global $g, $gr, $arcs;
        $arc=0;
        $ready=make_matrix_sm($G_plus);
        echo "<br>";
        echo "<br>";
        echo "Все дуги:<br>";
        if($type==1){
            for($i = 0; $i <$node_amount; $i++){
                foreach($G_plus[$i] as $val){
                    echo "(".$i.")--e".$arc."->(".(int)$val.")<br>";
                    array_push($g[$i], (int)$val);
                    array_push($gr[(int)$val], $i);
                    $arcs[$arc]=array();
                    array_push($arcs[$arc], $i);
                    array_push($arcs[$arc], (int)$val);
                    $arc++;
                }
            }
            $arc_amount=$arc;
        }
        else{
            $ready=array();
            echo "<br>";
            for($i=0;$i<$node_amount; $i++){
                for($j=$i;$j<$node_amount; $j++){
                    if($ready[$i][$j]==1){
                        echo "(".$i.")--e".$arc."--(".$j.")<br>";
                        array_push($g[$i], $j);
                        array_push($gr[$j], $i);
                        array_push($arcs[$arc], $i);
                        array_push($arcs[$arc], $j);
                        $arc++;
                    }
                }
            }
        }
        make_list();
        $matrix_sm=make_new_matrix($ready);
        $arc=0;
        // echo "<br>";
        for($i = 0; $i <count($matrix_sm); $i++){
            for($j = 0; $j < count($matrix_sm); $j++){
                // echo $matrix_sm[$i][$j]." ";
                if($matrix_sm[$i][$j]==1){
                    $arc++;
                }
            }
            // echo "<br>";
        }
        // echo $arc;
        $matrix_inc=array();
        for($i=0;$i<count($matrix_sm);$i++){
            $matrix_inc[$i]=array();
            for($j=0;$j<$arc;$j++){
                $matrix_inc[$i][$j]=0;
            }
        }
        $arc=0;
        for($i=0;$i<count($matrix_sm);$i++){
            for($j = 0; $j < count($matrix_sm);$j++){
                if($matrix_sm[$i][$j]==1){
                    $matrix_inc[$i][$arc]=1;
                    $matrix_inc[$j][$arc]=-1;
                    $arc++;
                }
            }
        }
        echo "<br>";
        echo "<br>";
        echo "Матрица инциденций конечного графа:<br>";
        echo "<table>";
        echo "<tr>"; 
        echo "<th style='padding: 6px'> B</th>";
        for($j = 0; $j <$arc; $j++) {
            echo "<th style='padding: 6px'> e".$j."</th>";
        }
        echo "</tr>";    
        for($i = 0; $i<count($matrix_inc); $i++){
            echo "<tr style='display: table-row'>";
            echo "<td><b> G".$i."</b></td>";
            for($j = 0; $j <count($matrix_inc[$i]); $j++) {
                echo "<td> ".$matrix_inc[$i][$j]."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
    }
    
    function make_new_matrix($matrix){
        global $node_amount, $arc_amount, $G, $E, $arcs;
        $matrix_sm=array();
        for($i=0;$i<count($G); $i++){
            $matrix_sm[$i]=array();
            for($j=0;$j<count($G); $j++){
                $matrix_sm[$i][$j]=0;
            }
        }
        // $point_i=0;
        // $point_j=0;
        for($i=0;$i<count($matrix); $i++){
            for($j=0;$j<count($matrix[$i]); $j++){
                $point_i=INF;
                $point_j=INF;
                if($matrix[$i][$j]==1){
                    for($k=0;$k<count($G);$k++){
                        if(array_search($i, $G[$k])!==false){
                            $point_i=$k;
                        }
                        if(array_search($j, $G[$k])!==false){
                            $point_j=$k;
                        }
                    }
                    if($point_i!=$point_j && $point_i!=INF && $point_j!=INF){
                        // echo "[".$i."][".$j."]= ".$matrix[$i][$j]."<br>";
                        // echo "G".$point_i." = ".$i."(i)<br>";
                        // echo "G".$point_j." = ".$j."(j)<br><br>";
                        $matrix_sm[$point_i][$point_j]=1;
                        $matrix[$i][$j]=0;
                    }
                }
            }
        }
        return $matrix_sm;
    }

    function make_list(){
        global $used, $node_amount, $order, $component, $G, $E, $arcs;
        for($i=0;$i<$node_amount; $i++){
            $used[$i]=false;
        }
        for($i=0; $i<$node_amount; ++$i){
            if(!$used[$i]){
                dfs1($i);
            }
        }
        for($i=0;$i<$node_amount; $i++){
            $used[$i]=false;
        }
        for($i=0; $i<$node_amount; ++$i){
            $v=$order[$node_amount-1-$i];
            if(!$used[$v]){
                dfs2($v);
                array_push($G, $component);
                $component=array();
            }
        }
        for($i=0;$i<count($G);$i++){
            echo "<br>G".$i.": V(";
            foreach($G[$i] as $val){
                echo $val." ";
            }
            echo ")  ";
            echo "E(";
            if(count($G[$i])==1){
                echo " no arcs";
            }
            else{
                for($j=0; $j<count($G[$i]); $j++){
                    $first=$G[$i][$j];
                    // echo "first=".$first."<br>";
                    for($k=0; $k<count($G[$i]); $k++){
                        $second=$G[$i][$k];
                        // echo "second=".$second."<br>";
                        if($first!=$second){
                            for($f=0; $f<count($arcs); $f++){
                                if((array_search($first, $arcs[$f])!==false)&&(array_search($second, $arcs[$f])!==false)){
                                    if(array_search($f, $E)===false){
                                        array_push($E, $f);
                                    }
                                }
                            }
                        }
                    }
                }
                foreach($E as $val){
                    echo $val." ";
                }
            }
            echo ")<br>";
        }

    }

    function dfs1($v){
        global $used, $g, $order;
        $used[$v]=true;
        for($i=0; $i<count($g[$v]); ++$i){
            if(!$used[$g[$v][$i]]){
                dfs1($g[$v][$i]);
            }
        }
        array_push($order, $v);
    }

    function dfs2($v){
        global $used, $gr, $component;
        $used[$v]=true;
        array_push($component, $v);
        for($i=0; $i<count($gr[$v]); ++$i){
            if(!$used[$gr[$v][$i]]){
                dfs2($gr[$v][$i]);
            }
        }
    }

