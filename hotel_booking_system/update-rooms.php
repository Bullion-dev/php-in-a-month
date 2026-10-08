<?php
require_once "db.php";

if($_SERVER["REQUEST_METHOD"] !== "POST"){
    http_response_code(405);
    echo json_encode([
        "status"=>"error",
        "message"=> "Invalid request method. Use POST."
    ]);
    exit();
}

//capture
$capture = json_decode(file_get_contents("php://input"),true);

$room_number = $capture["room_number"] ?? $_POST["room_number"] ?? "";
$room_type = $capture["room_type"] ?? $_POST["room_type"] ?? "";
$price_per_night = $capture["price_per_night"] ?? $_POST["price_per_night"] ?? "";
$room_id = $capture["id"] ?? $_POST["id"] ?? "";

//validate
if(empty($room_number) || empty($room_type) || empty($price_per_night) || empty($room_id)){
    http_response_code(400); //bad request
    echo json_encode([
    "status"=> "error",
    "message"=> "All fields are required"
    ]);
    exit();

}

//now we need to make it so that when an image was updated
$new_image_url= [];//
$image_url = null;

//now we check if an image was attached and if there were no errors
//$_FILES is simply php's dedicated store box that is automatically created behind the scenes
//whenever a a client sends a file upload to the server via html form or postman
//so this line is basically saying first check if an image file was uploaded in the container
//AND also check if the error message matches UPLOAD_ERR_OK
if(isset($_FILES["image_url"]) && $_FILES["image_url"]["error"] === UPLOAD_ERR_OK){ //
//now we set up a destination folder
$upload_dir = "uploads/";
//and we check if that folder already exists, if it doesn't mkdir($upload_dir, 077, tue
//automatically creates one

if(!is_dir($upload_dir)){
 mkdir($upload_dir,0777,true);
}

//now we craft a unique and safe filename
//we peak into the original filename and then use pathinfo() to slice off just the extension
$file_extension = pathinfo($_FILES["image_url"]["name"], PATHINFO_EXTENSION);
$new_filename = "room_" . time() . uniqid() . "." . $file_extension; //this then creates a new name
$target_file = $upload_dir . $new_filename; //this adds the new destination folder to the new filename ensuring that no names overlap

//php normally stores the image inside a temporal folder just for security reasons
//so here we're moving it from the temporal location to the permanent folder we created
if(move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)){
//now we assign our naming convention to a new variable
$image_url = $target_file;

} else{
    http_response_code(500);
    echo json_encode([
  "status"=>"error",
  "message"=> "Failed to upload image"
    ]);
    exit();
}
}//

try {
//was an image update included in the room update
if ($image_url !== null) {
        $sql = "UPDATE rooms SET room_number = ?, room_type = ?, price_per_night = ?, image_url = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdsi", $room_number, $room_type, $price_per_night, $image_url, $room_id);
    } 
    // Else update text fields only, leaving the existing image alone
    else {
        $sql = "UPDATE rooms SET room_number = ?, room_type = ?, price_per_night = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdi", $room_number, $room_type, $price_per_night, $room_id);
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Failed to update room record: " . mysqli_stmt_error($stmt));
    }

    // Check if any row was actually modified or found
    if (mysqli_stmt_affected_rows($stmt) === 0) {
        // Could mean ID doesn't exist or data sent was identical
        // We can still return success or check if room exists
    }

    mysqli_stmt_close($stmt);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Room updated successfully",
        "data" => [
            "room_id" => $room_id,
            "room_number" => $room_number,
            "room_type" => $room_type,
            "price_per_night" => $price_per_night,
            "image_url" => $image_url
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