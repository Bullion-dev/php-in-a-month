<?php


if(isset($_GET["id"])){
    $id=$_GET["id"];

    include "db.php";

    //prepared
    $stmt = mysqli_prepare($conn, "DELETE FROM bookings WHERE id=? ");

    //bind
    mysqli_stmt_bind_param($stmt, "i", $id);
    //execute
    mysqli_stmt_execute($stmt);
    //close
    mysqli_stmt_close($stmt);

}
    header("Location: index.php");
    exit();

?>