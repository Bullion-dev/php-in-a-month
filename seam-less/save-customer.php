<!--this file receives the form data and saves it to the database:-->
    <?php
    //"Check the security notebook ($_SERVER). Look under 'How did they arrive?' 
    // (REQUEST_METHOD). Did they arrive carrying a sealed package (POST)?"

//If YES: Run the code inside { ... } to open the package and save the customer to the database.

//If NO (e.g., someone just typed save-customer.php in their browser): 
//Ignore the code so it doesn't crash or save empty data.

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
include "db.php";

// Get the data from the form
// get the ino from the input on the form
$full_name = $_POST["full_name"];
$phone = $_POST["phone"];
$location = $_POST["location"];
$notes = $_POST["notes"];

//save to database
//sqlis the langueage the database understands
// add a new row to the customers table

//Prepare SQL template with ? placeholders
$stmt = mysqli_prepare ($conn, "INSERT INTO customers (full_name, phone, location, notes)
/*  these are the values to insert */
        VALUES (?,?,?,?)");

//Bind variables to placeholders ("ssss" = 4 string parameters)
mysqli_stmt_bind_param($stmt, "ssss", $full_name, $phone, $location, $notes);
//execute statement
mysqli_stmt_execute($stmt);

//Close statement and redirect back to customer list 
mysqli_stmt_close($stmt);

   

        //Go back to customers list
        //After saving, redirect the user back to the customers list 
        //like navigate('/customers') in React.
        header("Location: index.php");
        exit();

    }else {
        header("Location: index.php");
    }

    ?>