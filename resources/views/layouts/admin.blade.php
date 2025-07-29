<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --sidebar-bg: #212529;
            --sidebar-link-color: #adb5bd;
            --sidebar-link-hover-bg: #343a40;
            --sidebar-link-active-bg: #495057;
        }
        body {
            background-color: #f8f9fa;
        }
        .main-wrapper {
            display: flex;
            transition: margin-left 0.3s ease;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            transition: margin-left 0.3s ease;
        }
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            padding: 2rem;
        }
        .navbar-admin {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }

        /* Style untuk Sidebar Collapsed (Desktop) */
        @media (min-width: 992px) {
            body.sidebar-collapsed .sidebar {
                margin-left: -260px;
            }
        }
        
        /* Style untuk Sidebar Off-canvas (Mobile) */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1050;
                margin-left: -260px; /* Sembunyi secara default */
            }
            body.sidebar-visible .sidebar {
                margin-left: 0; /* Tampilkan saat dibutuhkan */
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 1040;
                display: none;
            }
            body.sidebar-visible .sidebar-overlay {
                display: block;
            }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <!-- Sidebar -->
    @include('admin.partials._sidebar')

    <div class="content-wrapper">
        <!-- Navbar -->
        @include('admin.partials._navbar')

        <!-- Konten Utama Halaman -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>
</div>

<!-- Overlay untuk mobile -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>


<!-- Script untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script untuk Toggle Sidebar -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarToggler = document.getElementById('sidebar-toggler');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        const toggleSidebar = () => {
            if (window.innerWidth < 992) {
                document.body.classList.toggle('sidebar-visible');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
            }
        };

        if (sidebarToggler) {
            sidebarToggler.addEventListener('click', toggleSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
    });
</script>

<!-- Script untuk menandai link aktif di sidebar -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentUrl = window.location.href;
        const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');

        sidebarLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
                const collapseElement = link.closest('.collapse');
                if (collapseElement) {
                    new bootstrap.Collapse(collapseElement, { toggle: true });
                }
            }
        });
    });
</script>
</body>
</html>
