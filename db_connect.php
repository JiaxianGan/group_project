<?php
$servername = "localhost";
$username = "root";
$password = ""; // Leave empty if using default XAMPP settings
$database = "agrimarketsolutions"; // Ensure this matches your database name
$port = 4306; // Default MySQL port in XAMPP

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
