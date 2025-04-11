<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
require_once '../database/db_connect.php'; // Include the database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $scientific_name = isset($_POST['scientific_name']) ? $_POST['scientific_name'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $care_instructions = isset($_POST['care_instructions']) ? $_POST['care_instructions'] : '';
    $environment = isset($_POST['environment']) ? $_POST['environment'] : '';
    $toxicity = isset($_POST['toxicity']) ? 1 : 0;
    
    // Validate required fields
    if (empty($name) || empty($category)) {
        echo json_encode([
            "success" => false, 
            "message" => "Plant name and category are required."
        ]);
        exit;
    }
    
    // Handle image upload
    $target_dir = "../uploads/";
    $image_name = "";
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            echo json_encode([
                "success" => false, 
                "message" => "Failed to create uploads directory. Check permissions."
            ]);
            exit;
        }
    }
    
    // Debug: Check if directory is writable
    if (!is_writable($target_dir)) {
        echo json_encode([
            "success" => false, 
            "message" => "Uploads directory is not writable. Check permissions."
        ]);
        exit;
    }
    
    // Debug: Check if file was uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
        $upload_error = $_FILES["image"]["error"];
        
        if ($upload_error === UPLOAD_ERR_OK) {
            $image_name = time() . '_' . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;
            
            // Check if image file is an actual image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                // Try to upload file
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // File uploaded successfully
                } else {
                    echo json_encode([
                        "success" => false, 
                        "message" => "Error moving uploaded file. Check permissions. Error: " . error_get_last()['message']
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    "success" => false, 
                    "message" => "File is not an image."
                ]);
                exit;
            }
        } else if ($upload_error !== UPLOAD_ERR_NO_FILE) {
            // Handle upload errors
            $error_message = "Unknown error";
            switch ($upload_error) {
                case UPLOAD_ERR_INI_SIZE:
                    $error_message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error_message = "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error_message = "The uploaded file was only partially uploaded";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error_message = "Missing a temporary folder";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error_message = "Failed to write file to disk";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $error_message = "A PHP extension stopped the file upload";
                    break;
            }
            
            echo json_encode([
                "success" => false, 
                "message" => "File upload error: " . $error_message
            ]);
            exit;
        }
    }
    
    try {
        // Check if connection is established
        if (!isset($conn) || $conn->connect_error) {
            throw new Exception("Database connection failed. Please check your connection settings.");
        }
        
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO plants (name, scientific_name, category, description, care_instructions, environment, toxicity, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        // Convert toxicity to integer to ensure proper binding
        $toxicity = (int)$toxicity;
        
        $stmt->bind_param("ssssssss", $name, $scientific_name, $category, $description, $care_instructions, $environment, $toxicity, $image_name);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode([
                "success" => true, 
                "message" => "Plant added successfully!",
                "plant_id" => $conn->insert_id
            ]);
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            "success" => false, 
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
    
    // Close the connection if it exists
    if (isset($conn)) {
        $conn->close();
    }
    
    exit;
}

// If not a POST request, return error
echo json_encode([
    "success" => false, 
    "message" => "Invalid request method. Expected POST, got " . $_SERVER["REQUEST_METHOD"]
]);
?>