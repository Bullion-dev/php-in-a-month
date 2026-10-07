<?php
 require_once "db.php";

//only trigger an error when it's not a GET request
 if($_SERVER["REQUEST_METHOD"] !== "GET"){
http_response_code(405);
echo json_encode([
"status" => "error",
"message"=> "Invalid method, use GET"

]);
exit();

 }

 try{
    //get the total rooms
    //count all rows in the rooms table and label the result as total_rooms
       $roomQuery = "SELECT COUNT(*) AS total_rooms FROM rooms";
    $roomResult = mysqli_query($conn, $roomQuery);
    $roomData = mysqli_fetch_assoc($roomResult);
    $totalRooms = $roomData['total_rooms'];
    

    //get the total customers
    $customerQuery = "SELECT COUNT(*) AS total_customers FROM customers";
    $customerResult = mysqli_query($conn,$customerQuery);
    $customerData = mysqli_fetch_assoc($customerResult);
    $totalCustomers = $customerData["total_customers"];


    //get the total bookings
    $bookingsQuery = "SELECT COUNT(*) AS total_bookings FROM bookings";
    $bookingsResult = mysqli_query($conn,$bookingsQuery);
    $bookingsData = mysqli_fetch_assoc($bookingsResult);
    $totalBookings = $bookingsData["total_bookings"];



    //retrurn a success message if we're able to grab all the calculations
    http_response_code(200);
echo json_encode([
"status" => "success",
"message"=> "total successful",
"data" => [
    "total_rooms" => (int)$totalRooms,
    "total_customers" => (int)$totalCustomers,
    "total_bookings" => (int)$totalBookings
]

]);

 } 
 //Specifically, Exception $e acts as a safety net for unforeseen runtime errors that happen 
 // after your connection is open. For example:

//example a Typo in a Table Name, If you accidentally typed FROM room instead of FROM rooms, 
// MySQL will throw an error. Without a try and catch, the API would crash with a fatal error.
// With the safety net, PHP catches it, stops the crash, and lets you gracefully return a clean 
// JSON error (500 Internal Server Error) with the message ($e->getMessage()).
 catch(Exception $e){
http_response_code(500); //internal server error
echo json_encode([
    "status" => "error",
    "message" => "Failed to fetch the dashboard metrics: " . $e->getMessage() //this is saying enter into the exception net and get this message
]);
 }
?>