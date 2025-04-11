<?php
session_start();
include '../database/db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = 'Please enter your email address.';
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store reset token in database
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $expiry, $email);
            
            if ($stmt->execute()) {
                // Send reset email (you would implement this)
                $success = 'Password reset instructions have been sent to your email.';
            } else {
                $error = 'An error occurred. Please try again.';
            }
        } else {
            $error = 'No account found with that email address.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Plantitos</title>
    <link rel="stylesheet" href="../css/user_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="forgot-password-container">
        <div class="forgot-password-header">
            <i class="fas fa-key"></i>
            <h2>Forgot Password</h2>
            <p>Enter your email to reset your password</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form class="forgot-password-form" method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="reset-btn">Reset Password</button>
        </form>
        
        <div class="back-to-login">
            <a href="login.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
    </div>
</body>
</html> 