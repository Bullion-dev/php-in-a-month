<?php
session_start();
?>
<?php
if(isset($_POST["logout"])){
     session_destroy();
    header("Location: index1.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Out</title>
</head>
<body>
   This is the Logout page <br>
   <form action="logout.php" method="post">
    <input type="submit" name="logout" value="logout">
</form>
</body>
</html>

