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

$sql = "SELECT
            rooms.id AS room_id,
            rooms.room_number,
            rooms.room_type,
            rooms.price_per_night
        FROM rooms
        ORDER BY rooms.id DESC";


$result = mysqli_query($conn,$sql);

if($result){
    $rooms = [];

    while($row = mysqli_fetch_assoc($result)){
        $rooms[] = $row;
    }
    //success message
    http_response_code(200);
    echo json_encode([
    "status"=> "success",
    "data" => $rooms,
    "count" => count($rooms)
    ]);
} else{
    http_response_code(500);
    echo json_encode([
    "status"=> "error",
    "message" => "Database error: Failed to fetch rooms"
    ]);
    exit();
}


?>