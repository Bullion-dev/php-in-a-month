<?php
session_start();
include "db.php";



//check if the search button was clicked
if(isset($_GET['search']) && !empty(trim($_GET['search']))){
    $search = "%" . trim($_GET['search']) . "%" ;
// Use a prepared statement for security against SQL injection
// FROM just means starting point, php just needs a starting point
//so it can also be FROM customers
//JOIN bookings ON bookings.custom_id = customers.id;
//WHERE customers.full_name LIKE ?;
    $sql = "SELECT bookings.*, customers.full_name, customers.phone
    FROM bookings

    JOIN customers ON bookings.custom_id = customers.id 
    WHERE customers.full_name LIKE ?";


    $stmt = mysqli_prepare($conn, $sql );

    //bind
    mysqli_stmt_bind_param($stmt,"s", $search);
    //execute
     mysqli_stmt_execute($stmt);
     //save the result in a result variable
     $result = mysqli_stmt_get_result($stmt);

}else{
    // Default query if no search term is entered
    $sql = "SELECT bookings.*, customers.full_name,customers.phone
    FROM bookings
    JOIN customers ON bookings.custom_id = customers.id";

$result = mysqli_query($conn, $sql);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Add Booking</h1>
    <a href="add-booking.php">Add Booking</a><br><br>

<!--display the flash message if it exists -->
<?php 
if(isset($_SESSION['success_message'])):
    //the colon  tells PHP "The condition check is finished, and everything
    // from this point down belongs to this if statement until you see endif;
?>
<div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 15px 0; border: 1px solid #c3e6cb;">
<?php
echo $_SESSION['success_message'];
unset($_SESSION['success_message']); //clear it so it doesn't stay on refresh
?>
</div>
<?php endif; //acts as the closing curly braces ?>

<!--search button-->
<form method="GET" action="index.php" style="margin-bottom: 15px;">
<input type="text" placeholder="Search by Guest name......" name="search" value="<?php echo htmlspecialchars(($_GET['search']) ?? ""); ?>">
<button type="submit">Search</button>
<a href="index.php">Reset</a>
</form>

<!-- mysqli_num_rows() counts how many rows were found -->
<?php if(mysqli_num_rows($result)) : ?>

        <table border="1">
<tr>
    <th>Full Name</th>
    <th>Phone</th>
    <th>Check In Date</th>
    <th>Check Out Date</th>
    <th>Adults</th>
    <th>Children</th>
    <th>Rooms</th>
    <th>Actions</th>



</tr>

<?php
while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row["full_name"]; ?></td>
    <td><?php echo $row["phone"]; ?></td>
    <td><?php echo $row["check_in_date"]; ?></td>
    <td><?php echo $row["check_out_date"]; ?></td>
    <td><?php echo $row["adults"]; ?></td>
    <td><?php echo $row["children"]; ?></td>
    <td><?php echo $row["rooms"]; ?></td>
    <td>
        <a href="edit-booking.php?id=<?php echo $row["id"];?>" >Edit</a>


        <!--this is only now accessible to an admin
        what it's simply doing is, check if the session box exists first
        after that if that is true, then compare what is inside to admin-->
        <?php if(isset($_SESSION["roles"]) && $_SESSION["roles"] === "admin"): ?>
        <a href="delete-booking.php?id=<?php echo $row["id"];?>"
        onclick="return confirm('Are you sure you want to delete this booking?');">
        Delete
    </a>
    <?php endif; ?>
</td>
</tr>
<?php } ?>
</table>
<?php else: ?>
    <p style="color:red;">Doesn't match our records</p>
<?php endif; ?>

<a href="logout.php">
<button >Log Out</button>
</a>
</body>
</html>