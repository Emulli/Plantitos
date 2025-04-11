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

// Get user ID
$user_id = $_SESSION['user_id'];

// Get form data
$plant_id = $_POST['plant_id'];
$watering_frequency = $_POST['watering_frequency'];
$misting_frequency = $_POST['misting_frequency'];
$fertilizing_frequency = $_POST['fertilizing_frequency'];
$notes = $_POST['notes'];

// Get plant details from the plants table
$query = "SELECT name, image FROM plants WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $plant_id);
$stmt->execute();
$result = $stmt->get_result();
$plant = $result->fetch_assoc();

if (!$plant) {
    echo json_encode(['success' => false, 'message' => 'Plant not found']);
    exit;
}

// Ensure the image path is correct
$plant_image = $plant['image'];
if (!empty($plant_image)) {
    // Check possible image paths
    $possiblePaths = [
        'uploads/' . $plant_image,
        'admin/uploads/' . $plant_image,
        '../uploads/' . $plant_image
    ];
    
    $imagePath = '';
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            $imagePath = $path;
            break;
        }
    }
    
    // If no path exists, use the default path
    if (empty($imagePath)) {
        $imagePath = 'uploads/' . $plant_image;
    }
    
    $plant_image = $imagePath;
} else {
    // If no image is available, set a default image
    $plant_image = "img/plants/default-plant.jpg";
}

// Insert plant into user's garden
$query = "INSERT INTO user_garden_plants (user_id, plant_id, plant_name, plant_image, watering_frequency, misting_frequency, fertilizing_frequency, notes) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iissiiis", $user_id, $plant_id, $plant['name'], $plant_image, $watering_frequency, $misting_frequency, $fertilizing_frequency, $notes);

if ($stmt->execute()) {
    // Create reminders for the plant
    $garden_plant_id = $stmt->insert_id;
    
    // Watering reminder
    $reminder_date = date('Y-m-d H:i:s', strtotime("+$watering_frequency days"));
    $query = "INSERT INTO plant_care_reminders (user_garden_plant_id, reminder_type, reminder_date) VALUES (?, 'water', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $garden_plant_id, $reminder_date);
    $stmt->execute();
    
    // Misting reminder
    $reminder_date = date('Y-m-d H:i:s', strtotime("+$misting_frequency days"));
    $query = "INSERT INTO plant_care_reminders (user_garden_plant_id, reminder_type, reminder_date) VALUES (?, 'mist', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $garden_plant_id, $reminder_date);
    $stmt->execute();
    
    // Fertilizing reminder
    $reminder_date = date('Y-m-d H:i:s', strtotime("+$fertilizing_frequency days"));
    $query = "INSERT INTO plant_care_reminders (user_garden_plant_id, reminder_type, reminder_date) VALUES (?, 'fertilize', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $garden_plant_id, $reminder_date);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding plant to garden']);
} 