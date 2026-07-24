<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
  <form action="site.php" method="post">
  Student: <input type="text" name="student">
<input type="submit">;
  </form>
<?php 
$grades = array("jim" => "B", "Cole" => "A++", "Rex" => "C");
if (isset($_POST["student"])){
    $key = $_POST["student"];
echo $grades[$key];
}
?>
</body>
</html>