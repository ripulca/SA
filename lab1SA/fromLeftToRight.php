<a href="index.php"> НАЗАД </a><br>
<?php
error_reporting(0);
reading();

    function reading (){
        $node_amount=$_POST['node_amount'];
        // echo $_POST['type'];
        if($_POST['type']==1){
            $type=false;
        }
        else{
            $type= true;
        }
        $G_minus=[];
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
        make_right($G_minus, $node_amount, $type);
    }

    function make_right($G_minus, $node_amount, $type){
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
        make_matrix($G_plus, $node_amount, $type);
    }
    function make_matrix($G_plus, $node_amount, $type){
        $arc=0;
        echo "<br>";
        echo "<br>";
        echo "Все дуги:<br>";
        if($type==1){
            for($i = 0; $i <$node_amount; $i++){
                foreach($G_plus[$i] as $val){
                    echo "(".$i.")--e".$arc."->(".(int)$val.")<br>";
                    $arc++;
                }
            }
            $matrix=array();
            for($i=0;$i<=$node_amount;$i++){
                $matrix[$i]=array();
                for($j=0;$j<=$arc;$j++){
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
