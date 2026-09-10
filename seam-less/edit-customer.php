<?php
include "db.php";

//check if id exists in the url
if(isset($_GET["id"])){
    $id = $_GET["id"];

//prepare bind execute
$stmt = mysqli_prepare($conn, "SELECT * FROM customers WHERE id = ?");

//bind
mysqli_stmt_bind_param($stmt, "i", $id);
//execute
mysqli_stmt_execute($stmt);

//bundles the raw package into an envelope called the result
//which can't be accessed  just yet
$result = mysqli_stmt_get_result($stmt);

//opens the package and converts it into a readable php associative array you can print inside your form
$customer = mysqli_fetch_assoc($result);

//close statement 
mysqli_stmt_close($stmt);

//safeguard if id doesn't exist, move page to index.php
if(!$customer){
    header("Location: index.php");
}

}else{
    // Safeguard: If someone opens edit-customer.php without ?id= in URL
    header("Location: index.php");
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
</head>
<body>
    <h2>Edit Customer</h2>

    <form action="update-customer.php" method="POST">
<!--hidden input that way you can't edit the id-->
<input type= "hidden" name="id" value="<?php echo $customer["id"];?>">

<label>Full Name:</label><br>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($customer['full_name']); ?>" required><br><br>

        <label>Phone Number:</label><br>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required><br><br>

        <label>Location:</label><br>
        <input type="text" name="location" value="<?php echo htmlspecialchars($customer['location']); ?>" required><br><br>

        <label>Notes:</label><br>
        <textarea name="notes"><?php echo htmlspecialchars($customer['notes']); ?></textarea><br><br>

        <input type="submit" value="Update Customer">
        <a href="index.php">Cancel</a>

</form>
</body>
</html>

