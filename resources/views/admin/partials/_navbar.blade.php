{{-- Form Logout tersembunyi yang akan dipicu oleh dropdown --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
    :root {
        --navbar-bg: #ffffff;
        --navbar-shadow: rgba(0, 0, 0, 0.1);
        --navbar-hover-bg: #f8f9fa;
        --navbar-text: #2c3e50;
        --navbar-accent: #3498db;
    }

    .navbar-admin {
        background-color: var(--navbar-bg);
        box-shadow: 0 2px 15px var(--navbar-shadow);
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .navbar-admin .navbar-brand {
        color: var(--navbar-text);
        font-size: 1.4rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    #sidebar-toggler {
        background-color: var(--navbar-accent);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
    }

    #sidebar-toggler:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.4);
    }

    #sidebar-toggler:active {
        transform: translateY(0);
    }

    .navbar-admin .nav-link {
        color: var(--navbar-text);
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .navbar-admin .nav-link:hover {
        background-color: var(--navbar-hover-bg);
        color: var(--navbar-accent);
    }

    .navbar-admin .dropdown-toggle::after {
        margin-left: 0.5rem;
        vertical-align: middle;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 0.5rem;
        margin-top: 0.5rem;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dropdown-item {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        color: var(--navbar-text);
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }

    .dropdown-item:hover {
        background-color: var(--navbar-hover-bg);
        color: var(--navbar-accent);
    }

    .dropdown-item i {
        margin-right: 0.75rem;
        color: var(--navbar-accent);
    }

    .navbar-admin .nav-link i {
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }

    /* Efek saat dropdown aktif */
    .navbar-admin .dropdown.show .nav-link {
        background-color: var(--navbar-hover-bg);
        color: var(--navbar-accent);
    }

    /* Animasi untuk tombol toggle */
    #sidebar-toggler i {
        transition: transform 0.3s ease;
    }

    #sidebar-toggler.collapsed i {
        transform: rotate(90deg);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-admin shadow-sm">
    <div class="container-fluid">
        <!-- Tombol Toggle Sidebar BARU -->
        <button class="btn btn-light me-3" type="button" id="sidebar-toggler">
            <i class="bi bi-list fs-5"></i>
        </button>

        {{-- Judul Halaman Dinamis --}}
        <span class="navbar-brand mb-0 h1 fw-bold">@yield('title', 'Admin Panel')</span>

        {{-- Dropdown Pengguna di Sebelah Kanan --}}
        <div class="ms-auto">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="bi bi-person-fill text-white"></i>
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>Logout
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-gear"></i>Pengaturan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-question-circle"></i>Bantuan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggler = document.getElementById('sidebar-toggler');
        const sidebar = document.querySelector('.sidebar');
        
        // Toggle sidebar collapse
        sidebarToggler.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            sidebarToggler.classList.toggle('collapsed');
        });
    });
</script>