<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
  <form action="site.php" method="post">
  Apples: <input type="checkbox" name="fruits[]" value="apples" ><br>
   Oranges: <input type="checkbox" name="fruits[]" value="oranges" ><br>
   Pears: <input type="checkbox" name="fruits[]" value="pears" ><br>

<input type="submit">;
  </form>
<?php 
if (isset($_POST["fruits"])) {
    $fruits = $_POST["fruits"];
    foreach ($fruits as $fruit) {
        echo "$fruit <br>";
    }
}
?>
</body>
</html>