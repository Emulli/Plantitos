<?php
session_start();
include '../database/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get user's garden plants
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user_garden_plants WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$garden_plants = $result->fetch_all(MYSQLI_ASSOC);

// Get available plants from the database
$query = "SELECT * FROM plants";
$result = $conn->query($query);
$available_plants = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Garden - Plantitos</title>
    <link rel="stylesheet" href="../css/user_home.css">
    <link rel="stylesheet" href="../css/my_garden.css">
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
                <li><a href="my_garden.php" class="active"><i class="fas fa-seedling"></i> My Garden</a></li>
                <li><a href="plant_diseases.php"><i class="fas fa-bug"></i> Plant Diseases</a></li>
                <li><a href="#" id="aboutUsBtn"><i class="fas fa-users"></i> About Us</a></li>
                <li><a href="#" id="profileBtn"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
            <div class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- My Garden Section -->
    <section class="my-garden-section">
        <div class="container">
            <div class="garden-header">
                <h1>My Garden</h1>
                <button id="addPlantBtn" class="btn"><i class="fas fa-plus"></i> Add Plant</button>
            </div>

            <!-- Garden Grid -->
            <div class="garden-grid" id="gardenGrid">
                <?php if (empty($garden_plants)): ?>
                    <div class="empty-garden">
                        <i class="fas fa-seedling"></i>
                        <p>Your garden is empty. Add some plants to get started!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($garden_plants as $plant): ?>
                        <div class="garden-plant-card" data-plant-id="<?php echo $plant['id']; ?>">
                            <div class="plant-image <?php echo empty($plant['plant_image']) ? 'no-image' : ''; ?>">
                                <?php if (!empty($plant['plant_image'])): ?>
                                    <?php
                                    // Check if the image exists
                                    $image_exists = false;
                                    $image_path = $plant['plant_image'];
                                    
                                    // Check if the image file exists
                                    if (file_exists($image_path)) {
                                        $image_exists = true;
                                    } else {
                                        // Try to find the image in other possible locations
                                        $possible_paths = [
                                            'uploads/' . basename($image_path),
                                            'admin/uploads/' . basename($image_path),
                                            '../uploads/' . basename($image_path)
                                        ];
                                        
                                        foreach ($possible_paths as $path) {
                                            if (file_exists($path)) {
                                                $image_path = $path;
                                                $image_exists = true;
                                                break;
                                            }
                                        }
                                    }
                                    
                                    if ($image_exists):
                                    ?>
                                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($plant['plant_name']); ?>">
                                    <?php else: ?>
                                        <i class="fas fa-leaf"></i>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <i class="fas fa-leaf"></i>
                                <?php endif; ?>
                            </div>
                            <div class="plant-info">
                                <h3><?php echo htmlspecialchars($plant['plant_name']); ?></h3>
                                <div class="care-info">
                                    <div class="care-item <?php echo (strtotime($plant['last_watered']) < strtotime('-' . $plant['watering_frequency'] . ' days')) ? 'needs-care' : ''; ?>">
                                        <i class="fas fa-tint"></i>
                                        <span>Water</span>
                                        <small>Last: <?php echo $plant['last_watered'] ? date('M d', strtotime($plant['last_watered'])) : 'Never'; ?></small>
                                    </div>
                                    <div class="care-item <?php echo (strtotime($plant['last_misted']) < strtotime('-' . $plant['misting_frequency'] . ' days')) ? 'needs-care' : ''; ?>">
                                        <i class="fas fa-cloud"></i>
                                        <span>Mist</span>
                                        <small>Last: <?php echo $plant['last_misted'] ? date('M d', strtotime($plant['last_misted'])) : 'Never'; ?></small>
                                    </div>
                                    <div class="care-item <?php echo (strtotime($plant['last_fertilized']) < strtotime('-' . $plant['fertilizing_frequency'] . ' days')) ? 'needs-care' : ''; ?>">
                                        <i class="fas fa-flask"></i>
                                        <span>Fertilize</span>
                                        <small>Last: <?php echo $plant['last_fertilized'] ? date('M d', strtotime($plant['last_fertilized'])) : 'Never'; ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="plant-actions">
                                <button class="btn btn-small" onclick="markCare('<?php echo $plant['id']; ?>', 'water')">
                                    <i class="fas fa-tint"></i> Water
                                </button>
                                <button class="btn btn-small" onclick="markCare('<?php echo $plant['id']; ?>', 'mist')">
                                    <i class="fas fa-cloud"></i> Mist
                                </button>
                                <button class="btn btn-small" onclick="markCare('<?php echo $plant['id']; ?>', 'fertilize')">
                                    <i class="fas fa-flask"></i> Fertilize
                                </button>
                                <button class="btn btn-small btn-info" onclick="viewPlantDetails('<?php echo $plant['plant_id']; ?>')" title="View plant details">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <button class="btn btn-small btn-danger" onclick="removePlant('<?php echo $plant['id']; ?>')" title="Remove from garden">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Add Plant Modal -->
    <div class="modal-overlay" id="addPlantModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Add Plant to Garden</h2>
                <button class="modal-close" id="closeAddPlantModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addPlantForm">
                    <div class="form-group">
                        <label for="plantSelect">Select Plant</label>
                        <select id="plantSelect" name="plant_id" required>
                            <option value="">Choose a plant...</option>
                            <?php foreach ($available_plants as $plant): ?>
                                <option value="<?php echo $plant['id']; ?>" 
                                        data-name="<?php echo htmlspecialchars($plant['name']); ?>"
                                        data-image="<?php echo htmlspecialchars($plant['image']); ?>">
                                    <?php echo htmlspecialchars($plant['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="wateringFrequency">Watering Frequency (days)</label>
                        <input type="number" id="wateringFrequency" name="watering_frequency" min="1" value="7" required>
                    </div>
                    <div class="form-group">
                        <label for="mistingFrequency">Misting Frequency (days)</label>
                        <input type="number" id="mistingFrequency" name="misting_frequency" min="1" value="3" required>
                    </div>
                    <div class="form-group">
                        <label for="fertilizingFrequency">Fertilizing Frequency (days)</label>
                        <input type="number" id="fertilizingFrequency" name="fertilizing_frequency" min="1" value="30" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Garden</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Plant Details Modal -->
    <div class="modal-overlay" id="plantDetailsModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title" id="plantDetailsTitle">Plant Details</h2>
                <button class="modal-close" id="closePlantDetailsModal">&times;</button>
            </div>
            <div class="modal-body" id="plantDetailsBody">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading plant details...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/my_garden.js"></script>
    <script src="../js/user_home.js"></script>
    <script src="../js/script_user.js"></script>
    <script src="../js/dark_mode.js"></script>
    
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
</body>
</html>

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