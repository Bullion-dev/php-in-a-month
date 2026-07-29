<?php

?>
<?php
session_start();

$error = "";

if(isset($_POST["login"])){ // only check if the form was actually submitted before

if(!empty($_POST["username"]) &&
!empty($_POST["password"])){


$_SESSION["username"] = $_POST["username"]; 
$_SESSION["password"] = $_POST["password"];


header("location: logout.php");
}else{
    $error = "invalid credentials"; // just store the message, don't print it yet
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <form action="index1.php" method="post">
    Username:<br> <input type="text" name="username"><br>
     Password:<br> <input type="password" name="password"><br>
    <input type="submit" name="login">
</form>

<?php if ($error != "") { echo $error; } ?>

</body>
</html>

