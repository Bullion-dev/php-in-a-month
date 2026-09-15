<?php
include "db.php";

if($_SERVER[ "REQUEST_METHOD" ] == "POST"){

$full_name = $_POST["full_name"];
$phone = $_POST["phone"];
$check_in_date = $_POST["check_in_date"];
$check_out_date = $_POST["check_out_date"];
$adults = $_POST["adults"];
$children = $_POST["children"];
$rooms = $_POST["rooms"];

//prepared statements
//placeholder
$stmt = mysqli_prepare($conn, "INSERT INTO bookings(full_name,phone,check_in_date,check_out_date,adults,children,rooms) 
VALUE (?,?,?,?,?,?,?)");

//bind
mysqli_stmt_bind_param($stmt, "ssssiii",$full_name,$phone,$check_in_date,$check_out_date,$adults,$children,$rooms);

//execute
mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

header("Location: index.php");
exit();
}else{
    header("Location: index.php");
}
?>

