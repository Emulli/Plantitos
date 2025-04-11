<?php
session_start();
include '../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if any field is empty
    if(empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        header("Location: register.php?error=empty");
        exit();
    }
    
    // Check if passwords match
    if($password !== $confirm_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }
    
    // Check if username is taken
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0) {
        header("Location: register.php?error=username_taken");
        exit();
    }
    
    // Check if email is taken
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0) {
        header("Location: register.php?error=email_taken");
        exit();
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $username, $hashed_password);
    
    if($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        header("Location: user_home.php");
        exit();
    } else {
        header("Location: register.php?error=unknown");
        exit();
    }
}
?> 