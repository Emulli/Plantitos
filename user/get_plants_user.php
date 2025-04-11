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
    'plants' => []
];

try {
    // Build query based on parameters
    $query = "SELECT * FROM plants WHERE 1=1";
    $params = [];
    $types = "";
    
    // Filter by search term if provided
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = '%' . $_GET['search'] . '%';
        $query .= " AND (name LIKE ? OR scientific_name LIKE ? OR description LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $types .= "sss";
    }
    
    // Filter by category if provided
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $query .= " AND category = ?";
        $params[] = $_GET['category'];
        $types .= "s";
    }
    
    // Featured plants (random selection)
    if (isset($_GET['featured']) && $_GET['featured'] == 1) {
        $query .= " ORDER BY RAND() LIMIT 4";
    } else {
        $query .= " ORDER BY name ASC";
    }
    
    // Prepare and execute query
    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch all plants
    $plants = [];
    while ($row = $result->fetch_assoc()) {
        // Check if image exists in various possible locations
        if (!empty($row['image'])) {
            // Check possible image paths
            $possiblePaths = [
                'uploads/' . $row['image'],
                'admin/uploads/' . $row['image'],
                '../uploads/' . $row['image']
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
                $imagePath = 'uploads/' . $row['image'];
            }
            
            $row['image_url'] = $imagePath;
        } else {
            $row['image_url'] = '';
        }
        
        $plants[] = $row;
    }
    
    // Set response
    $response['success'] = true;
    $response['plants'] = $plants;
    $response['count'] = count($plants);
    
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