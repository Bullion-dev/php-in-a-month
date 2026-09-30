<?php

//link db
require_once "db.php";
//check if necessary request method was used
if($_SERVER["REQUEST_METHOD"] !== "POST"){
http_response_code(405); //405 Method not allowed
json_encode([
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
json_encode([
    "status" => "error",
    "message" => "Email and Password are required"
]);
exit();
}

// securely query the db using prepared stmts
$sql = "SELECT id, name, role, password, email FROM users WHERE email = ? ";
$stmt = mysqli_prepare($conn, $sql);
//bind
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

//check if the user exists
if($row = mysqli_fetch_assoc($result)){
//verify password against the hashed password in the database
if (password_verify($password, $row["password"])){
    http_response_code(200);
    json_encode([
        "status" => "success",
        "message"=> "Login Succcessful",
        "id" => $row["id"],
        "name" => $row["name"],
        "email" => $row["email"],
        "role" => $row["role"],
    ]);
}else{
    //incorrect password
    http_response_code(401); //unauthorized
    json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
}

}else{
    //email not found in db 
    http_response_code(401); //unauthorized
    json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
}



?>