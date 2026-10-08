<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Use POST."
    ]);
    exit();
}

//Capture 
$capture = json_decode(file_get_contents("php://input"), true);

$username = trim($capture["username"] ?? $_POST["username"] ?? "");
$email = trim($capture["email"] ?? $_POST["email"] ?? "");
$password = $capture["password"] ?? $_POST["password"] ?? "";
$confirm_password = $capture["confirm_password"] ?? $_POST["confirm_password"] ?? "";
$role = trim($capture["role"] ?? $_POST["role"] ?? "guest");

//Validate required fields including password confirmation
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "All fields including password confirmation are required"
    ]);
    exit();
}

//Check if passwords match
if ($password !== $confirm_password) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Passwords do not match"
    ]);
    exit();
}

//Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email format"
    ]);
    exit();
}

//Validate role dynamically against a safe whitelist to prevent unauthorized admin creation
$allowed_roles = ["guest", "customer", "staff"];
if (!in_array($role, $allowed_roles)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid role specified. Allowed roles: " . implode(", ", $allowed_roles)
    ]);
    exit();
}

try {
    //Check if email or username already exists
    $check_sql = "SELECT id FROM users WHERE email = ? OR username = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $email, $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        http_response_code(409); // Conflict
        echo json_encode([
            "status" => "error",
            "message" => "Username or email already exists"
        ]);
        mysqli_stmt_close($check_stmt);
        exit();
    }
    mysqli_stmt_close($check_stmt);

    //Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //Insert new user into database
    $insert_sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "ssss", $username, $email, $hashed_password, $role);

    if (!mysqli_stmt_execute($insert_stmt)) {
        throw new Exception("Failed to register user: " . mysqli_stmt_error($insert_stmt));
    }

    $new_user_id = mysqli_insert_id($conn);
    mysqli_stmt_close($insert_stmt);

    //Return success response
    http_response_code(201); // Created
    echo json_encode([
        "status" => "success",
        "message" => "User registered successfully",
        "data" => [
            "user_id" => $new_user_id,
            "username" => $username,
            "email" => $email,
            "role" => $role
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
    exit();
}
?>