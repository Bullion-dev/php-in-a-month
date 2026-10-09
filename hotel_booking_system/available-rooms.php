<?php
require_once "db.php";

if($_SERVER["REQUEST_METHOD"] !=="GET"){
    http_response_code(405); //method not allowed
    echo json_encode([
        "status" => "error",
        "message" => "Invalid method, use GET"
    ]);
    exit();
}

if (!isset($_GET["check_in"]) || !isset($_GET["check_out"])) {
    http_response_code(400); //bad request
    echo json_encode([
        "status" => "error",
        "message" => "Please provide both 'check_in' and 'check_out' query parameters..."
    ]);
    exit();
}

$check_in  = $_GET["check_in"];
$check_out = $_GET["check_out"];

if ($check_out <= $check_in) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Check-out date must be after check-in date"]);
    exit();
}

//the subquery checks for any overlaps
//simply saying cncelled bookings are not required
//check_in < ? means the existing booking starts before the new stay ends.
//check_out < ? means the existing booking ends after the new stay starts.
$sql = "SELECT rooms.id AS room_id, rooms.room_number, rooms.room_type, rooms.price_per_night,rooms.image_url
        FROM rooms
        WHERE rooms.id NOT IN (
            SELECT bookings.room_id FROM bookings
            WHERE bookings.status != 'cancelled'
              AND bookings.check_in < ?
              AND bookings.check_out > ?
        )
        ORDER BY rooms.room_number ASC";


$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $check_out, $check_in);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if($result){
    //create an empty container to store incoming data
    $available_rooms = [];

    //loop through and display the results row by row until there's no more available result
 while($row = mysqli_fetch_assoc($result)){
    $available_rooms[] = $row;
 }

 //success message
 http_response_code(200);
    echo json_encode([
    "status"=> "success",
    "requested_dates"=>[
    "check_in" => $check_in,
    "check_out" => $check_out
    ],
    "count"=> count($available_rooms),
    "data"=> $available_rooms
    ]);

}else{
    http_response_code(500);
    echo json_encode([
    "status"=> "error",
    "message"=> "Database error: Failed to check available rooms"
    ]);
}


?>