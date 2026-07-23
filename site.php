<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
  <form action="site.php" method="post">
    Password: <input type="password" name="password"><br>
   
<input type="submit">
  </form>
<?php 
echo $_POST["password"];//POST is more secure than GET. 
//Stops your info from showing up in the URL. 
// Got passed between the client and the server
?>
</body>
</html>