<?php
session_start();
include '../database/db_connect.php';

if(isset($_SESSION['user_id'])) {
    header("Location: user_home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Plantitos</title>
    <link rel="stylesheet" href="../css/user_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <div class="register-header">
                <i class="fas fa-leaf"></i>
                <h1>Create Account</h1>
                <p>Join the Plantitos community</p>
            </div>
            
            <?php
            if(isset($_GET['error'])) {
                echo '<div class="error-message">';
                if($_GET['error'] == 'empty') {
                    echo 'Please fill in all fields';
                } else if($_GET['error'] == 'password_mismatch') {
                    echo 'Passwords do not match';
                } else if($_GET['error'] == 'username_taken') {
                    echo 'Username is already taken';
                } else if($_GET['error'] == 'email_taken') {
                    echo 'Email is already registered';
                }
                echo '</div>';
            }
            ?>

            <form class="register-form" action="register_process.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="register-btn">Register</button>
            </form>
            
            <div class="register-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <?php include 'script_user.php'; ?>
</body>
</html> 