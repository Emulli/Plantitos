<?php
session_start();
include '../database/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'garden_plant' => null
];

try {
    // Check if plant_id is provided
    if (!isset($_GET['plant_id']) || empty($_GET['plant_id'])) {
        throw new Exception("Plant ID is required");
    }
    
    $plant_id = intval($_GET['plant_id']);
    $user_id = $_SESSION['user_id'];
    
    // Prepare and execute query to get the user's garden plant
    $stmt = $conn->prepare("SELECT * FROM user_garden_plants WHERE plant_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $plant_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Plant not found in your garden");
    }
    
    // Fetch garden plant data
    $garden_plant = $result->fetch_assoc();
    
    // Set response
    $response['success'] = true;
    $response['garden_plant'] = $garden_plant;
    
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

// Close connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?> 