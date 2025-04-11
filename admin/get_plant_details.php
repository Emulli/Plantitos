<?php
require_once '../database/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM plants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($plant = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "plant" => $plant]);
    } else {
        echo json_encode(["success" => false, "message" => "Plant not found."]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request."]);
?>