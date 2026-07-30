<?php

// This is a car inventory project where each submitted car becomes a proper
// Car object (built from a class blueprint) instead of a plain sentence, and
// those objects get stored in the same growing session array as before —
// then each object prints itself using its own describe() method.

// Start (or resume) the session — must run before any HTML/output,
// so PHP can track this user's data across page loads
session_start();

// The blueprint: describes what every Car object will have and can do.
// This doesn't create any actual car yet — just defines the shape of one.
class Cars {
    var $make;
    var $model;
    var $year;
    var $price;

    // Runs automatically every time we create a new Car object with "new Cars(...)"
    function __construct($aMake, $aModel, $aYear, $aPrice){
        // Store each incoming value onto THIS specific object's own properties
        $this->make = $aMake;
        $this->model = $aModel;
        $this->year = $aYear;
        $this->price = $aPrice;
    }

    // A method that prints this specific car's own stored data
    function describe(){
        echo "{$this->make} {$this->model} ({$this->year}) - \${$this->price}<br>";
    }
}

// Check: does our "cars" list already exist in this session?
// Only true on the very first visit — after that, this block is skipped,
// leaving whatever cars are already stored untouched
if (!isset($_SESSION["cars"])) {
    // First time ever visiting — create an empty list to start collecting cars into
    $_SESSION["cars"] = array();
}

// Check: did the person fill in all four fields with actual values (not blank)?
// Runs on every page load, but only passes if a real submission happened
if (!empty($_POST["make"]) &&
    !empty($_POST["model"]) &&
    !empty($_POST["year"]) &&
    !empty($_POST["price"])) {

    // Double-check: was the Submit button specifically clicked?
    if ($_POST["submit"]) {

        // Build one real Car object using the four submitted values
        $newCars = new Cars($_POST["make"], $_POST["model"], $_POST["year"], $_POST["price"]);

        // Add this new Car object onto the END of the existing list —
        // [] means "add to the list", NOT "replace the whole list"
        $_SESSION["cars"][] = $newCars;
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
    <!-- The form: collects make, model, year, price from the user -->
    <!-- action="index.php" + method="post" sends the data back to this same file via POST -->
    <form action="index.php" method="post">
      Make:  <input type="text" name="make">
      Model:  <input type="text" name="model">
      Year:  <input type="text" name="year">
      Price:  <input type="text" name="price">
    <input type="submit" name="submit">
</form>

<?php
// Loop through EVERY Car object currently stored in $_SESSION["cars"],
// one at a time, and let each one print its own details
// (on first visit this list is empty, so nothing prints yet — that's expected)
foreach ($_SESSION["cars"] as $car) {
    $car->describe();
}
?>

</body>
</html>