<?php 
// Start (or resume) the session — must be the very first thing in the file,
// before any HTML or output, so PHP can track this user across page loads
session_start();

// session is a super global variable that stores information
// about a user to be used across multiple pages
// a user is assigned a session id

// Check: does our "cars" list already exist in this session?
// This only matters on the very first visit — after that, it already exists
// and this block gets skipped, leaving the existing list untouched
if(!isset($_SESSION["cars"])){
    // First time ever visiting — create an empty list to start collecting cars into
    $_SESSION["cars"] = array();
}

// Check: did the person fill in all four fields with actual values (not blank)?
// This runs on every page load, but only passes if a real submission happened
if (!empty($_POST["make"]) &&
    !empty($_POST["model"]) &&
    !empty($_POST["year"]) &&
    !empty($_POST["price"])) {
    // If we reach here, all four fields are filled in with real values, safe to proceed

    // Double-check: was the Submit button specifically clicked?
    // (This mostly overlaps with the check above, but confirms this was a real submission)
    if(isset($_POST["submit"])){

        // Glue the four submitted values into one readable sentence
        // Example: "Toyota" . " " . "Corolla" . " " . "2012" . " - $" . "18000"
        //        = "Toyota Corolla 2012 - $18000"
        $newCars = $_POST["make"] . " " . $_POST["model"] . " " . $_POST["year"] . " - $" . $_POST["price"];

        // Add this new car sentence onto the END of the existing list —
        // the [] means "add to the list", NOT "replace the whole list"
        // keep everything that was already in this list, and add one more thing onto the end
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
<!-- action="index.php" means: submit this data back to this same file -->
<form action="index.php" method="post">
    Make: <input type="text" name="make"><br>
    Model: <input type="text" name="model"><br>
    Year: <input type="number" name="year"><br>
    Price: <input type="number" name="price"><br>
    <input type="submit" name="submit">
</form>

<?php
// Loop through EVERY car currently stored in $_SESSION["cars"],
// one at a time, and print each one on its own line
// (on first visit this list is empty, so nothing prints yet — that's expected)
foreach($_SESSION["cars"] as $car) {
    echo $car . "<br>";
}
?>

</body>
</html>