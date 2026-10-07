<?php
require_once "db.php";

//check the request method
if($_SERVER["REQUEST_METHOD"] !== "GET"){
   http_response_code(405); //method not allowed
    echo json_encode([
        "status" => "error",
        "message" => "Invalid method, use GET"
    ]);
    exit();
}


//join all the tables in order to display them 
$sql = "SELECT
            bookings.id AS bookings_id,
            customers.full_name,
            customers.email,
            customers.phone,
            rooms.room_number,
            rooms.room_type,
            bookings.check_in,
            bookings.check_out,
            bookings.total_price,
            bookings.status
        FROM bookings
        JOIN customers ON bookings.customer_id = customers.id
        JOIN rooms ON bookings.room_id = rooms.id
        ORDER BY bookings.id DESC";

//this sends a query to the database using the connection 
$result = mysqli_query($conn,$sql);

if($result){
    //create an empty array
    // we need this so we can store all the booking rows one by one
    $bookings = [];

    //loop through the database rows
    //evrytime the loop runs it moves forward to the next row
    //$bookings[] = $row;: This takes each extracted row and 
    // pushes it into our $bookings array container. 
    // By the time the loop finishes, $bookings holds a full
    //  list of every reservation.
    while($row = mysqli_fetch_assoc($result)){
    $bookings[] = $row;

    }

    // return a successful ok message with the list of bookings
    http_response_code(200);
    echo json_encode([
    "status" => "success",
    "data" => $bookings,
    "count" => count($bookings)
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error: Failed to fetch bookings"
    ]);
    exit();
}

?>