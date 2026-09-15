<?php
include "db.php";

//query the database
$result = mysqli_query($conn, "SELECT * FROM bookings");
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
    <a href="add-booking.php">Add Booking</a>
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
        <a href="delete-booking.php?id=<?php echo $row["id"];?>"
        onclick="return confirm('Are you sure you want to delete this booking?');">
        Delete
    </a>
</td>
</tr>
<?php } ?>
</table>
</body>
</html>