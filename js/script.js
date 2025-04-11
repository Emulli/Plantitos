// Mobile Navigation Toggle
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('navLinks');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    
    // Toggle hamburger icon
    const icon = hamburger.querySelector('i');
    if (icon.classList.contains('fa-bars')) {
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-times');
    } else {
        icon.classList.remove('fa-times');
        icon.classList.add('fa-bars');
    }
});

// Carousel Functionality
const slides = document.querySelectorAll('.carousel-slide');
const dots = document.querySelectorAll('.carousel-dot');
const prevBtn = document.getElementById('prevSlide');
const nextBtn = document.getElementById('nextSlide');
let currentSlide = 0;
let slideInterval;

// Initialize carousel
function startCarousel() {
    slideInterval = setInterval(() => {
        changeSlide(currentSlide + 1);
    }, 5000); // Change slide every 5 seconds
}

// Stop carousel on user interaction
function stopCarousel() {
    clearInterval(slideInterval);
}

// Change slide
function changeSlide(n) {
    slides[currentSlide].classList.remove('active');
    dots[currentSlide].classList.remove('active');
    
    currentSlide = (n + slides.length) % slides.length;
    
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
}

// Event listeners
prevBtn.addEventListener('click', () => {
    stopCarousel();
    changeSlide(currentSlide - 1);
    startCarousel();
});

nextBtn.addEventListener('click', () => {
    stopCarousel();
    changeSlide(currentSlide + 1);
    startCarousel();
});

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        stopCarousel();
        changeSlide(index);
        startCarousel();
    });
});

// Start carousel on page load
startCarousel();

// Plant Management Functionality
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const addPlantModal = document.getElementById('addPlantModal');
    const viewPlantModal = document.getElementById('viewPlantModal');
    const addPlantBtn = document.getElementById('addPlantBtn');
    const addPlantBtn2 = document.getElementById('addPlantBtn2');
    const closeModal = document.getElementById('closeModal');
    const closeViewModal = document.getElementById('closeViewModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeViewBtn = document.getElementById('closeViewBtn');
    const saveBtn = document.getElementById('saveBtn');
    const plantForm = document.getElementById('plantForm');
    const plantImageInput = document.getElementById('plantImage');
    const imagePreview = document.getElementById('imagePreview');
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const modalTitle = document.getElementById('modalTitle');
    
    // Category containers
    const underwaterContainer = document.getElementById('underwater-plants');
    const gardenContainer = document.getElementById('garden-plants');
    const hangingContainer = document.getElementById('hanging-plants');
    const indoorContainer = document.getElementById('indoor-plants');
    
    // Category counts
    const underwaterCount = document.getElementById('underwater-count');
    const gardenCount = document.getElementById('garden-count');
    const hangingCount = document.getElementById('hanging-count');
    const indoorCount = document.getElementById('indoor-count');
    
    // Load plants on page load
    loadPlants();
    
    // Search functionality
    searchBtn.addEventListener('click', function() {
        loadPlants(searchInput.value);
    });
    
    // Also search when Enter key is pressed
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            loadPlants(searchInput.value);
        }
    });
    
    // Function to load plants from the database
    function loadPlants(search = '') {
        // Clear existing plants
        underwaterContainer.innerHTML = '<div class="loading-plants">Loading plants...</div>';
        gardenContainer.innerHTML = '<div class="loading-plants">Loading plants...</div>';
        hangingContainer.innerHTML = '<div class="loading-plants">Loading plants...</div>';
        indoorContainer.innerHTML = '<div class="loading-plants">Loading plants...</div>';
        
        // Fetch plants from the server
        fetch(`get_plants.php${search ? '?search=' + encodeURIComponent(search) : ''}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update category counts
                    underwaterCount.textContent = data.counts.underwater;
                    gardenCount.textContent = data.counts.garden;
                    hangingCount.textContent = data.counts.hanging;
                    indoorCount.textContent = data.counts.indoor;
                    
                    // Clear containers
                    underwaterContainer.innerHTML = '';
                    gardenContainer.innerHTML = '';
                    hangingContainer.innerHTML = '';
                    indoorContainer.innerHTML = '';
                    
                    // Populate underwater plants
                    if (data.categorized.underwater.length > 0) {
                        data.categorized.underwater.forEach(plant => {
                            underwaterContainer.appendChild(createPlantItem(plant));
                        });
                    } else {
                        underwaterContainer.innerHTML = '<div class="no-plants">No underwater plants found.</div>';
                    }
                    
                    // Populate garden plants
                    if (data.categorized.garden.length > 0) {
                        data.categorized.garden.forEach(plant => {
                            gardenContainer.appendChild(createPlantItem(plant));
                        });
                    } else {
                        gardenContainer.innerHTML = '<div class="no-plants">No garden plants found.</div>';
                    }
                    
                    // Populate hanging plants
                    if (data.categorized.hanging.length > 0) {
                        data.categorized.hanging.forEach(plant => {
                            hangingContainer.appendChild(createPlantItem(plant));
                        });
                    } else {
                        hangingContainer.innerHTML = '<div class="no-plants">No hanging plants found.</div>';
                    }
                    
                    // Populate indoor plants
                    if (data.categorized.indoor.length > 0) {
                        data.categorized.indoor.forEach(plant => {
                            indoorContainer.appendChild(createPlantItem(plant));
                        });
                    } else {
                        indoorContainer.innerHTML = '<div class="no-plants">No indoor plants found.</div>';
                    }
                } else {
                    console.error('Error loading plants:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    
    // Function to create a plant item element
    function createPlantItem(plant) {
        const plantItem = document.createElement('div');
        plantItem.className = 'plant-item';
        plantItem.dataset.id = plant.id;
        
        // Create thumbnail
        const thumbnail = document.createElement('img');
        thumbnail.className = 'plant-thumbnail';
        thumbnail.src = plant.image ? `../uploads/${plant.image}` : 'https://source.unsplash.com/random/100x100/?plant';
        thumbnail.alt = plant.name;
        
        // Create plant info
        const plantInfo = document.createElement('div');
        plantInfo.className = 'plant-info';
        
        const plantName = document.createElement('div');
        plantName.className = 'plant-name';
        plantName.textContent = plant.name;
        
        // Add toxic indicator if plant is toxic
        if (plant.toxicity == 1) {
            const toxicIndicator = document.createElement('span');
            toxicIndicator.className = 'toxic-indicator';
            plantName.appendChild(toxicIndicator);
        }
        
        const scientificName = document.createElement('div');
        scientificName.className = 'plant-scientific';
        scientificName.textContent = plant.scientific_name || '';
        
        plantInfo.appendChild(plantName);
        plantInfo.appendChild(scientificName);
        
        // Create action buttons
        const plantActions = document.createElement('div');
        plantActions.className = 'plant-actions';
        
        const viewBtn = document.createElement('button');
        viewBtn.className = 'plant-action-btn';
        viewBtn.title = 'View Details';
        viewBtn.innerHTML = '<i class="fas fa-eye"></i>';
        viewBtn.addEventListener('click', () => viewPlant(plant.id));
        
        const editBtn = document.createElement('button');
        editBtn.className = 'plant-action-btn';
        editBtn.title = 'Edit Plant';
        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
        editBtn.addEventListener('click', () => editPlant(plant.id));
        
        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'plant-action-btn';
        deleteBtn.title = 'Delete Plant';
        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
        deleteBtn.addEventListener('click', () => deletePlant(plant.id, plant.name));
        
        plantActions.appendChild(viewBtn);
        plantActions.appendChild(editBtn);
        plantActions.appendChild(deleteBtn);
        
        // Assemble plant item
        plantItem.appendChild(thumbnail);
        plantItem.appendChild(plantInfo);
        plantItem.appendChild(plantActions);
        
        return plantItem;
    }
    
    // Function to view plant details
    function viewPlant(id) {
        const viewModalBody = document.getElementById('viewModalBody');
        viewModalBody.innerHTML = '<div class="loading">Loading plant details...</div>';
        
        // Show the modal
        viewPlantModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Fetch plant details
        fetch(`get_plant_details.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const plant = data.plant;
                    
                    // Update modal title
                    document.getElementById('viewModalTitle').textContent = plant.name;
                    
                    // Create plant details HTML
                    let detailsHTML = `
                        <div class="plant-detail-container">
                            <div class="plant-detail-image">
                                <img src="${plant.image ? `../uploads/${plant.image}` : 'https://source.unsplash.com/random/400x300/?plant'}" alt="${plant.name}">
                            </div>
                            <div class="plant-detail-info">
                                <h3>${plant.name}</h3>
                                <p class="scientific-name">${plant.scientific_name || 'No scientific name provided'}</p>
                                
                                <div class="detail-section">
                                    <h4>Category</h4>
                                    <p>${plant.category.charAt(0).toUpperCase() + plant.category.slice(1)}</p>
                                </div>
                                
                                <div class="detail-section">
                                    <h4>Description</h4>
                                    <p>${plant.description || 'No description provided'}</p>
                                </div>
                                
                                <div class="detail-section">
                                    <h4>Care Instructions</h4>
                                    <p>${plant.care_instructions || 'No care instructions provided'}</p>
                                </div>
                                
                                <div class="detail-section">
                                    <h4>Environment</h4>
                                    <p>${plant.environment || 'No environment information provided'}</p>
                                </div>
                                
                                <div class="detail-section">
                                    <h4>Toxicity</h4>
                                    <p>${plant.toxicity == 1 ? '<span class="toxic-tag">Toxic to humans or pets</span>' : 'Not toxic'}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    viewModalBody.innerHTML = detailsHTML;
                } else {
                    viewModalBody.innerHTML = `<div class="error">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                viewModalBody.innerHTML = '<div class="error">Error loading plant details. Please try again.</div>';
            });
    }
    
    // Function to edit a plant
    function editPlant(id) {
        // Reset form
        plantForm.reset();
        imagePreview.innerHTML = `
            <div class="image-preview-placeholder">
                <i class="fas fa-image"></i>
                <p>No image selected</p>
            </div>
        `;
        
        // Update modal title
        modalTitle.textContent = 'Edit Plant';
        
        // Set plant ID
        document.getElementById('plantId').value = id;
        
        // Fetch plant details
        fetch(`get_plant_details.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const plant = data.plant;
                    
                    // Fill form with plant data
                    document.getElementById('plantName').value = plant.name;
                    document.getElementById('scientificName').value = plant.scientific_name || '';
                    document.getElementById('category').value = plant.category;
                    document.getElementById('description').value = plant.description || '';
                    document.getElementById('careInstructions').value = plant.care_instructions || '';
                    document.getElementById('environment').value = plant.environment || '';
                    document.getElementById('toxicity').checked = plant.toxicity == 1;
                    
                    // Show image preview if available
                    if (plant.image) {
                        imagePreview.innerHTML = `<img src="../uploads/${plant.image}" alt="${plant.name}">`;
                    }
                    
                    // Show the modal
                    addPlantModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading plant details. Please try again.');
            });
    }
    
    // Function to delete a plant
    function deletePlant(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"?`)) {
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('delete_plant.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    loadPlants(searchInput.value); // Reload plants
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting plant. Please try again.');
            });
        }
    }
    
    // Open modal
    function openAddModal() {
        // Reset form
        plantForm.reset();
        document.getElementById('plantId').value = '';
        modalTitle.textContent = 'Add New Plant';
        
        imagePreview.innerHTML = `
            <div class="image-preview-placeholder">
                <i class="fas fa-image"></i>
                <p>No image selected</p>
            </div>
        `;
        
        addPlantModal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    // Close modal
    function closeModalFunc() {
        addPlantModal.classList.remove('active');
        document.body.style.overflow = ''; // Enable scrolling
    }
    
    // Close view modal
    function closeViewModalFunc() {
        viewPlantModal.classList.remove('active');
        document.body.style.overflow = ''; // Enable scrolling
    }

    // Event listeners for modal
    addPlantBtn.addEventListener('click', openAddModal);
    addPlantBtn2.addEventListener('click', openAddModal);
    closeModal.addEventListener('click', closeModalFunc);
    cancelBtn.addEventListener('click', closeModalFunc);
    closeViewModal.addEventListener('click', closeViewModalFunc);
    closeViewBtn.addEventListener('click', closeViewModalFunc);

    // Close modal when clicking outside
    addPlantModal.addEventListener('click', (e) => {
        if (e.target === addPlantModal) {
            closeModalFunc();
        }
    });
    
    viewPlantModal.addEventListener('click', (e) => {
        if (e.target === viewPlantModal) {
            closeViewModalFunc();
        }
    });

    // Image preview functionality
    plantImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Plant Preview">`;
            }
            reader.readAsDataURL(file);
        }
    });

    // Save plant functionality
    saveBtn.addEventListener('click', () => {
        const formData = new FormData(plantForm);
        const plantId = document.getElementById('plantId').value;
        
        // Determine if this is an add or update operation
        const url = plantId ? 'update_plant.php' : 'add_plant.php';
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModalFunc();
                loadPlants(searchInput.value); // Reload plants with current search
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving plant. Please try again.');
        });
    });
    
});