<?php
session_start();

include "db.php";

//If you try to check a variable that hasn't been created yet
//  without using isset(), PHP will throw a warning or error 
// ("Notice: Undefined variable"). isset() safely checks for 
// it in the background without causing your page to crash.
if(isset($_SESSION["user_id"])){
    header("Location: index.php");
    exit();
}
$error_message = "";

//check if the url wasn't just pasted here
//works only when the user submits the form
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if(empty($username) || empty($password)){
        $error_message = " Your credentials don't match ";
    } else{

    //now we get to look inside the db to see if the user exists
    //prepared statements
    $sql =  "SELECT id, username,password FROM users WHERE username = ?";
    $login_stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($login_stmt, "s", $username);
    mysqli_stmt_execute( $login_stmt);
    //store the found results into a variable
    $result = mysqli_stmt_get_result($login_stmt);

    //password verification
    if($result_stored = mysqli_fetch_ass($result)){
        if(password_verify($password, $result_stored["password"])){
            $_SESSION["user_id"] = $result_stored["id"] ;
            $_SESSION["username"] = $result_stored["username"] ;

            header("Location: index.php");
            exit();
        }else{
        $error_message = "Invalid Username and Password";
    }


    }else{
        $error_message = "Invalid Username and Password";
    }





    }
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 50px;">
    <h2>Log In</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>


<form action="index.php" method="POST">
        <div style="margin-bottom: 15px;">
            <label>Username:</label><br>
            <input type="text" name="username" required>
        </div>
        <div style="margin-bottom: 15px;">
            <label>Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Log In</button>
    </form>
</body>
</html>
