<?php
session_start();
include '../database/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$plant_id = $data['plant_id'];

// Get user ID
$user_id = $_SESSION['user_id'];

// Check if the plant belongs to the user
$query = "SELECT id FROM user_garden_plants WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $plant_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Plant not found or not owned by user']);
    exit;
}

// Delete the plant from the user's garden
// The reminders will be automatically deleted due to the ON DELETE CASCADE constraint
$query = "DELETE FROM user_garden_plants WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $plant_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error removing plant']);
} 