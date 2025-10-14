<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - PEMILOS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* === GLOBAL & LAYOUT === */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-bg: #f8f9fc;
            --dark-bg: #5a5c69;
            --card-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-bg);
            overflow-x: hidden;
        }

        .main-wrapper { display: flex; min-height: 100vh; }
        .content-wrapper { flex-grow: 1; width: 100%; transition: margin-left 0.3s ease; }
        .main-content { padding: 1.5rem; }
        .card { border: none; border-radius: 12px; box-shadow: var(--card-shadow); margin-bottom: 1.5rem; }
        .card-header { background-color: transparent; border-bottom: 1px solid #e3e6f0; padding: 1rem 1.5rem; font-weight: 600; }
        .btn { border-radius: 8px; padding: 0.5rem 1rem; font-weight: 500; }

        /* === LOADER === */
        .loader-container {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex; justify-content: center; align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        .loader {
            width: 50px; height: 50px; border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* === SIDEBAR === */
        .sidebar {
            width: 260px; min-width: 260px; height: 100vh;
            background-color: #2c3e50; /* Warna dari sidebar baru Anda */
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            z-index: 100;
            display: flex; flex-direction: column;
            transition: margin-left 0.3s ease;
        }
        .sidebar .sidebar-brand {
            height: 60px; display: flex; align-items: center;
            padding: 0 1.5rem; background-color: rgba(0,0,0,0.2);
            color: white; font-weight: 700; font-size: 1.2rem;
        }
        .sidebar .sidebar-brand i { margin-right: 0.75rem; color: var(--primary-color); }
        .sidebar .nav-link {
            display: flex; align-items: center; padding: 0.85rem 1.25rem;
            margin: 0.25rem 1rem; color: #ecf0f1; border-radius: 0.5rem;
            text-decoration: none; transition: all 0.2s ease;
        }
        .sidebar .nav-link i { margin-right: 0.75rem; width: 24px; text-align: center; }
        .sidebar .nav-link:hover { color: white; background-color: #34495e; transform: translateX(5px); }
        .sidebar .nav-link.active { color: white; background-color: var(--primary-color); font-weight: 600; }
        .sidebar .nav-dropdown { padding-left: 1.5rem; background-color: rgba(0,0,0,0.2); }
        .sidebar .nav-dropdown .nav-link { padding-left: 2rem; font-size: 0.9rem; margin: 0.1rem 1rem; color: #bdc3c7; }
        .sidebar .nav-dropdown .nav-link:hover, .sidebar .nav-dropdown .nav-link.active { color: #fff; background-color: rgba(52, 152, 219, 0.3); }
        .sidebar .sidebar-footer { margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1); }

        /* === NAVBAR (Digabung dari kode baru Anda) === */
        .navbar-admin {
            background-color: #ffffff;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: sticky; top: 0; z-index: 1020;
        }
        #sidebar-toggler {
            background-color: var(--primary-color); color: white; border: none;
            border-radius: 8px; padding: 0.5rem 0.75rem; transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
        }
        #sidebar-toggler:hover { background-color: var(--secondary-color); transform: translateY(-2px); }
        .navbar-admin .nav-link { font-weight: 500; }
        .navbar-admin .dropdown-menu {
            border: none; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px; padding: 0.5rem; margin-top: 0.5rem;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .dropdown-item { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 500; }
        .dropdown-item i { margin-right: 0.75rem; color: var(--primary-color); }
        #sidebar-toggler i { transition: transform 0.3s ease; }
        #sidebar-toggler.collapsed i { transform: rotate(180deg); }

        /* === RESPONSIVE & SIDEBAR TOGGLING === */
        @media (min-width: 992px) {
            body.sidebar-collapsed .sidebar {
                margin-left: -260px;
            }
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed; margin-left: -260px;
            }
            body.sidebar-visible .sidebar {
                margin-left: 0;
            }
            .sidebar-overlay {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background-color: rgba(0,0,0,0.5);
                z-index: 99; display: none;
            }
            body.sidebar-visible .sidebar-overlay {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="sidebar-collapsed"> <div class="loader-container" id="loader">
    <div class="loader"></div>
</div>

<div class="main-wrapper">
    @include('admin.partials._sidebar')

    <div class="content-wrapper">
        @include('admin.partials._navbar')

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</div>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('loader');
                if (loader) {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 500);
                }
            }, 500);
        });

        // Sidebar Toggling Logic
        const sidebarToggler = document.getElementById('sidebar-toggler');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        const toggleSidebar = () => {
            if (window.innerWidth < 992) { // Mobile view
                document.body.classList.toggle('sidebar-visible');
            } else { // Desktop view
                document.body.classList.toggle('sidebar-collapsed');
            }
            sidebarToggler.classList.toggle('collapsed');
        };

        if (sidebarToggler) {
            sidebarToggler.addEventListener('click', toggleSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }

        // Initialize sidebar state based on screen size
        if (window.innerWidth < 992) {
           document.body.classList.add('sidebar-collapsed');
           sidebarToggler.classList.add('collapsed');
        } else {
           document.body.classList.remove('sidebar-collapsed');
           sidebarToggler.classList.remove('collapsed');
        }
    });
</script>

@stack('scripts')
</body>
</html>