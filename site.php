<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
  <form action="site.php" method="post">
   First Number: <input type="number" name="num1">
   OP: <input type="text" name="op">
   Second Number: <input type="number" name="num2">

   <input type="submit">
</form> 
<?php 
$num1 = $_POST["num1"];
$op = $_POST["op"];
$num2 = $_POST["num2"];

if($op == "+"){
    echo $num + $num2;
}
    elseif($op == "-"){
        echo $num - $num2;
    }
     elseif($op == "/"){
        echo $num / $num2;
    }
     elseif($op == "*"){
        echo $num * $num2;
    }
    else{
        echo "invalid operator";
    }
?>
</body>
</html>