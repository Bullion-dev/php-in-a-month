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
    echo "The {$this->make} was one of the most popular cars in {$this->year}<br>";
}

function isClassic(){
    switch($this->year){
        case "2015":
            echo "This car is a classic <br>";
            break;
        case "2018":
            echo "This car is not classic <br>";
            break;
        case "2022":
            echo "This car is not a classic <br>";
            break;
        default:
            echo "NOT IN RANGE <br>";
    }
}
}


$car1 = new Car("Porsche", "Mustang", 2015);
$car2 = new Car("Honda", "Civic", 2022);

$car1->describe();
$car1->isClassic();
$car2->describe();
$car2->isClassic();

  ?>
</body>
</html>