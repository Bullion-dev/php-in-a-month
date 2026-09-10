<?php
//Verifies that the user arrived at this file 
// by submitting an HTML form with method="POST".
if ($_SERVER["REQUEST_METHOD"] == "POST") {

include "db.php";

$id        = $_POST["id"];
$full_name = $_POST["full_name"];
$phone     = $_POST["phone"];
$location  = $_POST["location"];
$notes     = $_POST["notes"];

//prepare
$stmt = mysqli_prepare($conn, "UPDATE customers SET full_name=?, phone=?, location=?, notes=? WHERE id=?");

//bind
mysqli_stmt_bind_param($stmt, "ssssi", $full_name, $phone, $location, $notes,$id );

//execute statement 
mysqli_stmt_execute($stmt);
//close statement
mysqli_stmt_close($stmt);

header("Location: index.php");
exit();
}else{
    header("Location: index.php");
}

?>