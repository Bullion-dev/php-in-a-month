<?php
session_start();
include "db.php";

$error = "";
$success = "";

//check if it was submitted with the method POST
if($_SERVER["REQUEST_METHOD"]== "POST"){
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"] ?? "";

    //Check if any of the fields are empty
    if(!empty($username) && !empty($password) && !empty($confirm_password)) {
        if($password !== $confirm_password){
$error = "Passwords do not match";
        }else {
    $hash_password = password_hash($password,PASSWORD_DEFAULT);

    //prepare statements
    $login_stmt = mysqli_prepare($conn, "INSERT INTO users(username, password) VALUES(?,?)");
    
    //preparing for fails
    if($login_stmt){
        mysqli_stmt_bind_param($login_stmt,"ss", $username, $hash_password);
        
        //This checks whether the database successfully ran the insert command.
        if(mysqli_stmt_execute($login_stmt)){
            $success = "Registration successful! You can now log in.";
        }else{
            //mysqli_errno($conn) asks MySQL: "What was the exact error code you just ran into?"
            //If the code is 1062, it's MySQL's specific code for: "Hey, this value already exists in a unique column!"
            if(mysqli_errno($conn)== 1062){
           $error = "That username is already taken. Choose another username";
            }else {
                //any other database error(connection drop,server issue,etc)
                $error = "something went wrong. Please try again.";
            }
        }
        //clean up the statement memory
        mysqli_stmt_close($login_stmt);
    }
    }

    }else{
        $error = "Please fill in all fields";
    }


    
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 50px;">
    <h2>Create an Account</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>


<form action="register.php" method="POST">
        <div style="margin-bottom: 15px;">
            <label>Username:</label><br>
            <input type="text" name="username" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Show Password:</label><br>
            <input type="checkbox" onclick="togglePasswordVisibility()">
        </div>
        <button type="submit">Register</button>
    </form>
    <script>
        function togglePasswordVisibility() {
            var passField = document.getElementById("password");
            var confirmPassField = document.getElementById("confirm_password");
            
            if (passField.type === "password") {
                passField.type = "text";
                confirmPassField.type = "text";
            } else {
                passField.type = "password";
                confirmPassField.type = "password";
            }
        }
    </script>
</body>
</html>
