<?php
$obj_amount=10;
$obj_array=array("FT","ST","TP","RP","MT","TB","TL","UT","TS","DT");
$experts_amount=8;
$l=1.64485363;
$A=array();
$Y=array(); //A but common
$P=array();
$Z=array();
$Sum_Z_i=array();
$Z_i=array();
$Sum_Z_j=array();
$Z_j=array();
$beta_i=array();
$F_z_i=array();
$F_z_j=array();
for($i=0;$i<$experts_amount;$i++){
    $A[$i]=array();
}

for($i=0;$i<$obj_amount;$i++){
    $Y[$i]=array();
    $Z[$i]=array();
    for($j=0;$j<$obj_amount;$j++){
        $Y[$i][$j]=0;
        $Z[$i][$j]=0;
    }
}

read();
echo "<p>Матрицы парных сравнений экспертов</p>";
show_2($A);
make_common();
echo "<p>Обобщенная матрица парных сравнений</p>";
show($Y);
$D=getD();
echo "<p>(коэф. при К)<b>D</b>= ".$D."</p><br>";
$K=getK($D);
echo "<p>Коэф. конкордации <b>K</b>= ".$K."</p><br>";
$v=getV();
echo "<p>Степени свободы <b>v</b>= ".$v."</p><br>";
$X=getX($v, $D);
echo "<p><b>X^2</b>= ".$X."</p><br>";
$P= getP($Y);
echo "<p>Матрица вероятностей</p>";
show($P);
echo "<br>";
$Z= getZ($P);
echo "<p>Матрица оценок разностей важности</p>";
show($Z);
echo "<br>";
$Sum_Z_i=getSumZ_i($Z);
$Sum_Z_j=getSumZ_j($Z);
echo "<br>";
echo "<p><b>∑zij</b></p>";
show_1($Sum_Z_i);
echo "<br>";
$Z_i=getZ_z($Sum_Z_i);
$Z_j=getZ_z($Sum_Z_j);
echo "<p><b>Ẑi</b></p>";
show_1($Z_i);
echo "<br>";
$F_z_i=getF_z($Z_i);
$F_z_j=getF_z($Z_j);
echo "<p><b>Ф(Ẑi)</b></p>";
show_1($F_z_i);
echo "<br>";
$beta_i=getBeta($F_z_i, $F_z_j);
echo "<p><b>β_i</b></p>";
show_1($beta_i);
echo "<br>";

function read(){
    global $obj_amount, $A;
    $inputFiles = glob('data/*.txt');
    $num=0;
    foreach($inputFiles as $input){
        $readStr = fopen($input, 'r');
        $tmp=array();
        for($i = 0; $i<$obj_amount;$i++){
            $str = trim(fgets($readStr), " \n");
            // $str = trim($str, " \t");
            $array = explode("\t", $str);
            $tmp[$i]=array();
            $tmp[$i]=$array;
        }
        $A[$num] = $tmp;
        $num++;
    }
    // var_dump($A);
}

function make_common(){
    global $obj_amount, $A, $Y;
    foreach ($A as $a){
        for($i=0;$i<$obj_amount;$i++){
            for($j=0;$j<$obj_amount;$j++){
                if($a[$i][$j]==="X"){
                    $Y[$i][$j]=INF;
                }
                else{
                    $Y[$i][$j] += intval($a[$i][$j]);
                }
            }
        }
    }
}

function show(array $A){
    global $obj_array;
    echo "<table>";
    echo "<tr style='display: table-row'>";
    echo "<td style='padding: 6px'>  </td>";
    for($j=0;$j<count($obj_array);$j++){
        echo "<td style='padding: 6px'><b>".$obj_array[$j]."</b></td>";
    }
    echo "</tr>";
    for($i=0;$i<count($A);$i++){
        echo "<tr style='display: table-row'>";
        echo "<td style='padding: 6px'><b>".$obj_array[$i]."</b></td>";
        for($j=0;$j<count($A[$i]);$j++){
            echo "<td style='padding: 6px'> ".$A[$i][$j]."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<br>";
    echo "<br>";
}

function show_1($A){
    echo "<table>";
    echo "<tr style='display: table-row'>";
    for($i=0;$i<count($A);$i++){
        echo "<td style='padding: 6px'>".round($A[$i], 3)."</td>";
    }
    echo "</tr>";
    echo "</table>";
    echo "<br>";
}

function show_2(array $A){
    global $obj_array;
    for($i=0;$i<count($A);$i++){
        echo "<table>";
        echo "<tr style='display: table-row'>";
        echo "<td style='padding: 6px'><b>".($i+1)."</b></td>";
        for($j=0;$j<count($obj_array);$j++){
            echo "<td style='padding: 6px'><b>".$obj_array[$j]."</b></td>";
        }
        echo "</tr>";
        for($j=0;$j<count($A[$i]);$j++){
            echo "<tr style='display: table-row'>";
            echo "<td style='padding: 6px'><b>".$obj_array[$j]."</b></td>";
            for($k=0;$k<count($A[$i][$j]);$k++){
                echo "<td style='padding: 6px'> ".$A[$i][$j][$k]."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
        echo "<br>";
    }
}

function fact($a){
    if($a!==0){
        return $a * fact ($a-1);
    }
    else{
        return 1;
    }
}

function getD(){
    global $obj_amount, $experts_amount, $Y;
    $first_part=0;
    $second_part=0;
    $third_part=0;
    for($i=0;$i<$obj_amount;$i++){
        for($j=0;$j<$i;$j++){
            $first_part+=pow($Y[$i][$j], 2);
            $second_part+=$Y[$i][$j];
        }
    }
    $third_part=(fact($experts_amount)/(fact(2)*fact($experts_amount-2)))*(fact($obj_amount)/(fact(2)*fact($obj_amount-2)));
    return ($first_part-($experts_amount*$second_part)+$third_part);
}

function getK($D){
    global $obj_amount, $experts_amount;
    return (4/($obj_amount*($obj_amount-1)*$experts_amount*($experts_amount-1)))*$D;
}

function getV(){
    global $obj_amount, $experts_amount;
    return (fact($obj_amount)/(fact(2)*fact($obj_amount-2)))*(($experts_amount*($experts_amount-1))/pow(($experts_amount-2), 2));
}

function getX($v, $D){
    global $obj_amount, $experts_amount, $l;
    if($v<=100){
        return ((4/($experts_amount-2))*($D-(1/2)*(fact($experts_amount)/(fact(2)*fact($experts_amount-2)))*(fact($obj_amount)/(fact(2)*fact($obj_amount-2)))*(($experts_amount-3)/($obj_amount-2))));
    }
    else{
        return ($v+$l*pow(2*$v, (1/2))+((2/3)*(pow($l, 2)-1))+((pow($l, 3)-7*$l)/(9*pow(2*$l, (1/2)))));
    }
}

function getP($A){
    global $obj_amount, $experts_amount;
    $tmp=array();
    for($i=0;$i<$obj_amount;$i++){
        $tmp[$i]=array();
        for($j=0;$j<$obj_amount;$j++){
            // echo "i=".$i." j=".$j." ".$A[$i][$j]."/".$experts_amount."<br>";
            $tmp[$i][$j]=$A[$i][$j]/$experts_amount;
            if($tmp[$i][$j]==0){
                $tmp[$i][$j]=1/(2*$experts_amount);
            }
            else if($tmp[$i][$j]==1){
                $tmp[$i][$j]=(1-(1/(2*$experts_amount)));
            }
            else if($tmp[$i][$j]==INF){
                $tmp[$i][$j]=0;
            }
        }
    }
    return $tmp;
}

function getZ($P){
    global $obj_amount;
    $tmp = array();
    for ($i=0; $i<$obj_amount;$i++){
        $tmp[$i]=array();
        for ($j=0;$j<$obj_amount;$j++){
            $tmp[$i][$j]=laplace_back($P[$i][$j]);
            // echo laplace_back($P[$i][$j])." ";
        }
        // echo "<br>";
    }
    for ($i=0; $i<$obj_amount;$i++){
        for ($j=0;$j<$obj_amount;$j++){
            if($tmp[$i][$j]==0 && $i!=$j && $tmp[$j][$i]!=0){
                $tmp[$i][$j]=-1*$tmp[$j][$i];
            }
        }
    }
    // show($tmp);
    return $tmp;
}

function laplace($z){
    $z/=pow(2,(1/2));
    $first_part=(2/pow(pi(), (1/2)));
    $second_part=0;
    for($i=0;$i<5;$i++){
        $second_part+=pow(-1, $i)*pow($z, 2*$i+1)/(fact($i)*(2*$i+1));
    }
    return (($first_part*$second_part)+1)/2;
}

function laplace_back($z){
    // $result=0;
    $file="data_laplace.txt";
    $strs=file($file);
    $Z=strval($z);
    foreach($strs as $str) {
        $tmp=preg_split('/\s+/', $str);
        if($z<=0.5){
            $tmp[1]='0.5';
            break;
        }
        if(str_contains($tmp[1], $Z)!==false){
            // $result=$tmp[0];
            break;
        }
    }
    return floatval($tmp[0]);
}

function laplace_back_search($z){
    $z/=pow(2,(1/2));
    echo $z."<br>";
    $output=shell_exec('python laplace_back.py '.$z);
    echo "<br>".$output."<br>";
    $output=($output+1)/2;
    echo "<br>".$output."<br>";
    return $output;
}

function getSumZ_i($Z){
    global $obj_amount;
    $Sum_Z_i= array();
    for($i=0;$i<$obj_amount;$i++){
        $tmp=0;
        for($j=0;$j<$obj_amount;$j++){
            $tmp += $Z[$i][$j];
        }
        $Sum_Z_i[$i]=$tmp;
    }
    return $Sum_Z_i;
}

function getSumZ_j($Z){
    global $obj_amount;
    $Sum_Z_j= array();
    for($i=0;$i<$obj_amount;$i++){
        $tmp=0;
        for($j=0;$j<$obj_amount;$j++){
            $tmp += $Z[$j][$i];
        }
        $Sum_Z_j[$i]=$tmp;
    }
    return $Sum_Z_j;
}

function getZ_z($Sum_Z){
    global $obj_amount;
    $tmp=array();
    for($i=0;$i<$obj_amount;$i++){
        $tmp[$i]=$Sum_Z[$i]/$obj_amount;
    }
    return $tmp;
}

function getF_z($Z){
    global $obj_amount;
    $tmp=array();
    for($i=0;$i<$obj_amount;$i++){
        $tmp[$i]=laplace($Z[$i]);
    }
    return $tmp;
}

function getBeta($F_z_i, $F_z_j){
    global $obj_amount;
    $tmp=array();
    $sum=0;
    for($i=0;$i<$obj_amount;$i++){
        $sum+=$F_z_j[$i];
    }
    for($i=0;$i<$obj_amount;$i++){
        $tmp[$i]=$F_z_i[$i]/$sum;
    }
    return $tmp;
}