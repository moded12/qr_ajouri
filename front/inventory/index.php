<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام إدارة المخزون</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/style.css">

</head>
<body>

    <div class="header">
        <button class="toggle-button d-md-none" aria-label="Toggle Sidebar">☰</button>
        <div class="title">
            <a href="https://www.shneler.com/qr_ajouri/front/inventory/index.php">📦 نظام إدارة المخزون</a>
        </div>
        <button class="dark-mode-toggle" aria-label="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
    </div>

    <div class="sidebar">
        <div class="sidebar-header d-none d-md-flex">
             <h4>القائمة الرئيسية</h4>
        </div>
        <a href="#" data-page="add_item.php"><i class="fas fa-plus"></i> إضافة صنف</a>
        <a href="#" data-page="remove_item.php"><i class="fas fa-minus"></i> إخراج صنف</a>
        <a href="#" data-page="list_items.php"><i class="fas fa-list"></i> عرض الأصناف</a>
        <a href="#" data-page="search.php"><i class="fas fa-search"></i> البحث</a>
        <a href="#" data-page="logs.php"><i class="fas fa-file-alt"></i> سجل الحركات</a>
        <a href="#" data-page="print_list.php"><i class="fas fa-print"></i> طباعة</a>
        <a href="#" data-page="set_thresholds.php"><i class="fas fa-cogs"></i> حدود التنبيه</a>
        <a href="#" data-page="notify_settings.php"><i class="fas fa-bell"></i> إعدادات التنبيهات</a>
        <a href="/qr_ajouri/accounting/index.php"><i class="fas fa-coins"></i> قسم المحاسبة</a>
    </div>

    <div class="content">
        <iframe src="list_items.php" id="mainFrame"></iframe>
    </div>

    <button id="backToTop" aria-label="Back to Top"><i class="fas fa-arrow-up"></i></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // assets/scripts.js content starts here
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
        // assets/scripts.js content ends here
    </script>

</body>
</html>