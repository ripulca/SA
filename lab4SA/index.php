<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab1_SA</title>
</head>
    <body>
        <div class="app">
            <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                Граф ориентированный?  <input type="checkbox" name="type">
                <br><br>
                Кол-во вершин: <input name="node_amount">
                <button type="submit">Ок</button>
            </form>
            <br><br>
            
            <form method="POST" action="fromLeftToRight.php">
                <?php
                    if(isset($_POST["node_amount"])):
                        $node_amount=$_POST["node_amount"];
                        if(isset($_POST["type"])){
                            $type=false;
                        }
                        else{
                            $type=true;
                        }
                ?>
                    <p>Введите матрицу расстояний смежных вершин</p>
                    <table>
                    <tr>
                    <th style='padding: 6px'> B</th>
                    <?php for($j = 0; $j <$node_amount; $j++):?>
                        <th style='padding: 6px'> <?php echo $j?></th>
                    <?php endfor;?>
                    </tr>     
                    <?php for($i=0;$i<$node_amount; $i++):?>
                        <tr style='display: table-row'>
                        <td><b> <?php echo $i." "?></b></td>
                        <?php for($j=0;$j<$node_amount; $j++):?>
                            <td><input style='width:30px' name="<?php echo "V-".$i."-".$j; ?>" value="<?php if($i==$j){echo "0";}?>"></td>
                        <?php endfor;?>
                        </tr>
                    <?php endfor;?>
                    </table>
                    <br>
                    <input name="node_amount" hidden="true" value="<?php echo $node_amount; ?>">
                    <input name="type" hidden="true" value="<?php echo $type; ?>">
                    <button type="submit">Submit</button>
                <?php endif;?>
            </form>


        </div>
    </body>
</html>