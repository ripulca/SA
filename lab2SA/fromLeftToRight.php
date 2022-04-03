<a href="index.php"> НАЗАД </a><br>
<?php
// error_reporting(0);
$node_amount=$_POST['node_amount']; //кол-во вершин
$mark=-1;  //метка наличия контура
$tmp=array();  //массив выделение контура
$lvls=array();  //иерархия вершин
// for($i=0;$i<$node_amount;$i++){
//     $lvls[$i]=array();
// }
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
// $ways=array();

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
        global $node_amount, $type, $lvls, $mark;
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
        
        if($type==1){
            validate($G_minus, $G_plus);
            if($mark!=1){
                echo "<br>Иерархические уровни:<br>";
                foreach($lvls as $l){
                    foreach($l as $el){
                        echo $el." ";
                    }
                    echo "<br>";
                }
            }
        }
        make_matrix($G_plus);
    }

    function validate($G_minus, $G_plus){
        global $mark, $tmp;
        global $node_amount, $type;
        for($i=0;$i<$node_amount;$i++){
            // $start=$i;
            dfs($G_plus, $i, $mark);
            unset($tmp);
            $tmp=array();
            if($mark!=-1){
                break;
            }
        }
        if($mark!=-1){
            echo "<br>Ошибка выделения иерархических уровней. Есть контур.<br><br>";
        }
        else{
            range_nodes($G_minus, $G_plus);
        }
    }

    function dfs($G_plus, $i, $mark){
        global $mark, $tmp;
        if(array_search($i, $tmp)===false){
            array_push($tmp, $i);
            for($j=0;$j<count($G_plus[$i]);$j++){
                dfs($G_plus, $G_plus[$i][$j], $mark);
            }
            array_splice($tmp, count($tmp)-1);
        }
        else{
            $mark=1;
            return;
        }
    }

    function range_nodes($G_minus, $G_plus){
        global $node_amount, $type, $lvls, $matched;
            $lvls[0]=array();
        for($i=0;$i<$node_amount;$i++){
            // var_dump($G_minus[$i]);
            // echo "<br>";
            // echo "<br>";
            if(empty($G_minus[$i])){        //запись вершин первого уровня
                array_push($lvls[0], $i);
                // $matched[$i]=true;
            }
        }
        // var_dump($lvls);
        // echo "<br>";
        foreach($lvls[0] as $val){          //проход по стартовым вершинам
            match_nodes($G_minus, $G_plus, $val);
        }
        for($i=0;$i<count($lvls);$i++){
            for($j=0;$j<count($lvls[$i]);$j++){

            }
        }
    }

    function match_nodes($G_minus, $G_plus, $val){
        global $lvls, $matched;
        $temp=0;
        $lvl=0;
        // echo "start V ".$val."<br><br>";
        for($j=0; $j<count($lvls);$j++){
            if(empty($lvls[$j])||empty($G_minus[$val])){break;}
            foreach($G_minus[$val] as $G){
                if(array_search($G, $lvls[$j])!==false){
                    if($j>$temp){$temp=$j;}
                    // echo "lowest parent lvl ".$j."<br><br>";
                    $lvl=$temp+1;
                }
            }
        }
        if($lvl==1){
            if(count($G_minus[$val])==1){
                if(empty($lvls[$lvl])){$lvls[$lvl]=array();}
                if(array_search($val, $lvls[$lvl])===false){
                    for($i=0;$i<count($lvls); $i++){
                        if(array_search($val, $lvls[$i])!==false){
                            unset($lvls[$i][array_search($val, $lvls[$i])]);
                        }
                    }
                    array_push($lvls[$lvl], $val);
                    // echo "accepted ".$val."<br><br>";
                }
                
                for($i=0;$i<count($G_plus[$val]);$i++){
                    // echo $val." child ".$G_plus[$val][$i]."<br><br>";
                    match_nodes($G_minus, $G_plus, $G_plus[$val][$i]);
                }
            }
        }
        else{
            if(empty($lvls[$lvl])){$lvls[$lvl]=array();}
            if(array_search($val, $lvls[$lvl])===false){
                for($i=0;$i<count($lvls); $i++){
                    if(array_search($val, $lvls[$i])!==false){
                        unset($lvls[$i][array_search($val, $lvls[$i])]);
                    }
                }
                array_push($lvls[$lvl], $val);
                // echo "accepted ".$val."<br><br>";
            }
            
            for($i=0;$i<count($G_plus[$val]);$i++){
                // echo $val." child ".$G_plus[$val][$i]."<br><br>";
                match_nodes($G_minus, $G_plus, $G_plus[$val][$i]);
            }
        }
    }

    function make_matrix($G_plus){
        global $node_amount, $type, $lvls, $mark;
        $arc=0;
        // echo "<br>";
        // echo "<br>";
        // echo "Все дуги:<br>";
        if($type==1){
            for($i = 0; $i <$node_amount; $i++){
                foreach($G_plus[$i] as $val){
                    // echo "(".$i.")--e".$arc."->(".(int)$val.")<br>";
                    $arc++;
                }
            }
            if($mark==-1){
                $new_nodes = array();
                $n=0;
                foreach ($lvls as $lvl){
                    foreach ($lvl as $el){
                        $new_nodes[$n]=$el;
                        // echo $n." -> ".$new_nodes[$n]."<br>";
                        $n++;
                    }
                }
                echo "<br>";
                $new_nodes_l = array();
                $n=0;
                foreach ($lvls as $lvl){
                    foreach ($lvl as $el){
                        $new_nodes_l[$el]=$n;
                        echo $el." -> ".$new_nodes_l[$el]."<br>";
                        $n++;
                    }
                }
                $matrix=array();
                for($i=0;$i<$node_amount;$i++){
                    $matrix[$i]=array();
                    for($j=0;$j<$arc;$j++){
                        $matrix[$i][$j]=0;
                    }
                }
                echo "<br>";
                $arc=0;
                for($i = 0; $i <$node_amount; $i++){
                    // echo $i." - ";
                    foreach($G_plus[$new_nodes[$i]] as $val){
                        // echo "<br>(".$arc.") -".$val.">".$new_nodes_l[$val];
                        $matrix[$i][$arc]=1;
                        $matrix[$new_nodes_l[$val]][$arc]=-1;
                        if($i==$new_nodes_l[$val]){
                            $matrix[$new_nodes_l[$val]][$arc]=2;
                        }
                        $arc++;
                    }
                    // echo "<br><br>";
                }
            }
            else{
                $matrix=array();
                for($i=0;$i<$node_amount;$i++){
                    $matrix[$i]=array();
                    for($j=0;$j<$arc;$j++){
                        $matrix[$i][$j]=0;
                    }
                }
                echo "<br>";
                $arc=0;
                for($i = 0; $i <$node_amount; $i++){
                    foreach($G_plus[$i] as $val){
                        $matrix[$i][$arc]=1;
                        $matrix[$val][$arc]=-1;
                        if($i==$val){
                            $matrix[$val][$arc]=2;
                        }
                        $arc++;
                    }
                    echo "<br>";
                }
            }
        }
        else{
            $ready=array();
            $matrix=array();
            for($i=0; $i< $node_amount; $i++){
                $ready[$i]=array();
                for($j=0; $j<$node_amount; $j++){
                    $ready[$i][$j]=0;
                }
            }
            for($i = 0; $i <$node_amount; $i++){
                foreach($G_plus[$i] as $val){
                    $ready[$i][$val]=1;
                }
            }
            echo "<br>";
            
            for($i=0;$i<$node_amount; $i++){
                for($j=$i;$j<$node_amount; $j++){
                    if($ready[$i][$j]==1){
                        echo "(".$i.")--e".$arc."--(".$j.")<br>";
                        $arc++;
                    }
                }
            }
            for($i=0;$i<=$node_amount;$i++){
                $matrix[$i]=array();
                for($j=0;$j<=$arc;$j++){
                    $matrix[$i][$j]=0;
                }
            }
            $arc=0;
            for($i=0;$i<$node_amount; $i++){
                for($j=$i;$j<$node_amount; $j++){
                    if($ready[$i][$j]==1){
                        $matrix[$i][$arc]=1;
                        $matrix[$j][$arc]=1;
                        $arc++;
                    }
                }
            }
        }
        echo "<br>";
        echo "Матрица инциденций:<br>";
        echo "<table>";
        echo "<tr>"; 
        echo "<th style='padding: 6px'> B</th>";
        for($j = 0; $j <$arc; $j++) {
            echo "<th style='padding: 6px'> e".$j."</th>";
        }
        echo "</tr>";    
        for($i = 0; $i<$node_amount; $i++){
            echo "<tr style='display: table-row'>";
            echo "<td><b> ".$i."</b></td>";
            for($j = 0; $j <$arc; $j++) {
                echo "<td> ".$matrix[$i][$j]."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
