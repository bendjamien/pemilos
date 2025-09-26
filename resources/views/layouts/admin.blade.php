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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #224abe;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-bg: #f8f9fc;
            --dark-bg: #5a5c69;
            --sidebar-bg: #212529;
            --sidebar-link-color: #adb5bd;
            --sidebar-link-hover-bg: #343a40;
            --sidebar-link-active-bg: #495057;
            --card-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-bg);
            overflow-x: hidden;
        }

        /* Loading Spinner */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Main Layout */
        .main-wrapper {
            display: flex;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            transition: all 0.3s ease;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar .sidebar-brand {
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            background-color: rgba(0,0,0,0.1);
            text-decoration: none;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .sidebar .sidebar-brand i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .sidebar .nav-item {
            position: relative;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--sidebar-link-color);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: var(--sidebar-link-hover-bg);
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: var(--primary-color);
        }

        .sidebar .nav-link.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: white;
        }

        .sidebar .nav-dropdown .nav-link {
            padding-left: 3rem;
            font-size: 0.9rem;
        }

        .sidebar .nav-dropdown .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar .nav-dropdown .nav-link.active {
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar .sidebar-footer .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        /* Content Wrapper */
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Navbar */
        .navbar-admin {
            background-color: white;
            border-bottom: 1px solid #e3e6f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 0.75rem 1.5rem;
        }

        .navbar-admin .navbar-brand {
            font-weight: 700;
            color: var(--dark-bg);
        }

        .navbar-admin .nav-link {
            color: var(--dark-bg);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .navbar-admin .nav-link:hover {
            background-color: var(--light-bg);
        }

        .navbar-admin .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 8px;
            padding: 0.5rem;
        }

        .navbar-admin .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .navbar-admin .dropdown-item:hover {
            background-color: var(--light-bg);
        }

        .navbar-admin .dropdown-item i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .page-header p {
            margin-bottom: 0;
            opacity: 0.8;
        }

        /* Custom Components */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.5rem;
        }

        .card-header h5 {
            margin-bottom: 0;
            font-weight: 600;
            color: var(--dark-bg);
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.25rem;
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--light-bg);
            border-bottom: 2px solid #e3e6f0;
            font-weight: 600;
            color: var(--dark-bg);
        }

        /* Sidebar Collapsed (Desktop) */
        @media (min-width: 992px) {
            body.sidebar-collapsed .sidebar {
                margin-left: -260px;
            }
            
            body.sidebar-collapsed .content-wrapper {
                margin-left: 0;
            }
        }
        
        /* Sidebar Off-canvas (Mobile) */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1050;
                margin-left: -260px;
            }
            
            body.sidebar-visible .sidebar {
                margin-left: 0;
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
            
            .main-content {
                padding: 1rem;
            }
            
            .page-header {
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Loading Spinner -->
<div class="loader-container" id="loader">
    <div class="loader"></div>
</div>

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

<!-- Custom JS -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('loader');
                loader.style.opacity = '0';
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500);
            }, 500);
        });

        // Toggle Sidebar
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

        // Mark active sidebar link
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

        // Add smooth scrolling for all links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>