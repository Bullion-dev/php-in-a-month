<?php
require_once "db.php";
require_once "auth-middleware.php";
authenticate_user();

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid method, use GET"]);
    exit();
}

$email = $_SESSION["email"] ?? "";

$sql = "SELECT bookings.id AS bookings_id, rooms.room_number, rooms.room_type,
               bookings.check_in, bookings.check_out, bookings.total_price, bookings.status
        FROM bookings
        JOIN customers ON bookings.customer_id = customers.id
        JOIN rooms ON bookings.room_id = rooms.id
        WHERE customers.email = ?
        ORDER BY bookings.id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$bookings = [];
while ($row = mysqli_fetch_assoc($result)) { $bookings[] = $row; }

echo json_encode(["status" => "success", "data" => $bookings, "count" => count($bookings)]);
?>