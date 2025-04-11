<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "plantitos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // For security in production, you might want to log this instead of showing it
    die(json_encode([
        "success" => false, 
        "message" => "Connection failed: " . $conn->connect_error
    ]));
}

// Set charset to ensure proper handling of special characters
$conn->set_charset("utf8mb4");
?>