<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "primeflix_db";

// Connect to database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
