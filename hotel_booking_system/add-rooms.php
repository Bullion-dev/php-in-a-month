<?php
require_once "db.php";
require_once "auth-middleware.php";
$current_user = authorize_roles(["staff", "admin"]);


if($_SERVER["REQUEST_METHOD"] !=="POST"){
    http_response_code(405); //method not allowed
    echo json_encode([
        "status" => "error",
        "message" => "Invalid method, use POST"
    ]);
    exit();
}

$capture = json_decode(file_get_contents("php://input"),true);

//now capture both ways
$room_number = $capture["room_number"] ?? $_POST["room_number"] ?? "";
$room_type = $capture["room_type"] ?? $_POST["room_type"] ?? "";
$price_per_night = $capture["price_per_night"] ?? $_POST["price_per_night"] ?? "";
// default to null if no image is added
$image_url = null;

//now we check if the inputs are empty
if(empty($room_number) || empty($room_type) || empty($price_per_night)){
    http_response_code(400);//
    echo json_encode([
    "status" => "error",
    "message"=> "All fields are required"
    ]);
    exit();
}
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


//now we can insert into the database
$sql = "INSERT INTO rooms(room_number,room_type,price_per_night,image_url) VALUES(?,?,?,?)";
$stmt = mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt,"ssds",$room_number,$room_type,$price_per_night,$image_url);

if(!mysqli_stmt_execute($stmt)){
    http_response_code(500);
    echo json_encode([
    "status"=> "error",
    "message"=> "Failed to insert room record"
    ]);
} else{
    //grab the id and tell us which room to display
    $room_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    //now show our success message
    http_response_code(201);
    echo json_encode([
    "status"=> "success",
    "message"=> "Room added successfully",
    "data" =>[
        "room_id"=> $room_id,
        "room_number"=> $room_number,
        "room_type"=> $room_type,
        "price_per_night"=> $price_per_night,
        "image_url"=> $image_url
    ]
    ]);
}



 


?>