<?php
require_once "db.php";

if($_SERVER["REQUEST_METHOD"] !=="POST"){
    http_response_code(405); //method not allowed
    echo json_encode([
        "status" => "error",
        "message" => "Invalid method, use POST"
    ]);
    exit();
}

$capture = json_decode(file_get_contents("php://input"),true);

//now capture both ways
$room_number = $capture["room_number"] ?? $_POST["room_number"] ?? "";
$room_type = $capture["room_type"] ?? $_POST["room_type"] ?? "";
$price_per_night = $capture["price_per_night"] ?? $_POST["price_per_night"] ?? "";
$img_url = "null";

//now we check if the inputs are empty
if(empty($room_number) || empty($room_type) || empty($price_per_night)){
    http_response_code(400);//
    echo json_encode([
    "status" => "error",
    "message"=> "All fields are required"
    ]);
    exit();
}
 


?>