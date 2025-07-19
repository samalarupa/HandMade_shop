<?php
// config.php
$servername = "localhost"; // Your database server, typically 'localhost'
$username = "root"; // Your database username (default for XAMPP is 'root')
$password = ""; // Your database password (default for XAMPP is an empty string)
$dbname = "handmade_shop"; // Replace with your actual database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
