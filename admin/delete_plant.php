<?php
require_once '../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Get image filename before deleting the record
    $stmt = $conn->prepare("SELECT image FROM plants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $image = $row['image'];
        
        // Delete the record from database
        $delete_stmt = $conn->prepare("DELETE FROM plants WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            // If deletion successful and image exists, delete the image file
            if (!empty($image)) {
                $image_path = "../uploads/" . $image;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            echo json_encode(["success" => true, "message" => "Plant deleted successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting plant: " . $delete_stmt->error]);
        }
        
        $delete_stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Plant not found."]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request."]);
?>