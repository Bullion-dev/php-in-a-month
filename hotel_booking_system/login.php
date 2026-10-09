<?php

require_once "db.php";

//check if necessary request method was used

if($_SERVER["REQUEST_METHOD"] !== "POST"){
http_response_code(405); //405 Method not allowed
echo json_encode([
"status" => "error",
"message" => "Method not allowed. use POST"
]);
exit();
}

//capture the login credentials no mattter how they were sent
//GET THE INCOMING JSON PAYLOAD
$capture =json_decode(file_get_contents("php://input"), true) ;

//fallback if the data is sent via standard form-data instead of json
$email = $capture["email"] ?? $_POST["email"] ?? "";
$password = $capture["password"] ?? $_POST["password"] ?? "";

//validate the fields are not empty
if(empty($email) || empty($password)){
http_response_code (400); //bad request
echo json_encode([
    "status" => "error",
    "message" => "Email and Password are required"
]);
exit();
}

// securely query the db using prepared stmts
$sql = "SELECT id, username, role, password, email FROM users WHERE email = ? ";
$stmt = mysqli_prepare($conn, $sql);
//bind
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

//check if the user exists
if($row = mysqli_fetch_assoc($result)){
//verify password against the hashed password in the database
if (password_verify($password, $row["password"])){
    //so this asks has a session already been started during this request?
    //PHP_SESSION_NONE, means no, if none is running then start one
    //if one is already running do nothing
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    //so this line of code gives the user a fresh session id
    //the other lines stores those information on the server to be used accross the pages
    session_regenerate_id(true); 
    $_SESSION["user_id"]  = $row["id"];
    $_SESSION["username"] = $row["username"];
    $_SESSION["email"]    = $row["email"];
    $_SESSION["role"]     = $row["role"];


    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message"=> "Login Succcessful",
        "id" => $row["id"],
        "name" => $row["username"],
        "email" => $row["email"],
        "role" => $row["role"],
    ]);
}else{
    //incorrect password
    http_response_code(401); //unauthorized
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
}

}else{
    //email not found in the database
    http_response_code(401); //unauthorized
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
}



?>