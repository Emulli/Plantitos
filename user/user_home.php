<?php
session_start();
include '../database/db_connect.php';

// Debug session
error_log('User Home - Session contents: ' . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    error_log('User is not logged in, redirecting to login.php');
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Ensure session variables are set with default values if not present
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'Guest';
}

if (!isset($_SESSION['created_at'])) {
    $_SESSION['created_at'] = date('Y-m-d H:i:s');
}

if (!isset($_SESSION['profile_image'])) {
    $_SESSION['profile_image'] = 'img/default-profile.png';
}

if (!isset($_SESSION['email'])) {
    $_SESSION['email'] = 'Not set';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Plantitos - Plant Explorer</title>
<link rel="stylesheet" href="../css/user_home.css">
<link rel="stylesheet" href="../css/botanist.css">
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
            <li><a href="user_home.php" class="active"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="my_garden.php"><i class="fas fa-seedling"></i> My Garden</a></li>
            <li><a href="plant_diseases.php"><i class="fas fa-bug"></i> Plant Diseases</a></li>
            <li><a href="#" id="aboutUsBtn"><i class="fas fa-users"></i> About Us</a></li>
            <li><a href="#" id="profileBtn"><i class="fas fa-user"></i> Profile</a></li>
        </ul>
        <div class="hamburger" id="hamburger">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</nav>

<!-- Hero Section with Carousel -->
<section class="hero">
    <div class="carousel" id="heroCarousel">
        <!-- Slide 1 -->
        <div class="carousel-slide active" style="background-image: url('../img/lumicover.png');">
            <div class="carousel-content">
                <h1>Discover the World of Plants</h1>
                <p>Explore our diverse collection of plants from various environments</p>
                <a href="#categories" class="btn">Browse Plants</a>
            </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="carousel-slide" style="background-image: url('../img/colorfulgarden.jpg');">
            <div class="carousel-content">
                <h1>Find Your Perfect Plant</h1>
                <p>From underwater species to hanging gardens, we have it all</p>
                <a href="#search-section" class="btn">Search Plants</a>
            </div>
        </div>
        
        <!-- Slide 3 -->
        <div class="carousel-slide" style="background-image: url('../img/gardenn.jpg');">
            <div class="carousel-content">
                <h1>Learn About Plant Care</h1>
                <p>Detailed care instructions for every plant in our collection</p>
                <a href="#categories" class="btn">Explore Categories</a>
            </div>
        </div>
        
        <!-- Carousel Navigation -->
        <div class="carousel-arrow carousel-prev" id="prevSlide">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="carousel-arrow carousel-next" id="nextSlide">
            <i class="fas fa-chevron-right"></i>
        </div>
        
        <!-- Carousel Dots -->
        <div class="carousel-nav" id="carouselNav">
            <div class="carousel-dot active" data-slide="0"></div>
            <div class="carousel-dot" data-slide="1"></div>
            <div class="carousel-dot" data-slide="2"></div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section" id="search-section">
    <div class="container">
        <div class="search-container">
            <h2>Find Your Perfect Plant</h2>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by plant name...">
                <button id="searchBtn"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories" id="categories">
    <div class="container">
        <h2 class="section-title">Explore Plant Categories</h2>
        
        <!-- Category Tabs -->
        <div class="category-tabs">
            <button class="category-tab active" data-category="all">All Plants</button>
            <button class="category-tab" data-category="underwater">Underwater</button>
            <button class="category-tab" data-category="garden">Garden</button>
            <button class="category-tab" data-category="hanging">Hanging</button>
            <button class="category-tab" data-category="indoor">Indoor</button>
        </div>
        
        <!-- Plants Grid -->
        <div class="plants-grid" id="plantsGrid">
            <!-- Plants will be loaded here dynamically -->
            <div class="loading-message">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading plants...</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Plants Section -->
<section class="featured-section">
    <div class="container">
        <h2 class="section-title">Featured Plants</h2>
        <div class="featured-grid" id="featuredPlants">
            <!-- Featured plants will be loaded here dynamically -->
            <div class="loading-message">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading featured plants...</p>
            </div>
        </div>
    </div>
</section>

<!-- Ask Botanist Section -->
<section class="ask-botanist-section">
    <div class="container">
        <div class="ask-botanist-content">
            <div class="ask-botanist-text">
                <h2>Ask Our Botanist</h2>
                <p>Have questions about plant care, identification, or growing tips? Our AI botanist is here to help!</p>
                <a href="#" class="btn" id="openBotanistBtn">Chat with PlantGuru</a>
            </div>
            <div class="ask-botanist-image">
                <img src="../img/crazyydave.png" alt="Botanist">
            </div>
        </div>
    </div>
</section>

<!-- Plant Details Modal -->
<div class="modal-overlay" id="plantModal">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle">Plant Details</h2>
            <button class="modal-close" id="closeModal">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Plant details will be loaded here -->
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading plant details...</p>
            </div>
        </div>
    </div>
</div>

<!-- Botanist Chat Modal -->
<div class="modal-overlay" id="botanistModal">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title">Chat with PlantGuru</h2>
            <button class="modal-close" id="closeBotanistModal">&times;</button>
        </div>
        <div class="modal-body" style="padding: 0;">
            <div class="botanist-chat-container">
                <div class="chat-header">
                    <div class="botanist-avatar">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="botanist-info">
                        <h3>PlantGuru</h3>
                        <p>Your personal botanist assistant</p>
                    </div>
                </div>
                <div class="chat-messages" id="chatMessages">
                    <!-- Chat messages will be loaded here -->
                </div>
                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Ask something about plants...">
                    <button id="sendMessageBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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
                            <img src="../img/lowell.jpg" alt="Lowell Toribio">
                        </div>
                        <h4>Lowell Toribio</h4>
                        <p class="member-title">Programmer</p>
                        <p class="member-description">Expert in backend development and database management.</p>
                    </div>
                    
                    <!-- Member 2 -->
                    <div class="team-member">
                        <div class="profile-image">
                            <img src="../img/mampustii.jpg" alt="Jose Maria Paolo Mampusti">
                        </div>
                        <h4>Jose Maria Paolo Mampusti</h4>
                        <p class="member-title">Programmer</p>
                        <p class="member-description">Specializing in frontend development and user experience.</p>
                    </div>
                    
                    <!-- Member 3 -->
                    <div class="team-member">
                        <div class="profile-image">
                            <img src="../img/olandria.png" alt="Jan Ryan Olandria">
                        </div>
                        <h4>Jan Ryan Olandria</h4>
                        <p class="member-title">Documentation Specialist</p>
                        <p class="member-description">Creating comprehensive guides and documentation for users.</p>
                    </div>
                    
                    <!-- Member 4 -->
                    <div class="team-member">
                        <div class="profile-image">
                            <img src="../img/agrifino.jpg" alt="Agrifino Dacles">
                        </div>
                        <h4>Agrifino Dacles</h4>
                        <p class="member-title">Documentation Specialist</p>
                        <p class="member-description">Ensuring accurate and user-friendly documentation.</p>
                    </div>
                    
                    <!-- Member 5 -->
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

<!-- Coming Soon Modal for Profile -->
<div class="modal-overlay" id="comingSoonModal">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title">Coming Soon</h2>
            <button class="modal-close" id="closeComingSoonModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="coming-soon-content">
                <i class="fas fa-clock"></i>
                <h3 id="comingSoonFeature">This Feature is Coming Soon</h3>
                <p>We're working hard to bring you this exciting new feature. Please check back later!</p>
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
                    <label for="profileImageInput" class="btn btn-primary">
                        <i class="fas fa-camera"></i> Change Photo
                    </label>
                    <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
                    <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="logo">
            <i class="fas fa-leaf"></i>
            <span>Plantitos</span>
        </div>
        <ul class="footer-links">
            <li><a href="#">Home</a></li>
            <li><a href="#" id="footerAboutUs">About Us</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Privacy Policy</a></li>
        </ul>
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-pinterest"></i></a>
        </div>
        <p>&copy; 2025 Plantitos. All rights reserved.</p>
    </div>
</footer>

<!-- Chat Head -->
<div class="chat-head" id="chatHead">
    <div class="chat-head-icon">
        <i class="fas fa-robot"></i>
    </div>
    <div class="chat-head-pulse"></div>
</div>

<!-- Include external JavaScript files -->
<script src="../js/script_user.js"></script>
<script src="../js/botanist.js"></script>
<script src="../js/user_home.js"></script>
<script src="../js/dark_mode.js"></script>

<script>
    // Additional script to connect the "Chat with PlantGuru" button
    document.addEventListener('DOMContentLoaded', function() {
        const openBotanistBtn = document.getElementById('openBotanistBtn');
        
        if (openBotanistBtn) {
            openBotanistBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const botanistModal = document.getElementById('botanistModal');
                botanistModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Initialize chat if needed
                const chatMessages = document.getElementById('chatMessages');
                if (chatMessages && chatMessages.children.length === 0) {
                    // Trigger the init function from botanist.js
                    const event = new Event('initChat');
                    document.dispatchEvent(event);
                }
            });
        }
        
        // About Us Modal functionality
        const aboutUsBtn = document.getElementById('aboutUsBtn');
        const footerAboutUs = document.getElementById('footerAboutUs');
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
        
        if (footerAboutUs) {
            footerAboutUs.addEventListener('click', openAboutUsModal);
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

        // Chat Head Functionality
        const chatHead = document.getElementById('chatHead');
        const botanistModal = document.getElementById('botanistModal');
        const closeBotanistModal = document.getElementById('closeBotanistModal');

        chatHead.addEventListener('click', function() {
            botanistModal.classList.add('active');
        });

        closeBotanistModal.addEventListener('click', function() {
            botanistModal.classList.remove('active');
        });
    });
</script>
</body>
</html>