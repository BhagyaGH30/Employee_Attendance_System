<?php
// db.php - Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "attendance_db"; // Change this if needed

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
