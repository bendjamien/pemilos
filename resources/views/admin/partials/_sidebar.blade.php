<style>
    /* CSS Khusus untuk Komponen Sidebar Baru */
    :root {
        --sidebar-bg: #2c3e50;
        --sidebar-link-color: #ecf0f1;
        --sidebar-link-hover-bg: #34495e;
        --sidebar-link-active-bg: #3498db;
        --sidebar-shadow: rgba(0, 0, 0, 0.1);
    }

    .sidebar {
        background-color: var(--sidebar-bg);
        box-shadow: 2px 0 10px var(--sidebar-shadow);
        transition: all 0.3s ease;
        z-index: 100;
    }

    .sidebar-sticky {
        position: sticky;
        top: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .sidebar-brand {
        font-size: 1.5rem;
        font-weight: 700;
        padding: 1.5rem 1rem;
        text-align: center;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-brand:hover {
        background-color: rgba(0, 0, 0, 0.2);
    }

    .sidebar-brand i {
        margin-right: 0.5rem;
        color: #3498db;
    }

    .sidebar-nav {
        flex-grow: 1;
        padding: 1rem 0;
        overflow-y: auto;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.85rem 1.25rem;
        margin: 0.25rem 1rem;
        color: var(--sidebar-link-color);
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-link i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
        color: #3498db;
    }

    .nav-link:hover {
        background-color: var(--sidebar-link-hover-bg);
        color: #fff;
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: var(--sidebar-link-active-bg);
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .nav-link.active::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background-color: #fff;
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }

    /* Dropdown style */
    .submenu {
        margin-top: 0.5rem;
    }

    .submenu .nav-link {
        padding-left: 3.5rem;
        font-size: 0.9rem;
        color: #bdc3c7;
        margin: 0.1rem 1rem;
    }

    .submenu .nav-link:hover, 
    .submenu .nav-link.active {
        color: #fff;
        background-color: rgba(52, 152, 219, 0.3);
    }

    /* User info di bawah */
    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background-color: rgba(0, 0, 0, 0.1);
    }

    .sidebar-footer .nav-link {
        margin: 0;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
    }

    .sidebar-footer .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: none;
    }

    .sidebar-footer i {
        font-size: 1.5rem;
        color: #3498db;
    }

    /* Animasi untuk dropdown */
    .collapse {
        transition: all 0.3s ease;
    }

    /* Scrollbar styling */
    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
</style>

<aside class="sidebar">
    <div class="sidebar-sticky">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand text-decoration-none">
            <i class="bi bi-box-seam"></i>
            <span>Pemilu OSIS</span>
        </a>

        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ (request()->is('admin/osis-candidates*') || request()->is('admin/mpk-candidates*')) ? 'active' : '' }}" data-bs-toggle="collapse" href="#kandidatSubmenu" role="button" aria-expanded="{{ (request()->is('admin/osis-candidates*') || request()->is('admin/mpk-candidates*')) ? 'true' : 'false' }}">
                    <i class="bi bi-people-fill"></i> Kelola Kandidat
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ (request()->is('admin/osis-candidates*') || request()->is('admin/mpk-candidates*')) ? 'show' : '' }} submenu" id="kandidatSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/osis-candidates*') ? 'active' : '' }}" href="{{ route('admin.osis-candidates.index') }}">
                                <i class="bi bi-person-badge"></i> Kandidat OSIS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/mpk-candidates*') ? 'active' : '' }}" href="{{ route('admin.mpk-candidates.index') }}">
                                <i class="bi bi-person-badge-fill"></i> Kandidat MPK
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/results*') ? 'active' : '' }}" href="{{ route('admin.results.index') }}">
                    <i class="bi bi-pie-chart-fill"></i> Hasil Suara
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="#" class="nav-link text-white">
                <i class="bi bi-person-circle"></i>
                <span>{{ Auth::user()->name }}</span>
                <i class="bi bi-box-arrow-right ms-auto"></i>
            </a>
        </div>
    </div>
</aside>