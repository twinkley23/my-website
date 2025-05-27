<?php
$servername = "localhost";
$username = "root";         // default XAMPP username
$password = "";             // leave blank in XAMPP
$database = "readmore_db";  // the new DB you just created

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
