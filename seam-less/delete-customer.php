<?php

if(isset($_GET["id"])){
$id = $_GET["id"];

include "db.php";


//prepare statement
$stmt = mysqli_prepare($conn, "DELETE FROM customers WHERE id=?");
//bind
mysqli_stmt_bind_param($stmt, "i", $id);

//execute
mysqli_stmt_execute($stmt);
//close statement
mysqli_stmt_close($stmt);


}
    header("Location: index.php");
    exit();

?>