<?php
session_start();
include '../database/db_connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to send error response
function sendError($message) {
    error_log("Error in mark_care.php: " . $message);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    sendError('User not logged in');
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Invalid request method');
}

// Get and validate JSON data
$json = file_get_contents('php://input');
if (!$json) {
    sendError('No data received');
}

$data = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    sendError('Invalid JSON data: ' . json_last_error_msg());
}

// Debug: Log the received data
error_log("Received data: " . print_r($data, true));

// Validate required parameters
if (!isset($data['plant_id']) || !isset($data['care_type'])) {
    sendError('Missing required parameters');
}

$plant_id = intval($data['plant_id']);
$care_type = $data['care_type'];
$user_id = $_SESSION['user_id'];

// Define column mappings
$column_mappings = [
    'water' => 'last_watered',
    'mist' => 'last_misted',
    'fertilize' => 'last_fertilized'
];

$frequency_mappings = [
    'water' => 'watering_frequency',
    'mist' => 'misting_frequency',
    'fertilize' => 'fertilizing_frequency'
];

// Debug: Log the parameters
error_log("Plant ID: $plant_id, Care Type: $care_type, User ID: $user_id");

// Validate care type
if (!isset($column_mappings[$care_type])) {
    sendError('Invalid care type');
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Check if the plant belongs to the user
    $query = "SELECT id, watering_frequency, misting_frequency, fertilizing_frequency 
              FROM user_garden_plants 
              WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Error preparing plant query: " . $conn->error);
    }

    $stmt->bind_param("ii", $plant_id, $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Error executing plant query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $plant = $result->fetch_assoc();

    // Debug: Log the query result
    error_log("Plant query result: " . print_r($plant, true));

    if (!$plant) {
        throw new Exception("Plant not found or not owned by user");
    }

    // Update the last care date using the mapped column name
    $column_name = $column_mappings[$care_type];
    $update_query = "UPDATE user_garden_plants SET " . $column_name . " = NOW() WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    if (!$stmt) {
        throw new Exception("Error preparing update query: " . $conn->error);
    }

    $stmt->bind_param("ii", $plant_id, $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Error updating care status: " . $stmt->error);
    }

    // Debug: Log successful update
    error_log("Successfully updated $column_name for plant_id: $plant_id");

    // Mark current reminder as completed
    $complete_query = "UPDATE plant_care_reminders 
                      SET is_completed = TRUE 
                      WHERE user_garden_plant_id = ? 
                      AND reminder_type = ? 
                      AND is_completed = FALSE 
                      ORDER BY reminder_date ASC LIMIT 1";
    $stmt = $conn->prepare($complete_query);
    if (!$stmt) {
        throw new Exception("Error preparing reminder completion query: " . $conn->error);
    }

    $stmt->bind_param("is", $plant_id, $care_type);
    if (!$stmt->execute()) {
        throw new Exception("Error completing reminder: " . $stmt->error);
    }

    // Create new reminder using the mapped frequency column
    $frequency_column = $frequency_mappings[$care_type];
    $frequency = $plant[$frequency_column];
    if (!$frequency) {
        throw new Exception("Invalid frequency for care type: $care_type");
    }

    $reminder_date = date('Y-m-d H:i:s', strtotime("+$frequency days"));
    $insert_query = "INSERT INTO plant_care_reminders (user_garden_plant_id, reminder_type, reminder_date) 
                     VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    if (!$stmt) {
        throw new Exception("Error preparing reminder insertion query: " . $conn->error);
    }

    $stmt->bind_param("iss", $plant_id, $care_type, $reminder_date);
    if (!$stmt->execute()) {
        throw new Exception("Error creating new reminder: " . $stmt->error);
    }

    // Commit transaction
    $conn->commit();
    
    // Debug: Log success
    error_log("Successfully completed all operations for plant_id: $plant_id, care_type: $care_type");
    
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    // Log the error
    error_log("Error in mark_care.php: " . $e->getMessage());
    
    // Send error response
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while updating the care status: ' . $e->getMessage()
    ]);
}

// Close the connection
$conn->close(); 