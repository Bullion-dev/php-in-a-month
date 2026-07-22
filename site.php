<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
  <form action="site.php" method="get">
    Name: <input type="text" name="name"><br><br>
    Age: <input type="number" name="age">
<input type="submit">
  </form>
  <br>
  Your name is <?php 
  echo $_GET["name"]//name of the input you want to grab
  ?>
  <br>
   You are <?php 
  echo $_GET["age"]//name of the input you want to grab
  ?> years old.
</body>
</html>