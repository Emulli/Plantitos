// Dark mode functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved dark mode preference
    const darkMode = localStorage.getItem('darkMode');
    if (darkMode === 'enabled') {
        document.body.classList.add('dark-mode');
    }

    // Create dark mode toggle button only if it doesn't already exist
    const navLinks = document.querySelector('.nav-links');
    if (navLinks && !document.querySelector('.dark-mode-toggle')) {
        const darkModeToggle = document.createElement('button');
        darkModeToggle.className = 'dark-mode-toggle';
        darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        darkModeToggle.setAttribute('aria-label', 'Toggle dark mode');
        darkModeToggle.setAttribute('title', 'Toggle dark mode');
        navLinks.appendChild(darkModeToggle);

        // Toggle dark mode
        darkModeToggle.addEventListener('click', function() {
            if (document.body.classList.contains('dark-mode')) {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            } else {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
        });

        // Update toggle button icon based on current mode
        if (darkMode === 'enabled') {
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
    }

    // Check system preference for dark mode
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Function to handle system preference changes
    function handleSystemPreference(e) {
        if (!localStorage.getItem('darkMode')) {
            if (e.matches) {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }
        }
    }
    
    // Initial check
    handleSystemPreference(prefersDarkScheme);
    
    // Listen for changes
    prefersDarkScheme.addListener(handleSystemPreference);
}); 