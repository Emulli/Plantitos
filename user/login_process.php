<?php
session_start();
include '../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if(empty($username) || empty($password)) {
        header("Location: login.php?error=empty");
        exit();
    }
    
    $stmt = $conn->prepare("SELECT id, username, password, email, created_at, profile_image FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['created_at'] = $user['created_at'];
            $_SESSION['profile_image'] = $user['profile_image'];
            $_SESSION['user_logged_in'] = true;
            header("Location: user_home.php");
            exit();
        }
    }
    
    header("Location: login.php?error=invalid");
    exit();
}
?> 