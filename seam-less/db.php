<?php
$conn = mysqli_connect("localhost", "root", "", "seam_less");

if(!$conn){
    die("connection failed: ". mysqli_connect_error());
}


?>