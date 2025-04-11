<?php
session_start();
include '../database/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get all plant diseases from the database
$query = "SELECT * FROM plant_diseases ORDER BY name";
$result = $conn->query($query);
$diseases = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Diseases - Plantitos</title>
    <link rel="stylesheet" href="../css/user_home.css">
    <link rel="stylesheet" href="../css/plant_diseases.css">
    <link rel="stylesheet" href="../css/dark_mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-leaf"></i>
                <span>Plantitos</span>
            </div>
            <ul class="nav-links" id="navLinks">
                <li><a href="user_home.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="my_garden.php"><i class="fas fa-seedling"></i> My Garden</a></li>
                <li><a href="plant_diseases.php" class="active"><i class="fas fa-bug"></i> Plant Diseases</a></li>
                <li><a href="#" id="aboutUsBtn"><i class="fas fa-users"></i> About Us</a></li>
                <li><a href="#" id="profileBtn"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
            <div class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Plant Diseases Section -->
    <section class="diseases-section">
        <div class="container">
            <h1>Common Plant Diseases</h1>
            <p class="section-description">Learn about common plant diseases, their symptoms, and how to treat them.</p>
            
            <div class="diseases-grid">
                <?php foreach ($diseases as $disease): ?>
                <div class="disease-card">
                    <div class="disease-image">
                        <?php if ($disease['image']): ?>
                            <img src="../img/diseases/<?php echo htmlspecialchars($disease['image']); ?>" alt="<?php echo htmlspecialchars($disease['name']); ?>">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class="fas fa-bug"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="disease-info">
                        <h2><?php echo htmlspecialchars($disease['name']); ?></h2>
                        <?php if ($disease['scientific_name']): ?>
                            <p class="scientific-name"><em><?php echo htmlspecialchars($disease['scientific_name']); ?></em></p>
                        <?php endif; ?>
                        <p class="description"><?php echo htmlspecialchars($disease['description']); ?></p>
                        
                        <div class="disease-details">
                            <div class="detail-section">
                                <h3>Symptoms</h3>
                                <p><?php echo htmlspecialchars($disease['symptoms']); ?></p>
                            </div>
                            <div class="detail-section">
                                <h3>Treatment</h3>
                                <p><?php echo htmlspecialchars($disease['treatment']); ?></p>
                            </div>
                            <div class="detail-section">
                                <h3>Prevention</h3>
                                <p><?php echo htmlspecialchars($disease['prevention']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- About Us Modal -->
    <div class="modal-overlay" id="aboutUsModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">About Our Team</h2>
                <button class="modal-close" id="closeAboutUsModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="team-container">
                    <!-- Team Introduction -->
                    <div class="team-intro">
                        <h3>Welcome to Plantitos</h3>
                        <p>We are a passionate team dedicated to bringing the world of plants closer to everyone. Our mission is to make plant care accessible, enjoyable, and educational for both beginners and experienced plant enthusiasts.</p>
                    </div>

                    <!-- Team Leader -->
                    <div class="team-leader">
                        <div class="team-member-profile">
                            <div class="profile-image">
                                <img src="../img/me.jpg" alt="Jhon Emerwin M. Bolivar">
                            </div>
                            <h3>Jhon Emerwin M. Bolivar</h3>
                            <p class="member-title">Leader/Lead Programmer</p>
                            <p class="member-description">Leading the development of Plantitos with a vision to create the most comprehensive plant care platform.</p>
                        </div>
                    </div>
                    
                    <!-- Team Members -->
                    <div class="team-members">
                        <!-- Member 1 -->
                        <div class="team-member">
                            <div class="profile-image">
                                <img src="../img/me.jpg" alt="Jhon Emerwin M. Bolivar">
                            </div>
                            <h4>Jhon Emerwin M. Bolivar</h4>
                            <p class="member-title">Lead Developer</p>
                            <p class="member-description">Creating innovative solutions for plant care and management.</p>
                        </div>
                        
                        <!-- Member 2 -->
                        <div class="team-member">
                            <div class="profile-image">
                                <img src="../img/agrifino.jpg" alt="Agrifino Dacles">
                            </div>
                            <h4>Agrifino Dacles</h4>
                            <p class="member-title">Documentation Specialist</p>
                            <p class="member-description">Ensuring accurate and user-friendly documentation.</p>
                        </div>
                        
                        <!-- Member 3 -->
                        <div class="team-member">
                            <div class="profile-image">
                                <img src="../img/jozef.jpg" alt="Aloysius Jozef Garganian">
                            </div>
                            <h4>Aloysius Jozef Garganian</h4>
                            <p class="member-title">Quality Assurance</p>
                            <p class="member-description">Maintaining high standards and testing new features.</p>
                        </div>
                    </div>

                    <!-- Team Mission -->
                    <div class="team-mission">
                        <h3>Our Mission</h3>
                        <p>To empower plant enthusiasts with knowledge, tools, and community support, making plant care an enjoyable and rewarding experience for everyone.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal-overlay" id="profileModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">My Profile</h2>
                <button class="modal-close" id="closeProfileModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="profile-container">
                    <div class="welcome-message">
                        <h3>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
                        <p>You've been a member since <?php echo isset($_SESSION['created_at']) ? date('F j, Y', strtotime($_SESSION['created_at'])) : 'Recently'; ?></p>
                    </div>
                    <div class="profile-image-section">
                        <div class="profile-image">
                            <?php
                            $profile_image = isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image']) 
                                ? '../' . $_SESSION['profile_image'] 
                                : '../img/default-profile.png';
                            ?>
                            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" id="profileImage">
                        </div>
                    </div>
                    <div class="profile-info">
                        <div class="info-group">
                            <label>Username</label>
                            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        </div>
                        <div class="info-group">
                            <label>Email</label>
                            <p><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Not set'; ?></p>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="logout.php" class="btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/user_home.js"></script>
    <script>
        // About Us Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const aboutUsBtn = document.getElementById('aboutUsBtn');
            const aboutUsModal = document.getElementById('aboutUsModal');
            const closeAboutUsModal = document.getElementById('closeAboutUsModal');
            
            function openAboutUsModal(e) {
                e.preventDefault();
                aboutUsModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            if (aboutUsBtn) {
                aboutUsBtn.addEventListener('click', openAboutUsModal);
            }
            
            if (closeAboutUsModal) {
                closeAboutUsModal.addEventListener('click', function() {
                    aboutUsModal.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
            
            // Close modal when clicking outside
            if (aboutUsModal) {
                aboutUsModal.addEventListener('click', function(e) {
                    if (e.target === aboutUsModal) {
                        aboutUsModal.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
            
            // Profile Modal functionality
            const profileBtn = document.getElementById('profileBtn');
            const profileModal = document.getElementById('profileModal');
            const closeProfileModal = document.getElementById('closeProfileModal');
            
            function openProfileModal(e) {
                e.preventDefault();
                profileModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            if (profileBtn) {
                profileBtn.addEventListener('click', openProfileModal);
            }
            
            if (closeProfileModal) {
                closeProfileModal.addEventListener('click', function() {
                    profileModal.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
            
            // Close modal when clicking outside
            if (profileModal) {
                profileModal.addEventListener('click', function(e) {
                    if (e.target === profileModal) {
                        profileModal.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>
    <script src="../js/script_user.js"></script>
    <script src="../js/dark_mode.js"></script>
</body>
</html> 