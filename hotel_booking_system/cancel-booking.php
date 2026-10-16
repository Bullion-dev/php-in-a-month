<?php
require_once "db.php";
require_once "auth-middleware.php";
authenticate_user(); // must be logged in

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid method, use POST"]);
    exit();
}

$capture = json_decode(file_get_contents("php://input"), true);
$booking_id = $capture["id"] ?? "";
$email = $_SESSION["email"] ?? "";

if (empty($booking_id)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Booking ID is required"]);
    exit();
}

$sql = "UPDATE bookings
        JOIN customers ON bookings.customer_id = customers.id
        SET bookings.status = 'cancelled'
        WHERE bookings.id = ? AND customers.email = ? AND bookings.status = 'upcoming'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $booking_id, $email);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) === 0) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Booking not found, not yours, or already cancelled"]);
    exit();
}

echo json_encode(["status" => "success", "message" => "Booking cancelled"]);
?>