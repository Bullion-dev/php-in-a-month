<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <?php
  $phrase = "Echo Room";
  $phrase[0] = "I";
  echo str_replace("Icho", "Echo", $phrase);
  echo strtolower($phrase);
  echo substr($phrase, 5, 3);
  ?>  
</body>
</html>