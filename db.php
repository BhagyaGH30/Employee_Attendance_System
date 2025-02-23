<?php
$host = "localhost";   // Database host (usually localhost)
$username = "root";    // Database username
$password = "";        // Database password (empty if using XAMPP)
$database = "attendance_db"; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
