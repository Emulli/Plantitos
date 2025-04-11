<?php
session_start();
include '../database/db_connect.php';

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

if(!isset($_FILES['profile_image'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit();
}

$file = $_FILES['profile_image'];
$user_id = $_SESSION['user_id'];

// Check file type
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if(!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG and GIF are allowed']);
    exit();
}

// Check file size (max 5MB)
if($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB']);
    exit();
}

// Create uploads directory if it doesn't exist
$upload_dir = '../uploads/profile_images/';
if(!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
$target_path = $upload_dir . $filename;

// Move uploaded file
if(move_uploaded_file($file['tmp_name'], $target_path)) {
    // Update database with new image path
    $image_path = 'uploads/profile_images/' . $filename;
    $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
    $stmt->bind_param("si", $image_path, $user_id);
    
    if($stmt->execute()) {
        // Update session with new profile image
        $_SESSION['profile_image'] = $image_path;
        echo json_encode(['success' => true, 'image_url' => $image_path]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
}
?> 