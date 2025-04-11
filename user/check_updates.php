<?php
session_start();
include '../database/db_connect.php';

// Set headers to prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: application/json");

// Debug information
error_log("check_updates.php called at " . date('Y-m-d H:i:s'));

// Get the last update timestamp from the database
$query = "SELECT MAX(updated_at) as last_update FROM plants";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastUpdate = $row['last_update'];
    error_log("Last update from database: " . $lastUpdate);
} else {
    // If no updates found, use current timestamp
    $lastUpdate = date('Y-m-d H:i:s');
    error_log("No updates found in database, using current timestamp: " . $lastUpdate);
}

// Format the timestamp to ensure consistent format
$formattedTimestamp = date('Y-m-d H:i:s', strtotime($lastUpdate));

// Return the last update timestamp
$response = ['last_update' => $formattedTimestamp];
error_log("Sending response: " . json_encode($response));
echo json_encode($response);
?> 