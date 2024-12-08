<?php
// Database connection settings
$host = 'localhost';
$dbname = 'bridalconnect';
$username = 'root';
$password = '';

// Establish connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Optional: Set character set to UTF-8
mysqli_set_charset($conn, 'utf8mb4');
?>
