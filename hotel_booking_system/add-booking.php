<?php
require_once "db.php";

if($_SERVER["REQUEST_METHOD"] !== "POST"){
    http_response_code(405); //invalid method
    echo json_encode([
    "status"=> "error",
    "message"=> "invalid method, use POST"

    ]);
    exit();
}

$capture = json_decode(file_get_contents("php://input"),true);

$full_name = $capture["full_name"] ?? $_POST["full_name"] ?? "";
$email = $capture["email"] ?? $_POST["email"] ?? "";
$phone = $capture["phone"] ?? $_POST["phone"] ?? "";
$room_number = $capture["room_number"] ?? $_POST["room_number"] ?? "";
$room_type = $capture["room_type"] ?? $_POST["room_type"] ?? "";
$check_in_date = $capture["check_in_date"] ?? $_POST["check_in_date"] ?? "";
$check_out_date = $capture["check_out_date"] ?? $_POST["check_out_date"] ?? "";

//if empty
if (empty($full_name) || empty($email) || empty($phone) || empty($room_number) || empty($room_type) || empty($check_in_date) || empty($check_out_date)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required."
    ]);
    exit();
} 
//with this we first check if a customer with that id exists
//if they exist asign the id to a new variable
//if they don't exist now log their details into the customers table
try{

$sql = "SELECT id FROM customers WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt,"s",$email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if($row = mysqli_fetch_assoc($result)){
    $customer_id = $row["id"];

    }else{
$sql = "INSERT INTO customers(full_name,email,phone) VALUES(?,?,?)";
    $new_customer = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($new_customer,"sss",$full_name,$email,$phone);

if(!mysqli_stmt_execute($new_customer)){
 throw new Exception("Failed to create customer record");
}
//then save the newly generated id in a variable
$customer_id = mysqli_insert_id($conn);
mysqli_stmt_close($new_customer);


}
mysqli_stmt_close($stmt);

//room lookup 
// we lookup the room number the customer typed in the rooms table
//if it exists , we bring up the price
//if it doesn't exist throw a 404 error (not found);
$sql = "SELECT id,price_per_night FROM rooms WHERE room_number = ?";
$room_stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($room_stmt,"i",$room_number);
mysqli_stmt_execute($room_stmt);
$result = mysqli_stmt_get_result($room_stmt);

if(!$room_row= mysqli_fetch_assoc($result)){
    http_response_code(404);//not found
    echo json_encode([
 "status" => "error",
 "message" => "Room number not found"
    ]);
    exit();
} else {
    $room_id = $room_row["id"];
    $price_per_night = $room_row["price_per_night"];
    mysqli_stmt_close($room_stmt);
}

//now we deal with the price calculation , this will be the multiplication of the number of nights
//by the room price
//this changes the dates from texts into actual date object so that php can see them as such
$in = new DateTime ($check_in_date);
$out = new DateTime ($check_out_date);
$nights = $in->diff($out)->days;//so $in->diff($out) gives us the gap between the dates(compares them both and spits the gap),
//->days this extracts the total number of days from that gap and stores it inside the variable

//now we validate the stay length
if($nights <= 0){
    http_response_code(400);// does not exist
    echo json_encode([
    "status"=> "error",
    "message"=> "Check-out date must be after check-in date"
    ]);
    exit();
}
$total_price = $nights * $price_per_night;

//now we save everything into the database
$sql = "INSERT INTO bookings(customer_id,room_id,check_in,check_out,total_price,status) VALUES(?,?,?,?,?, 'upcoming')";
$insert_all = mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($insert_all,"iissd",$customer_id, $room_id, $check_in_date, $check_out_date, $total_price);
if(!mysqli_stmt_execute($insert_all)){
    http_response_code(500);//database error
    echo json_encode([
    "status"=> "error",
    "message"=>"database error: Failed to save booking"
    ]);
    exit();
} else{
    http_response_code(201);//201 best practice for newly created items
    echo json_encode([
    "status"=> "success",
    "message"=>"booking created successfully",
    "total_price" => $total_price
    ]);
    exit();
}

} catch(Exception $e){
    http_response_code(500);//dtabase error
    echo json_encode([
    "status" => "error",
    "message"=> "Database error:" . $e->getMessage()
    ]);
}
?>