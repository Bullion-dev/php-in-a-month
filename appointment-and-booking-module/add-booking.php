<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Booking</title>
</head>
<body>
    <h2>Add a New Booking</h2>
    
    <!-- Points to your PHP processing file -->
    <form action="save-booking.php" method="POST">
        
        <label for="full_name">Full Name:</label><br>
        <input type="text" id="full_name" name="full_name" placeholder="Enter full name" required><br><br>
        
        <label for="phone">Phone Number:</label><br>
        <input type="text" id="phone" name="phone" placeholder="Enter phone number" required><br><br>
        
        <label for="check_in_date">Check-in Date:</label><br>
        <input type="date" id="check_in_date" name="check_in_date" required><br><br>
        
        <label for="check_out_date">Check-out Date:</label><br>
        <!-- Typo fixed here so it matches PHP -->
        <input type="date" id="check_out_date" name="check_out_date" required><br><br>

        <label for="adults">Adults:</label><br>
        <input type="number" id="adults" name="adults" min="1" value="1" required><br><br>

        <label for="children">Children:</label><br>
        <input type="number" id="children" name="children" min="0" value="0"><br><br>

        <label for="rooms">Rooms:</label><br>
        <input type="number" id="rooms" name="rooms" min="1" value="1" required><br><br>
        
        <input type="submit" value="Add Booking">
    </form>
</body>
</html>