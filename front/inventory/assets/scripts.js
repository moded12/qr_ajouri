// assets/scripts.js

document.addEventListener('DOMContentLoaded', () => {
    const backToTopButton = document.getElementById('backToTop');
    const body = document.body;
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.querySelector('.toggle-button');
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const mainFrame = document.getElementById('mainFrame');
    const sidebarLinks = document.querySelectorAll('.sidebar a[data-page]');
    const contentArea = document.querySelector('.content');

    // Define the mobile breakpoint (should match the CSS media query)
    const mobileBreakpoint = 768; // in pixels

    // --- Scroll to Top Button Logic ---
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // --- Sidebar Toggle Logic (Mobile Only) ---
    function toggleSidebar() {
        if (window.innerWidth < mobileBreakpoint) {
            sidebar.classList.toggle('open');
            // Optional: Add/remove overlay class
            // body.classList.toggle('sidebar-overlay');
        }
    }

    function closeSidebar() {
        if (window.innerWidth < mobileBreakpoint && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            // Optional: Remove overlay class
            // body.classList.remove('sidebar-overlay');
        }
    }

    if (toggleButton) {
         toggleButton.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleSidebar();
        });
    }

    if (contentArea) {
        contentArea.addEventListener('click', (event) => {
           if (window.innerWidth < mobileBreakpoint && sidebar.classList.contains('open') && !sidebar.contains(event.target)) {
               closeSidebar();
           }
        });
    }

    // --- Sidebar Menu Item Selection ---
    sidebarLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            const page = link.getAttribute('data-page');
            if (page) {
                 event.preventDefault();
                 if (mainFrame) {
                    mainFrame.src = page;
                    sidebarLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                }
            }

            if (window.innerWidth < mobileBreakpoint) {
                 closeSidebar();
            }
        });
    });

    // Highlight the initial active link based on iframe src
    const initialPage = mainFrame.getAttribute('src');
     if (initialPage) {
         sidebarLinks.forEach(link => {
             const linkPage = link.getAttribute('data-page');
             if (linkPage && initialPage.includes(linkPage)) {
                 link.classList.add('active');
             }
         });
     }


    // --- Dark Mode Toggle Logic ---
    function toggleDarkMode() {
        body.classList.toggle('dark-mode');
        // Removed: sidebar.classList.toggle('dark-mode'); // Let CSS handle sidebar dark mode based on body class

        // Update button icon
        const icon = darkModeToggle.querySelector('i');
        if (body.classList.contains('dark-mode')) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
             darkModeToggle.setAttribute('aria-label', 'Toggle Light Mode');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
             darkModeToggle.setAttribute('aria-label', 'Toggle Dark Mode');
        }

        // Save preference
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    }

     if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
     }

    // Apply saved dark mode preference on load
    if (localStorage.getItem('darkMode') === 'enabled') {
        if (!body.classList.contains('dark-mode')) {
             toggleDarkMode();
        }
    }


    // --- Window Resize Handling ---
    function handleResize() {
        if (window.innerWidth >= mobileBreakpoint) {
             sidebar.classList.remove('open');
             // Optional: Remove mobile overlay class on resize to desktop
             // body.classList.remove('sidebar-overlay');
        }
    }

    handleResize();
    window.addEventListener('resize', handleResize);

});