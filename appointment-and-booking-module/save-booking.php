<?php

session_start(); //start session at the very top
include "db.php";

if($_SERVER[ "REQUEST_METHOD" ] == "POST"){

$full_name = $_POST["full_name"];
$phone = $_POST["phone"];
$check_in_date = $_POST["check_in_date"];
$check_out_date = $_POST["check_out_date"];
$adults = $_POST["adults"];
$children = $_POST["children"];
$rooms = $_POST["rooms"];


//check if the id of that name already exists in the database using prepared statements
$check_stmt = mysqli_prepare($conn, "SELECT id FROM customers WHERE full_name=? AND phone=?");
//bind
mysqli_stmt_bind_param($check_stmt, "ss", $full_name, $phone);

//execute
mysqli_stmt_execute($check_stmt);
//store the information gottent after it was sent to the database into this variable
$result= mysqli_stmt_get_result($check_stmt);


//now we need to check if id exists in our search
if($row = mysqli_fetch_assoc($result)){
    //if it exists grab the id and store the id inside 
    $custom_id = $row["id"];
}else{
    //insert it as new
    //prepared statements
    $sql = "INSERT INTO customers(full_name,phone) VALUES(?,?)";
    $new_booking = mysqli_prepare($conn, $sql);
    //bind
    mysqli_stmt_bind_param($new_booking, "ss", $full_name,$phone);
    //execute
     mysqli_stmt_execute($new_booking);

     //Grab the ID that was just generated for this new customer
     $custom_id = mysqli_insert_id($conn);
}

//now insert the booking into the bookings table using the custom_id foreign key
//with prepared statement to prevent SQL injection
$sql="INSERT INTO bookings(custom_id,check_in_date,check_out_date,adults,children,rooms) VALUES(?,?,?,?,?,?)";
$insert_new_stmt = mysqli_prepare($conn, $sql);
//bind
mysqli_stmt_bind_param($insert_new_stmt, "issiii",$custom_id,$check_in_date,$check_out_date,$adults,$children,$rooms);

//execute
mysqli_stmt_execute($insert_new_stmt);
//close
mysqli_stmt_close($insert_new_stmt);


//session message
$_SESSION['success_message'] = "Booking successfully created";

header("Location: index.php");
exit();
}else{
    header("Location: index.php");
}
?>

