<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods:GET, POST, PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-with");


//before a request that carries a login token, the browser first sends a “may I?” check called OPTIONS
// Answer the browser's "may I?" check and stop here
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit();
}

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