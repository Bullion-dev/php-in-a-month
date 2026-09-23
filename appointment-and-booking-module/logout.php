<?php
//hey go look for the box that stores the info
session_start();

//store it into $_SESSION = array(); and replace it with an empty array 
$_SESSION = array();


// 3. Delete the session cookie from the browser (extra safety step)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}


//The file is named after a unique session ID cookie stored in your browser (for example: sess_9f8e7d6c5b4a3...).
//so this clears it;
session_destroy();

//now redirect to the login page
header("Location: login.php");
//now stop any other code from running
exit();

?>