<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>  
<?php 
class Car {
var $make;
var $model;
var $year;

/* A constructor is basically 
a function that will be called 
whenever we create an object of a class*/

function __construct($aMake, $aModel, $aYear){
    /*Left side = the object's permanent storage. 
    
    Right side = the temporary value that just arrived
    
    you need $this-> to access the object's stored properties*/

$this->make = $aMake;
$this->model = $aModel;
$this->year = $aYear;
}

function describe(){
    echo "The {$this->make} was one of the most popular cars in {$this->year}";
}
}


$car1 = new Car("Porsche", "Mustang", 2015);

$car1->describe();

  ?>
</body>
</html>