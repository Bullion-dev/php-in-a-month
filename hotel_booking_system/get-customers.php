<?php
require_once "db.php";
require_once "auth-middleware.php";
authorize_roles(["admin", "staff"]);

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid method, use GET"]);
    exit();
}

$sql = "SELECT id AS customer_id, full_name, email, phone
        FROM customers
        ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    $customers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $customers,
        "count" => count($customers)
    ]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: Failed to fetch customers"]);
}
?>