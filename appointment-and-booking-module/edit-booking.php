<?php
include "db.php";

if(isset($_GET["id"])){
    $id = $_GET["id"];

    $sql = "SELECT bookings.*, customers.full_name,customers.phone
    FROM bookings
    JOIN customers ON bookings.custom_id = customers.id
    WHERE bookings.id = ?";
    //prepare
    $stmt = mysqli_prepare($conn, $sql );

    //bind
    mysqli_stmt_bind_param($stmt,"i", $id);

    //execute
     mysqli_stmt_execute($stmt);

     //bundle every info into a notebook
     $result = mysqli_stmt_get_result($stmt);

     //now read the info and save it into an associative array
     $guest = mysqli_fetch_assoc($result);

     //close statement
     mysqli_stmt_close($stmt);

     //safeguard
     if(!$guest){
        header("Location: index.php");
     }

}else{
header("Location: index.php");
}
?>

<?php
include "db.php";

if($_SERVER[ "REQUEST_METHOD" ] == "POST"){
 $id = $_POST["id"];
 $custom_id = $_POST["custom_id"];
$full_name = $_POST["full_name"];
$phone = $_POST["phone"];
$check_in_date = $_POST["check_in_date"];
$check_out_date = $_POST["check_out_date"];
$adults = $_POST["adults"];
$children = $_POST["children"];
$rooms = $_POST["rooms"];


$sql_update= "UPDATE customers SET full_name=?, phone=? WHERE id=?";
//prepared statements
//placeholder
$stmt = mysqli_prepare($conn, $sql_update);

//bind
mysqli_stmt_bind_param($stmt, "ssi",$full_name,$phone,$custom_id);
mysqli_stmt_execute($stmt);

 header("Location: index.php");
exit;

}
    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
</head>
<body>
    <h2>Edit Your Booking</h2>
    
    <!-- Points to your PHP processing file -->
    <form method="POST">
        
    <input type="hidden" name="id" value="<?php echo $guest["id"]; ?>">
    <input type="hidden" name="custom_id" value="<?php echo $guest["custom_id"]; ?>">
    
        <label for="full_name">Full Name:</label><br>
        <input type="text" id="full_name" value="<?php echo $guest["full_name"]; ?>" name="full_name"required><br><br>
        
        <label for="phone">Phone Number:</label><br>
        <input type="text" id="phone" name="phone" value="<?php echo $guest["phone"]; ?>" required><br><br>
        
        <label for="check_in_date">Check-in Date:</label><br>
        <input type="date" id="check_in_date" name="check_in_date" value="<?php echo $guest["check_in_date"]; ?>" required><br><br>
        
        <label for="check_out_date">Check-out Date:</label><br>
        <!-- Typo fixed here so it matches PHP -->
        <input type="date" id="check_out_date" name="check_out_date" value="<?php echo $guest["check_out_date"]; ?>" required><br><br>

        <label for="adults">Adults:</label><br>
        <input type="number" id="adults" name="adults" min="1" value="<?php echo $guest["adults"]; ?>" required><br><br>

        <label for="children">Children:</label><br>
        <input type="number" id="children" name="children" min="0" value="<?php echo $guest["children"]; ?>"><br><br>

        <label for="rooms">Rooms:</label><br>
        <input type="number" id="rooms" name="rooms" value="<?php echo $guest["rooms"]; ?>"><br><br>
        
        <input type="submit" value="Update Booking">
    </form>
</body>
</html>