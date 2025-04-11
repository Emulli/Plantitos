// Mobile Navigation Toggle
document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.getElementById("hamburger")
    const navLinks = document.getElementById("navLinks")
  
    hamburger.addEventListener("click", () => {
      navLinks.classList.toggle("active")
  
      // Toggle hamburger icon
      const icon = hamburger.querySelector("i")
      if (icon.classList.contains("fa-bars")) {
        icon.classList.remove("fa-bars")
        icon.classList.add("fa-times")
      } else {
        icon.classList.remove("fa-times")
        icon.classList.add("fa-bars")
      }
    })
  
    // Carousel Functionality
    const slides = document.querySelectorAll(".carousel-slide")
    const dots = document.querySelectorAll(".carousel-dot")
    const prevBtn = document.getElementById("prevSlide")
    const nextBtn = document.getElementById("nextSlide")
    let currentSlide = 0
    let slideInterval
  
    // Initialize carousel
    function startCarousel() {
      slideInterval = setInterval(() => {
        changeSlide(currentSlide + 1)
      }, 5000) // Change slide every 5 seconds
    }
  
    // Stop carousel on user interaction
    function stopCarousel() {
      clearInterval(slideInterval)
    }
  
    // Change slide
    function changeSlide(n) {
      slides[currentSlide].classList.remove("active")
      dots[currentSlide].classList.remove("active")
  
      currentSlide = (n + slides.length) % slides.length
  
      slides[currentSlide].classList.add("active")
      dots[currentSlide].classList.add("active")
    }
  
    // Event listeners for carousel
    prevBtn.addEventListener("click", () => {
      stopCarousel()
      changeSlide(currentSlide - 1)
      startCarousel()
    })
  
    nextBtn.addEventListener("click", () => {
      stopCarousel()
      changeSlide(currentSlide + 1)
      startCarousel()
    })
  
    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        stopCarousel()
        changeSlide(index)
        startCarousel()
      })
    })
  
    // Start carousel on page load
    startCarousel()
  
    // Auto-refresh functionality to check for updates from admin
    let lastUpdateTime = null;
    let updateCheckInterval;
    
    // Function to check for updates
    function checkForUpdates() {
      // Use a fixed path to check_updates.php
      const updatePath = 'check_updates.php';
      
      console.log('Checking for updates at path:', updatePath);
      
      fetch(updatePath)
        .then(response => {
          console.log('Response status:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('Received update data:', data);
          
          if (lastUpdateTime === null) {
            // First time checking, just store the timestamp
            lastUpdateTime = data.last_update;
            console.log('First check, storing timestamp:', lastUpdateTime);
          } else {
            // Compare timestamps
            const currentTimestamp = data.last_update;
            console.log('Comparing timestamps - Current:', currentTimestamp, 'Previous:', lastUpdateTime);
            
            if (currentTimestamp !== lastUpdateTime) {
              // Update detected, refresh the page
              console.log('Update detected! Old timestamp:', lastUpdateTime, 'New timestamp:', currentTimestamp);
              console.log('Refreshing page...');
              forceRefresh();
            } else {
              console.log('No updates detected. Current timestamp:', lastUpdateTime);
            }
          }
        })
        .catch(error => {
          console.error('Error checking for updates:', error);
        });
    }
    
    // Function to force a refresh
    function forceRefresh() {
      // Try multiple methods to ensure the page refreshes
      try {
        // Method 1: Standard reload
        location.reload();
        
        // Method 2: Force reload from server (not from cache)
        setTimeout(() => {
          window.location.href = window.location.href + '?refresh=' + new Date().getTime();
        }, 100);
      } catch (e) {
        console.error('Error refreshing page:', e);
      }
    }
    
    // Start checking for updates every 30 seconds
    function startUpdateCheck() {
      console.log('Starting update check...');
      // Check immediately on page load
      checkForUpdates();
      
      // Then check every 10 seconds
      updateCheckInterval = setInterval(checkForUpdates, 10000);
      console.log('Update check interval set to 10 seconds');
    }
    
    // Start the update check if we're on the user_home.php page
    function isUserHomePage() {
      // Check if we're on the user_home.php page
      const path = window.location.pathname;
      return path.includes('user_home.php') || path.endsWith('/user/');
    }
    
    if (isUserHomePage()) {
      console.log('On user_home.php page, starting update check');
      startUpdateCheck();
    } else {
      console.log('Not on user_home.php page, update check not started');
    }
  
    // Plant Display Functionality
    // DOM Elements
    const plantsGrid = document.getElementById("plantsGrid")
    const featuredPlants = document.getElementById("featuredPlants")
    const categoryTabs = document.querySelectorAll(".category-tab")
    const searchInput = document.getElementById("searchInput")
    const searchBtn = document.getElementById("searchBtn")
    const plantModal = document.getElementById("plantModal")
    const modalTitle = document.getElementById("modalTitle")
    const modalBody = document.getElementById("modalBody")
    const closeModal = document.getElementById("closeModal")
    const profileBtn = document.getElementById("profileBtn")
    const comingSoonModal = document.getElementById("comingSoonModal")
    const closeComingSoonModal = document.getElementById("closeComingSoonModal")
    const comingSoonFeature = document.getElementById("comingSoonFeature")
  
    // About Us Modal Elements
    const aboutUsBtn = document.getElementById("aboutUsBtn")
    const footerAboutUs = document.getElementById("footerAboutUs")
    const aboutUsModal = document.getElementById("aboutUsModal")
    const closeAboutUsModal = document.getElementById("closeAboutUsModal")
  
    // Profile Modal Elements
    const profileModal = document.getElementById("profileModal")
    const closeProfileModal = document.getElementById("closeProfileModal")
    const profileImageInput = document.getElementById("profileImageInput")
    const profileImage = document.getElementById("profileImage")
  
    // Load all plants on page load
    loadPlants()
    loadFeaturedPlants()
  
    // Category tab functionality
    categoryTabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        // Remove active class from all tabs
        categoryTabs.forEach((t) => t.classList.remove("active"))
  
        // Add active class to clicked tab
        tab.classList.add("active")
  
        // Load plants for selected category
        const category = tab.dataset.category
        loadPlants(null, category)
      })
    })
  
    // Search functionality
    searchBtn.addEventListener("click", () => {
      const searchTerm = searchInput.value.trim()
      if (searchTerm) {
        // Reset category tabs
        categoryTabs.forEach((t) => t.classList.remove("active"))
        categoryTabs[0].classList.add("active") // Set "All Plants" as active
  
        // Load plants matching search term
        loadPlants(searchTerm)
      }
    })
  
    // Also search when Enter key is pressed
    searchInput.addEventListener("keyup", (e) => {
      if (e.key === "Enter") {
        searchBtn.click()
      }
    })
  
    // Coming soon feature for Profile
    profileBtn.addEventListener("click", (e) => {
        e.preventDefault();
        profileModal.classList.add("active");
        document.body.style.overflow = "hidden";
    })
  
    // About Us modal functionality
    function openAboutUsModal(e) {
      if (e) e.preventDefault()
      aboutUsModal.classList.add("active")
      document.body.style.overflow = "hidden"
    }
  
    if (aboutUsBtn) {
      aboutUsBtn.addEventListener("click", openAboutUsModal)
    }
  
    if (footerAboutUs) {
      footerAboutUs.addEventListener("click", openAboutUsModal)
    }
  
    if (closeAboutUsModal) {
      closeAboutUsModal.addEventListener("click", () => {
        aboutUsModal.classList.remove("active")
        document.body.style.overflow = ""
      })
    }
  
    // Close About Us modal when clicking outside
    if (aboutUsModal) {
      aboutUsModal.addEventListener("click", (e) => {
        if (e.target === aboutUsModal) {
          aboutUsModal.classList.remove("active")
          document.body.style.overflow = ""
        }
      })
    }
  
    // Show coming soon modal
    function showComingSoonModal(feature) {
      comingSoonFeature.textContent = feature + " is Coming Soon"
      comingSoonModal.classList.add("active")
      document.body.style.overflow = "hidden"
    }
  
    // Close coming soon modal
    closeComingSoonModal.addEventListener("click", () => {
      comingSoonModal.classList.remove("active")
      document.body.style.overflow = ""
    })
  
    // Close modal when clicking outside
    comingSoonModal.addEventListener("click", (e) => {
      if (e.target === comingSoonModal) {
        comingSoonModal.classList.remove("active")
        document.body.style.overflow = ""
      }
    })
  
    // Function to load plants
    function loadPlants(search = null, category = "all") {
      // Show loading message
      plantsGrid.innerHTML = `
            <div class="loading-message">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading plants...</p>
            </div>
        `
  
      // Build query string
      let queryString = ""
      if (search) {
        queryString += `search=${encodeURIComponent(search)}`
      }
      if (category && category !== "all") {
        if (queryString) queryString += "&"
        queryString += `category=${encodeURIComponent(category)}`
      }
  
      // Fetch plants from the server
      fetch(`get_plants_user.php${queryString ? "?" + queryString : ""}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`)
          }
          return response.json()
        })
        .then((data) => {
          if (data.success) {
            // Clear grid
            plantsGrid.innerHTML = ""
  
            if (data.plants.length > 0) {
              // Create plant cards
              data.plants.forEach((plant) => {
                plantsGrid.appendChild(createPlantCard(plant))
              })
            } else {
              // No plants found
              plantsGrid.innerHTML = `
                            <div class="no-results">
                                <i class="fas fa-leaf"></i>
                                <p>No plants found. Try a different search or category.</p>
                            </div>
                        `
            }
          } else {
            // Error loading plants
            plantsGrid.innerHTML = `
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>${data.message || "Error loading plants. Please try again."}</p>
                        </div>
                    `
          }
        })
        .catch((error) => {
          console.error("Error:", error)
          plantsGrid.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Error loading plants. Please try again.</p>
                    </div>
                `
        })
    }
  
    // Function to load featured plants
    function loadFeaturedPlants() {
      // Show loading message
      featuredPlants.innerHTML = `
            <div class="loading-message">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading featured plants...</p>
            </div>
        `
  
      // Fetch plants from the server (we'll use the same endpoint but limit results)
      fetch("get_plants_user.php?featured=1")
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`)
          }
          return response.json()
        })
        .then((data) => {
          if (data.success) {
            // Clear grid
            featuredPlants.innerHTML = ""
  
            if (data.plants.length > 0) {
              // Create featured plant cards (limit to 4)
              const featuredData = data.plants.slice(0, 3)
              featuredData.forEach((plant) => {
                featuredPlants.appendChild(createFeaturedPlantCard(plant))
              })
            } else {
              // No featured plants
              featuredPlants.innerHTML = `
                            <div class="no-results">
                                <i class="fas fa-leaf"></i>
                                <p>No featured plants available.</p>
                            </div>
                        `
            }
          } else {
            // Error loading plants
            featuredPlants.innerHTML = `
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>${data.message || "Error loading featured plants."}</p>
                        </div>
                    `
          }
        })
        .catch((error) => {
          console.error("Error:", error)
          featuredPlants.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Error loading featured plants.</p>
                    </div>
                `
        })
    }
  
    // Function to create a plant card
    function createPlantCard(plant) {
      const card = document.createElement("div")
      card.className = "plant-card"
      card.dataset.id = plant.id
  
      // Create card image
      const cardImage = document.createElement("div")
      cardImage.className = "plant-card-image"
  
      const img = document.createElement("img")
  
      // Try multiple possible image paths
      const possiblePaths = [
        plant.image_url,
        plant.image ? "uploads/" + plant.image : null,
        plant.image ? "admin/uploads/" + plant.image : null,
        plant.image ? "../uploads/" + plant.image : null,
        "https://source.unsplash.com/random/300x200/?plant",
      ]
  
      // Filter out null paths
      const validPaths = possiblePaths.filter((path) => path !== null)
  
      // Set the first path as the source
      img.src = validPaths[0]
      img.alt = plant.name
  
      // Add error handling to try alternative paths
      let pathIndex = 0
      img.onerror = function () {
        pathIndex++
        if (pathIndex < validPaths.length) {
          this.src = validPaths[pathIndex]
        }
      }
  
      // Add category badge
      const categoryBadge = document.createElement("span")
      categoryBadge.className = "category-badge"
      categoryBadge.textContent = getCategoryName(plant.category)
  
      cardImage.appendChild(img)
      cardImage.appendChild(categoryBadge)
  
      // Create card content
      const cardContent = document.createElement("div")
      cardContent.className = "plant-card-content"
  
      const plantName = document.createElement("h3")
      plantName.textContent = plant.name
  
      // Add toxic indicator if plant is toxic
      if (plant.toxicity == 1) {
        const toxicIndicator = document.createElement("span")
        toxicIndicator.className = "toxic-indicator"
        toxicIndicator.title = "Toxic to humans or pets"
        toxicIndicator.innerHTML = '<i class="fas fa-exclamation-triangle"></i>'
        plantName.appendChild(toxicIndicator)
      }
  
      const scientificName = document.createElement("p")
      scientificName.className = "scientific-name"
      scientificName.textContent = plant.scientific_name || ""
  
      const description = document.createElement("p")
      description.className = "description"
      description.textContent = truncateText(plant.description || "No description available", 100)
  
      const viewButton = document.createElement("button")
      viewButton.className = "view-details-btn"
      viewButton.textContent = "View Details"
      viewButton.addEventListener("click", () => viewPlantDetails(plant.id))
  
      cardContent.appendChild(plantName)
      cardContent.appendChild(scientificName)
      cardContent.appendChild(description)
      cardContent.appendChild(viewButton)
  
      // Assemble card
      card.appendChild(cardImage)
      card.appendChild(cardContent)
  
      return card
    }
  
    // Function to create a featured plant card
    function createFeaturedPlantCard(plant) {
      const card = document.createElement("div")
      card.className = "featured-card"
      card.dataset.id = plant.id
  
      // Try multiple possible image paths
      const possiblePaths = [
        plant.image_url,
        plant.image ? "uploads/" + plant.image : null,
        plant.image ? "admin/uploads/" + plant.image : null,
        plant.image ? "../uploads/" + plant.image : null,
        "https://source.unsplash.com/random/400x300/?plant",
      ]
  
      // Filter out null paths
      const validPaths = possiblePaths.filter((path) => path !== null)
  
      // Create background image with the first path
      card.style.backgroundImage = `url(${validPaths[0]})`
  
      // Add error handling for background image
      const img = new Image()
      img.src = validPaths[0]
      let pathIndex = 0
  
      img.onerror = () => {
        pathIndex++
        if (pathIndex < validPaths.length) {
          card.style.backgroundImage = `url(${validPaths[pathIndex]})`
          img.src = validPaths[pathIndex]
        }
      }
  
      // Create overlay
      const overlay = document.createElement("div")
      overlay.className = "featured-overlay"
  
      // Create content
      const content = document.createElement("div")
      content.className = "featured-content"
  
      const plantName = document.createElement("h3")
      plantName.textContent = plant.name
  
      const category = document.createElement("p")
      category.className = "featured-category"
      category.textContent = getCategoryName(plant.category)
  
      const viewButton = document.createElement("button")
      viewButton.className = "featured-btn"
      viewButton.textContent = "View Details"
      viewButton.addEventListener("click", () => viewPlantDetails(plant.id))
  
      content.appendChild(plantName)
      content.appendChild(category)
      content.appendChild(viewButton)
  
      // Assemble card
      overlay.appendChild(content)
      card.appendChild(overlay)
  
      return card
    }
  
    // Function to view plant details
    function viewPlantDetails(id) {
      // Show loading in modal
      modalBody.innerHTML = `
            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading plant details...</p>
            </div>
        `
  
      // Show modal
      plantModal.classList.add("active")
      document.body.style.overflow = "hidden"
  
      // Fetch plant details
      fetch(`get_plant_details.php?id=${id}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`)
          }
          return response.json()
        })
        .then((data) => {
          if (data.success) {
            const plant = data.plant
  
            // Update modal title
            modalTitle.textContent = plant.name
  
            // Try multiple possible image paths
            const possiblePaths = [
              plant.image_url,
              plant.image ? "uploads/" + plant.image : null,
              plant.image ? "admin/uploads/" + plant.image : null,
              plant.image ? "../uploads/" + plant.image : null,
              "https://source.unsplash.com/random/400x300/?plant",
            ]
  
            // Filter out null paths
            const validPaths = possiblePaths.filter((path) => path !== null)
  
            // Create plant details HTML
            const detailsHTML = `
                        <div class="plant-detail-container">
                            <div class="plant-detail-image">
                                <img src="${validPaths[0]}" alt="${plant.name}" 
                                    onerror="if (this.src !== '${validPaths[validPaths.length - 1]}') this.src = '${validPaths[validPaths.length - 1]}';">
                                <span class="category-badge">${getCategoryName(plant.category)}</span>
                            </div>
                            <div class="plant-detail-info">
                                <h3>${plant.name}</h3>
                                <p class="scientific-name">${plant.scientific_name || "No scientific name provided"}</p>
                                
                                <div class="detail-section">
                                    <h4>Description</h4>
                                    <p>${plant.description || "No description provided"}</p>
                                </div>
                                
                                <div class="detail-section">
                                    <h4>Care Instructions</h4>
                                    <p>${plant.care_instructions || "No care instructions provided"}</p>
                                </div>
                                
                                <div class="detail-section">
                                    <h4>Environment</h4>
                                    <p>${plant.environment || "No environment information provided"}</p>
                                </div>
                                
                                <div class="detail-section">
                                    <h4>Toxicity</h4>
                                    <p>${
                                      plant.toxicity == 1
                                        ? '<span class="toxic-tag"><i class="fas fa-exclamation-triangle"></i> Toxic to humans or pets</span>'
                                        : '<span class="non-toxic-tag"><i class="fas fa-check-circle"></i> Not toxic</span>'
                                    }</p>
                                </div>
                            </div>
                        </div>
                    `
  
            modalBody.innerHTML = detailsHTML
          } else {
            modalBody.innerHTML = `
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <p>${data.message || "Error loading plant details."}</p>
                        </div>
                    `
          }
        })
        .catch((error) => {
          console.error("Error:", error)
          modalBody.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Error loading plant details. Please try again.</p>
                    </div>
                `
        })
    }
  
    // Close plant details modal
    closeModal.addEventListener("click", () => {
      plantModal.classList.remove("active")
      document.body.style.overflow = ""
    })
  
    // Close modal when clicking outside
    plantModal.addEventListener("click", (e) => {
      if (e.target === plantModal) {
        plantModal.classList.remove("active")
        document.body.style.overflow = ""
      }
    })
  
    // Helper function to get category name
    function getCategoryName(category) {
      switch (category) {
        case "underwater":
          return "Underwater"
        case "garden":
          return "Garden"
        case "hanging":
          return "Hanging"
        case "indoor":
          return "Indoor"
        default:
          return "Other"
      }
    }
  
    // Helper function to truncate text
    function truncateText(text, maxLength) {
      if (text.length <= maxLength) return text
      return text.substring(0, maxLength) + "..."
    }
  
    // Profile Modal Functionality
    // Open profile modal
    profileBtn.addEventListener("click", (e) => {
        e.preventDefault();
        profileModal.classList.add("active");
        document.body.style.overflow = "hidden";
    });
  
    // Close profile modal
    closeProfileModal.addEventListener("click", () => {
        profileModal.classList.remove("active");
        document.body.style.overflow = "";
    });
  
    // Close modal when clicking outside
    profileModal.addEventListener("click", (e) => {
        if (e.target === profileModal) {
            profileModal.classList.remove("active");
            document.body.style.overflow = "";
        }
    });
  
    // Handle profile image upload
    profileImageInput.addEventListener("change", function(e) {
        const file = e.target.files[0];
        if (file) {
            const formData = new FormData();
            formData.append("profile_image", file);

            // Show loading state
            profileImage.style.opacity = "0.5";

            fetch("upload_profile_image.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update profile image
                    profileImage.src = "../" + data.image_url;
                    profileImage.style.opacity = "1";
                    
                    // Show success message
                    const successMessage = document.createElement("div");
                    successMessage.className = "success-message";
                    successMessage.textContent = "Profile image updated successfully!";
                    profileModal.querySelector(".modal-body").insertBefore(
                        successMessage,
                        profileModal.querySelector(".profile-container")
                    );
                    
                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);
                } else {
                    // Show error message
                    const errorMessage = document.createElement("div");
                    errorMessage.className = "error-message";
                    errorMessage.textContent = data.message || "Error uploading image";
                    profileModal.querySelector(".modal-body").insertBefore(
                        errorMessage,
                        profileModal.querySelector(".profile-container")
                    );
                    
                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        errorMessage.remove();
                    }, 3000);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                // Show error message
                const errorMessage = document.createElement("div");
                errorMessage.className = "error-message";
                errorMessage.textContent = "Error uploading image";
                profileModal.querySelector(".modal-body").insertBefore(
                    errorMessage,
                    profileModal.querySelector(".profile-container")
                );
                
                // Remove error message after 3 seconds
                setTimeout(() => {
                    errorMessage.remove();
                }, 3000);
            })
            .finally(() => {
                profileImage.style.opacity = "1";
            });
        }
    });
  })  