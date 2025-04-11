<?php
session_start();
include '../database/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page if not logged in
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../css/admin_home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-leaf"></i>
                <span>Plantitos - Admin Page</span>
            </div>
            <ul class="nav-links" id="navLinks">
                <li><a href="logout.php"><i class="fas fa-user"></i> Log out</a></li>
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
                    <h1>Welcome to Plantitos</h1>
                    <p>Your comprehensive solution for monitoring and managing plant health across all environments</p>
                    <a href="#" class="btn">Explore Categories</a>
                </div>
            </div>
            
            <!-- Slide 2 -->
            <div class="carousel-slide" style="background-image: url('../img/colorfulgarden.jpg');">
                <div class="carousel-content">
                    <h1>Manage Your Plant Collection</h1>
                    <p>Add, edit, and organize your plants with our powerful admin tools</p>
                    <a href="#" class="btn" id="addPlantBtn2">Add New Plant</a>
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="carousel-slide" style="background-image: url('../img/gardenn.jpg');">
                <div class="carousel-content">
                    <h1>Track Plant Health & Care</h1>
                    <p>Monitor watering schedules, growth patterns, and care requirements</p>
                    <a href="#" class="btn">View Reports</a>
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

    <!-- Admin Actions Bar -->
    <div class="admin-actions">
        <div class="admin-title">
            <i class="fas fa-tachometer-alt"></i>
            <span>Plant Management Dashboard</span>
        </div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search plants...">
            <button id="searchBtn"><i class="fas fa-search"></i></button>
        </div>
        <button class="add-btn" id="addPlantBtn"><i class="fas fa-plus"></i> Add New Plant</button>
    </div>

    <!-- Categories Section with Plants -->
    <section class="categories">
        <h2 class="section-title">Plant Categories</h2>
        <div class="category-grid">
            <!-- Category 1: Underwater Plants -->
            <div class="category-card">
                <div class="category-image" style="background-image: url('../img/lumicover.png');"></div>
                <div class="category-content">
                    <h3>Underwater Plants <span class="plant-count" id="underwater-count">0</span></h3>
                    <p>Aquatic plants that thrive in underwater environments, perfect for aquariums and ponds.</p>
                    <div class="category-stats">
                        <span><i class="fas fa-tint"></i> High Humidity</span>
                        <span><i class="fas fa-sun"></i> Low Light</span>
                    </div>
                    
                    <!-- Plants in this category -->
                    <div class="plants-list" id="underwater-plants">
                        <!-- Plants will be loaded dynamically -->
                        <div class="loading-plants">Loading plants...</div>
                    </div>
                </div>
            </div>

            <!-- Category 2: Garden Plants -->
            <div class="category-card">
                <div class="category-image" style="background-image: url('../img/colorfulgarden.jpg');"></div>
                <div class="category-content">
                    <h3>Garden Plants <span class="plant-count" id="garden-count">0</span></h3>
                    <p>Outdoor plants suitable for gardens, providing beautiful landscapes and fresh air.</p>
                    <div class="category-stats">
                        <span><i class="fas fa-sun"></i> Full Sun</span>
                        <span><i class="fas fa-tint"></i> Medium Water</span>
                    </div>
                    
                    <!-- Plants in this category -->
                    <div class="plants-list" id="garden-plants">
                        <!-- Plants will be loaded dynamically -->
                        <div class="loading-plants">Loading plants...</div>
                    </div>
                </div>
            </div>

            <!-- Category 3: Hanging Plants -->
            <div class="category-card">
                <div class="category-image" style="background-image: url('../img/hanihn.jpg');"></div>
                <div class="category-content">
                    <h3>Hanging Plants <span class="plant-count" id="hanging-count">0</span></h3>
                    <p>Decorative plants that hang beautifully, ideal for adding greenery to any space.</p>
                    <div class="category-stats">
                        <span><i class="fas fa-sun"></i> Partial Sun</span>
                        <span><i class="fas fa-tint"></i> Low Water</span>
                    </div>
                    
                    <!-- Plants in this category -->
                    <div class="plants-list" id="hanging-plants">
                        <!-- Plants will be loaded dynamically -->
                        <div class="loading-plants">Loading plants...</div>
                    </div>
                </div>
            </div>

            <!-- Category 4: Indoor Plants -->
            <div class="category-card">
                <div class="category-image" style="background-image: url('../img/indoor.jpg');"></div>
                <div class="category-content">
                    <h3>Indoor Plants <span class="plant-count" id="indoor-count">0</span></h3>
                    <p>Plants that thrive indoors with minimal maintenance, perfect for homes and offices.</p>
                    <div class="category-stats">
                        <span><i class="fas fa-sun"></i> Low Light</span>
                        <span><i class="fas fa-tint"></i> Low Water</span>
                    </div>
                    
                    <!-- Plants in this category -->
                    <div class="plants-list" id="indoor-plants">
                        <!-- Plants will be loaded dynamically -->
                        <div class="loading-plants">Loading plants...</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add Plant Modal -->
    <div class="modal-overlay" id="addPlantModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Add New Plant</h2>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="plantForm" enctype="multipart/form-data">
                    <input type="hidden" id="plantId" name="id" value="">
                    
                    <div class="form-group">
                        <label for="plantName">Plant Name</label>
                        <input type="text" id="plantName" name="name" placeholder="Enter plant name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="scientificName">Scientific Name</label>
                        <input type="text" id="scientificName" name="scientific_name" placeholder="Enter scientific name">
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <option value="">Select a category</option>
                            <option value="underwater">Underwater Plants</option>
                            <option value="garden">Garden Plants</option>
                            <option value="hanging">Hanging Plants</option>
                            <option value="indoor">Indoor Plants</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Enter plant description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="careInstructions">Care Instructions</label>
                        <textarea id="careInstructions" name="care_instructions" placeholder="Enter care instructions"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="environment">Environment</label>
                        <textarea id="environment" name="environment" placeholder="Describe ideal growing environment"></textarea>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="toxicity" name="toxicity">
                        <label for="toxicity">This plant is toxic to humans or pets</label>
                    </div>
                    
                    <div class="form-group">
                        <label for="plantImage">Plant Image</label>
                        <input type="file" id="plantImage" name="image" accept="image/*">
                        <div class="image-preview" id="imagePreview">
                            <div class="image-preview-placeholder">
                                <i class="fas fa-image"></i>
                                <p>No image selected</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" id="cancelBtn">Cancel</button>
                <button class="save-btn" id="saveBtn">Save Plant</button>
            </div>
        </div>
    </div>

    <!-- View Plant Modal -->
    <div class="modal-overlay" id="viewPlantModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title" id="viewModalTitle">Plant Details</h2>
                <button class="modal-close" id="closeViewModal">&times;</button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Plant details will be loaded here -->
                <div class="loading">Loading plant details...</div>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" id="closeViewBtn">Close</button>
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
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
            <p>&copy; 2025 Plantitos. All rights reserved.</p>
        </div>
    </footer>
</body>

<script src="../js/script.js"></script>
</html>