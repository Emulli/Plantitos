<?php
require_once '../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $scientific_name = $_POST['scientific_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $care_instructions = $_POST['care_instructions'];
    $environment = $_POST['environment'];
    $toxicity = isset($_POST['toxicity']) ? 1 : 0;
    
    // Check if a new image is uploaded
    $image_update = "";
    $params = "ssssssi";
    $old_image = "";
    
    // Get the current image
    $stmt = $conn->prepare("SELECT image FROM plants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $old_image = $row['image'];
    }
    $stmt->close();
    
    // Handle image upload if a new one is provided
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "../uploads/";
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Try to upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // File uploaded successfully
                $image_update = ", image = ?";
                $params .= "s";
                
                // Delete old image if it exists
                if (!empty($old_image)) {
                    $old_image_path = $target_dir . $old_image;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
            } else {
                echo json_encode(["success" => false, "message" => "Error uploading file."]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "message" => "File is not an image."]);
            exit;
        }
    }
    
    // Prepare SQL statement
    $sql = "UPDATE plants SET name = ?, scientific_name = ?, category = ?, description = ?, care_instructions = ?, environment = ?, toxicity = ?$image_update WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($image_update) {
        $stmt->bind_param($params . "i", $name, $scientific_name, $category, $description, $care_instructions, $environment, $toxicity, $image_name, $id);
    } else {
        $stmt->bind_param($params . "i", $name, $scientific_name, $category, $description, $care_instructions, $environment, $toxicity, $id);
    }
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Plant updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request."]);
?>