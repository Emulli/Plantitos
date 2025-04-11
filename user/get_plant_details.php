<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once '../database/db_connect.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'plant' => null
];

try {
    // Check if ID is provided
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("Plant ID is required");
    }
    
    $id = intval($_GET['id']);
    
    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM plants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Plant not found");
    }
    
    // Fetch plant data
    $plant = $result->fetch_assoc();
    
    // Check if image exists in various possible locations
    if (!empty($plant['image'])) {
        // Check possible image paths
        $possiblePaths = [
            'uploads/' . $plant['image'],
            'admin/uploads/' . $plant['image'],
            '../uploads/' . $plant['image']
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
            $imagePath = 'uploads/' . $plant['image'];
        }
        
        $plant['image_url'] = $imagePath;
    } else {
        $plant['image_url'] = '';
    }
    
    // Set response
    $response['success'] = true;
    $response['plant'] = $plant;
    
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
