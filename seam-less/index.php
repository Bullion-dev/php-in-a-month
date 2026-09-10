<?php
include "db.php";

//query the database to get all customers
$result = mysqli_query($conn, "SELECT * FROM customers");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
</head>
<body>
    <h1>CUSTOMERS</h1><br>
    <a href="add-customer.php">Add Customer</a><br><br>

  <table border="1">
    <tr>
        <th>Name</th>
      <th>Phone</th>
      <th>Location</th>
      <th>Notes</th>
      <th>Actions</th>
</tr>


<?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <tr>
        <td><?php echo $row["full_name"]; ?></td>
        <td><?php echo $row["phone"]; ?></td>
        <td><?php echo $row["location"]; ?></td>
        <td><?php echo $row["notes"]; ?></td>
        <td>
            <a href="edit-customer.php?id=<?php echo $row["id"]; ?>">Edit</a>
    <a href="delete-customer.php?id=<?php echo $row["id"]; ?>" 
               onclick="return confirm('Are you sure you want to delete this customer?');">
               Delete
            </a>
    </td>
        
</tr>

<?php } ?>


</table>
</body>
</html>