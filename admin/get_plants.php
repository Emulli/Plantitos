<?php
require_once '../database/db_connect.php';

// Get search term if provided
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL statement with search functionality
if (!empty($search)) {
    $search = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM plants WHERE name LIKE ? OR scientific_name LIKE ? OR description LIKE ? ORDER BY category, name");
    $stmt->bind_param("sss", $search, $search, $search);
} else {
    $stmt = $conn->prepare("SELECT * FROM plants ORDER BY category, name");
}

$stmt->execute();
$result = $stmt->get_result();
$plants = [];

while ($row = $result->fetch_assoc()) {
    $plants[] = $row;
}

// Group plants by category
$categorized_plants = [
    'underwater' => [],
    'garden' => [],
    'hanging' => [],
    'indoor' => [],
    'cacti' => []
];

foreach ($plants as $plant) {
    if (isset($categorized_plants[$plant['category']])) {
        $categorized_plants[$plant['category']][] = $plant;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'plants' => $plants,
    'categorized' => $categorized_plants,
    'counts' => [
        'underwater' => count($categorized_plants['underwater']),
        'garden' => count($categorized_plants['garden']),
        'hanging' => count($categorized_plants['hanging']),
        'indoor' => count($categorized_plants['indoor']),
        'cacti' => count($categorized_plants['cacti'])
    ]
]);

$stmt->close();
$conn->close();
?>