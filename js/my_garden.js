document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const addPlantModal = document.getElementById('addPlantModal');
    const addPlantBtn = document.getElementById('addPlantBtn');
    const closeAddPlantModal = document.getElementById('closeAddPlantModal');
    const addPlantForm = document.getElementById('addPlantForm');
    const plantSelect = document.getElementById('plantSelect');
    
    // Create image preview element
    const imagePreview = document.createElement('div');
    imagePreview.className = 'plant-image-preview';
    imagePreview.innerHTML = '<i class="fas fa-leaf"></i><p>Select a plant to see preview</p>';
    
    // Insert after the plant select
    plantSelect.parentNode.insertBefore(imagePreview, plantSelect.nextSibling);
    
    // Update image preview when plant is selected
    plantSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const imagePath = selectedOption.getAttribute('data-image');
        
        if (imagePath) {
            // Try multiple possible image paths
            const possiblePaths = [
                'uploads/' + imagePath,
                'admin/uploads/' + imagePath,
                '../uploads/' + imagePath
            ];
            
            // Set the first path as the source
            imagePreview.innerHTML = `<img src="${possiblePaths[0]}" alt="Plant preview" onerror="this.onerror=null; this.src='img/plants/default-plant.jpg'; this.parentNode.innerHTML='<i class=\'fas fa-leaf\'></i><p>Image not available</p>';">`;
        } else {
            imagePreview.innerHTML = '<i class="fas fa-leaf"></i><p>No image available</p>';
        }
    });

    // Open modal when clicking the Add Plant button
    addPlantBtn.addEventListener('click', function() {
        addPlantModal.classList.add('active');
    });

    // Close modal when clicking the close button
    closeAddPlantModal.addEventListener('click', function() {
        addPlantModal.classList.remove('active');
    });

    // Close modal when clicking outside
    addPlantModal.addEventListener('click', function(e) {
        if (e.target === addPlantModal) {
            addPlantModal.classList.remove('active');
        }
    });

    // Handle form submission
    addPlantForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(addPlantForm);
        
        // Send AJAX request to add plant
        fetch('add_plant_to_garden.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to show the new plant
                window.location.reload();
            } else {
                alert('Error adding plant: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the plant.');
        });
    });

    // Profile image upload functionality
    const profileImageInput = document.getElementById('profileImageInput');
    const profileImage = document.getElementById('profileImage');
    
    if (profileImageInput && profileImage) {
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('profile_image', file);
                
                // Show loading state
                profileImage.style.opacity = '0.5';
                
                fetch('upload_profile_image.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update profile image
                        profileImage.src = '../' + data.image_url;
                        profileImage.style.opacity = '1';
                        
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'success-message';
                        successMessage.textContent = 'Profile image updated successfully!';
                        document.querySelector('.profile-container').insertBefore(
                            successMessage,
                            document.querySelector('.profile-image-section')
                        );
                        
                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            successMessage.remove();
                        }, 3000);
                    } else {
                        // Show error message
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message';
                        errorMessage.textContent = data.message || 'Error uploading image';
                        document.querySelector('.profile-container').insertBefore(
                            errorMessage,
                            document.querySelector('.profile-image-section')
                        );
                        
                        // Remove error message after 3 seconds
                        setTimeout(() => {
                            errorMessage.remove();
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'error-message';
                    errorMessage.textContent = 'Error uploading image';
                    document.querySelector('.profile-container').insertBefore(
                        errorMessage,
                        document.querySelector('.profile-image-section')
                    );
                    
                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        errorMessage.remove();
                    }, 3000);
                })
                .finally(() => {
                    profileImage.style.opacity = '1';
                });
            }
        });
    }
});

// Function to mark care action (water, mist, fertilize)
function markCare(plantId, careType) {
    // Show loading state
    const plantCard = document.querySelector(`[data-plant-id="${plantId}"]`);
    if (plantCard) {
        const careItem = plantCard.querySelector(`.care-item:nth-child(${careType === 'water' ? 1 : careType === 'mist' ? 2 : 3})`);
        if (careItem) {
            const originalContent = careItem.innerHTML;
            careItem.classList.add('loading');
            careItem.innerHTML = `<i class="fas fa-spinner fa-spin"></i> <span>Updating...</span>`;
            
            fetch('mark_care.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    plant_id: plantId,
                    care_type: careType
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Reload the page to update the care status
                    window.location.reload();
                } else {
                    // Restore original content and show error
                    careItem.classList.remove('loading');
                    careItem.innerHTML = originalContent;
                    alert(data.message || 'An error occurred while updating the care status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore original content and show error
                careItem.classList.remove('loading');
                careItem.innerHTML = originalContent;
                alert('An error occurred while updating the care status. Please try again.');
            });
        }
    }
}

// Function to remove a plant from the garden
function removePlant(plantId) {
    if (confirm('Are you sure you want to remove this plant from your garden?')) {
        // Show loading state
        const plantCard = document.querySelector(`[data-plant-id="${plantId}"]`);
        if (plantCard) {
            plantCard.style.opacity = '0.5';
            plantCard.style.pointerEvents = 'none';
        }
        
        fetch('remove_plant.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                plant_id: plantId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate the removal
                if (plantCard) {
                    plantCard.style.transition = 'all 0.5s ease';
                    plantCard.style.transform = 'scale(0.8)';
                    plantCard.style.opacity = '0';
                    
                    setTimeout(() => {
                        plantCard.remove();
                        
                        // If no plants left, show empty garden message
                        const gardenGrid = document.getElementById('gardenGrid');
                        if (gardenGrid.children.length === 0) {
                            gardenGrid.innerHTML = `
                                <div class="empty-garden">
                                    <i class="fas fa-seedling"></i>
                                    <p>Your garden is empty. Add some plants to get started!</p>
                                </div>
                            `;
                        }
                    }, 500);
                }
            } else {
                // Reset the card if there was an error
                if (plantCard) {
                    plantCard.style.opacity = '1';
                    plantCard.style.pointerEvents = 'auto';
                }
                alert('Error removing plant: ' + data.message);
            }
        })
        .catch(error => {
            // Reset the card if there was an error
            if (plantCard) {
                plantCard.style.opacity = '1';
                plantCard.style.pointerEvents = 'auto';
            }
            console.error('Error:', error);
            alert('An error occurred while removing the plant.');
        });
    }
}

// Function to view plant details
function viewPlantDetails(plantId) {
    // Get modal elements
    const plantDetailsModal = document.getElementById('plantDetailsModal');
    const plantDetailsTitle = document.getElementById('plantDetailsTitle');
    const plantDetailsBody = document.getElementById('plantDetailsBody');
    const closePlantDetailsModal = document.getElementById('closePlantDetailsModal');
    
    // Show loading state
    plantDetailsBody.innerHTML = `
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading plant details...</p>
        </div>
    `;
    
    // Show modal
    plantDetailsModal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Fetch plant details
    fetch(`get_plant_details.php?id=${plantId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const plant = data.plant;
                
                // Update modal title
                plantDetailsTitle.textContent = plant.name;
                
                // Try multiple possible image paths
                const possiblePaths = [
                    plant.image_url,
                    plant.image ? "uploads/" + plant.image : null,
                    plant.image ? "admin/uploads/" + plant.image : null,
                    plant.image ? "../uploads/" + plant.image : null,
                    "img/plants/default-plant.jpg"
                ];
                
                // Filter out null paths
                const validPaths = possiblePaths.filter(path => path !== null);
                
                // Get category name
                const categoryName = getCategoryName(plant.category);
                
                // Fetch user's garden plant data to get notes
                fetch(`get_garden_plant.php?plant_id=${plantId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(gardenData => {
                        // Create plant details HTML
                        const detailsHTML = `
                            <div class="plant-detail-container">
                                <div class="plant-detail-image">
                                    <img src="${validPaths[0]}" alt="${plant.name}" 
                                        onerror="if (this.src !== '${validPaths[validPaths.length - 1]}') this.src = '${validPaths[validPaths.length - 1]}';">
                                    <span class="category-badge">${categoryName}</span>
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
                                    
                                    ${gardenData.success && gardenData.garden_plant.notes ? `
                                    <div class="detail-section">
                                        <h4>My Notes</h4>
                                        <p>${gardenData.garden_plant.notes}</p>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                        
                        plantDetailsBody.innerHTML = detailsHTML;
                    })
                    .catch(error => {
                        console.error("Error fetching garden plant data:", error);
                        // Still show plant details even if garden plant data fails
                        const detailsHTML = `
                            <div class="plant-detail-container">
                                <div class="plant-detail-image">
                                    <img src="${validPaths[0]}" alt="${plant.name}" 
                                        onerror="if (this.src !== '${validPaths[validPaths.length - 1]}') this.src = '${validPaths[validPaths.length - 1]}';">
                                    <span class="category-badge">${categoryName}</span>
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
                        `;
                        
                        plantDetailsBody.innerHTML = detailsHTML;
                    });
            } else {
                plantDetailsBody.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>${data.message || "Error loading plant details."}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            plantDetailsBody.innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Error loading plant details. Please try again.</p>
                </div>
            `;
        });
    
    // Close modal when clicking the close button
    closePlantDetailsModal.addEventListener('click', function() {
        plantDetailsModal.classList.remove('active');
        document.body.style.overflow = '';
    });
    
    // Close modal when clicking outside
    plantDetailsModal.addEventListener('click', function(e) {
        if (e.target === plantDetailsModal) {
            plantDetailsModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

// Helper function to get category name
function getCategoryName(category) {
    const categories = {
        'indoor': 'Indoor Plant',
        'outdoor': 'Outdoor Plant',
        'succulent': 'Succulent',
        'herb': 'Herb',
        'vegetable': 'Vegetable',
        'fruit': 'Fruit',
        'flower': 'Flower',
        'tree': 'Tree',
        'shrub': 'Shrub',
        'other': 'Other'
    };
    
    return categories[category] || 'Plant';
} 