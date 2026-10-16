<?php
require_once "db.php";
require_once "auth-middleware.php"; // loading this file starts the session

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid method, use POST"]);
    exit();
}

$_SESSION = [];     // clears the saved details
session_destroy();  // tear the page out on the server

echo json_encode([
    "status" => "success", 
    "message" => "Logged out"
    ]);
?>