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

// Capture incoming data (supports JSON or form-data)
$capture = json_decode(file_get_contents("php://input"), true);
$room_id = $capture["id"] ?? $_POST["id"] ?? "";

// Validate that an ID was provided
if (empty($room_id)) {
    http_response_code(400); // Bad request
    echo json_encode([
        "status" => "error",
        "message" => "Room ID is required"
    ]);
    exit();
}

try {
    //Fetch the room first to get the image path (so we can unlink/delete the file)
    $select_sql = "SELECT image_url FROM rooms WHERE id = ?";
    $select_stmt = mysqli_prepare($conn, $select_sql);
    mysqli_stmt_bind_param($select_stmt, "i", $room_id);
    mysqli_stmt_execute($select_stmt);
    $result = mysqli_stmt_get_result($select_stmt);

    if (mysqli_num_rows($result) === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Room not found"
        ]);
        exit();
    }

    $room = mysqli_fetch_assoc($result);
    $image_path = $room["image_url"];
    mysqli_stmt_close($select_stmt);

    //Delete the room record from the database
    $delete_sql = "DELETE FROM rooms WHERE id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $room_id);

    if (!mysqli_stmt_execute($delete_stmt)) {
        throw new Exception("Failed to delete room record: " . mysqli_stmt_error($delete_stmt));
    }

    mysqli_stmt_close($delete_stmt);

    //If the room had an image file stored locally, delete it from the uploads folder
    if (!empty($image_path) && file_exists($image_path)) {
        unlink($image_path);
    }

    //Return success response
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Room deleted successfully",
        "data" => [
            "room_id" => $room_id
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