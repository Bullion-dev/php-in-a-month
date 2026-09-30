<?php
header("Access-Control-Allow-Oriigin: *");
header("Content-Type: application/json; charset=UTFF-8");
header("Access-Control-Allow-Methods:GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-All-Headers, Authorization, X-Requested-with");

$host = "localhost";
$user = "root";
$password = "";
$dbname = "hotel_booking_system";

$conn = mysqli_connect($host, $user, $password, $dbname);

if(!$conn){
    //this sets up the http status code response to 500 internal server error
    http_response_code(500);
    //this converts php array into a json string and prints it out to 
    //whoever called the API;
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed:"
. mysqli_connect_error()
    ]);

    exit();

}


?>