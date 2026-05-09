<?php

$conn = mysqli_connect("localhost", "root", "root", "ecommerce", 3306);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>