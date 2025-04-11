<?php
session_start();
include '../database/db_connect.php';

// Debug session
error_log('Session contents: ' . print_r($_SESSION, true));

if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    error_log('User is logged in, redirecting to user_home.php');
    header("Location: user_home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plantitos</title>
    <link rel="stylesheet" href="../css/user_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <i class="fas fa-leaf"></i>
                <h1>Welcome Back</h1>
                <p>Login to your Plantitos account</p>
            </div>
            
            <?php
            if(isset($_GET['error'])) {
                echo '<div class="error-message">';
                if($_GET['error'] == 'empty') {
                    echo 'Please fill in all fields';
                } else if($_GET['error'] == 'invalid') {
                    echo 'Invalid username or password';
                }
                echo '</div>';
            }
            ?>

            <form class="login-form" action="login_process.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="forgot-password-link">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="login-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <?php include 'script_user.php'; ?>
</body>
</html> 