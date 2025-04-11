<?php
// This file contains all JavaScript for user pages
?>
<script>
// Profile Image Upload Function
function uploadProfileImage(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('profile_image', input.files[0]);
        
        fetch('upload_profile_image.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.querySelector('.profile-image').src = data.image_url;
                location.reload();
            } else {
                alert('Error uploading image: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error uploading image');
        });
    }
}

// Form Validation Functions
function validateLoginForm() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (!username || !password) {
        alert('Please fill in all fields');
        return false;
    }
    
    return true;
}

function validateRegisterForm() {
    const email = document.getElementById('email').value;
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (!email || !username || !password || !confirmPassword) {
        alert('Please fill in all fields');
        return false;
    }
    
    if (password !== confirmPassword) {
        alert('Passwords do not match');
        return false;
    }
    
    return true;
}

// Add event listeners when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Login form validation
    const loginForm = document.querySelector('.login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateLoginForm()) {
                e.preventDefault();
            }
        });
    }
    
    // Register form validation
    const registerForm = document.querySelector('.register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validateRegisterForm()) {
                e.preventDefault();
            }
        });
    }
    
    // Profile image upload
    const profileImageInput = document.getElementById('profile-image');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function() {
            uploadProfileImage(this);
        });
    }
});
</script> 