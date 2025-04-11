<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once '../database/db_connect.php';

echo "<h1>Image Path Debugging</h1>";

// Check upload directories
$upload_dirs = [
    'uploads',
    'admin/uploads',
    '../uploads'
];

echo "<h2>Checking Upload Directories</h2>";
echo "<ul>";
foreach ($upload_dirs as $dir) {
    if (file_exists($dir) && is_dir($dir)) {
        echo "<li>✅ Directory <strong>$dir</strong> exists</li>";
        
        // List files in directory
        $files = scandir($dir);
        echo "<ul>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "<li>File: $file (Path: $dir/$file)</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<li>❌ Directory <strong>$dir</strong> does not exist</li>";
    }
}
echo "</ul>";

// Check database records
echo "<h2>Checking Database Records</h2>";

try {
    $query = "SELECT id, name, image FROM plants";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Image</th><th>Image Exists?</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['image'] . "</td>";
            
            $image_exists = false;
            foreach ($upload_dirs as $dir) {
                if (!empty($row['image']) && file_exists("$dir/" . $row['image'])) {
                    $image_exists = true;
                    echo "<td>✅ Found in $dir/" . $row['image'] . "</td>";
                    break;
                }
            }
            
            if (!$image_exists) {
                echo "<td>❌ Not found in any directory</td>";
            }
            
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No plants found in database.</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Close connection
$conn->close();
?>

