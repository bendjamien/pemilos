<style>
    /* CSS Khusus untuk Komponen Sidebar Baru */
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
    }
    .sidebar-nav {
        flex-grow: 1;
        padding: 0 1rem;
    }
    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        color: var(--sidebar-link-color);
        border-radius: 0.5rem;
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    .nav-link i {
        margin-right: 0.75rem;
        font-size: 1.1rem;
        width: 20px; /* Jaga ikon tetap sejajar */
        text-align: center;
    }
    .nav-link:hover {
        background-color: var(--sidebar-link-hover-bg);
        color: #fff;
    }
    .nav-link.active {
        background-color: var(--sidebar-link-active-bg);
        color: #fff;
        font-weight: 600;
    }
    /* Dropdown style */
    .submenu .nav-link {
        padding-left: 2.75rem; /* Indentasi untuk submenu */
        font-size: 0.9rem;
        color: #8f9ca8;
    }
    .submenu .nav-link:hover, .submenu .nav-link.active {
        color: #fff;
    }
    /* User info di bawah */
    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid #343a40;
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
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#kandidatSubmenu" role="button">
                    <i class="bi bi-people-fill"></i> Kelola Kandidat
                </a>
                <div class="collapse submenu" id="kandidatSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.osis-candidates.index') }}">Kandidat OSIS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.mpk-candidates.index') }}">Kandidat MPK</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.results.index') }}">
                    <i class="bi bi-pie-chart-fill"></i> Hasil Suara
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            {{-- Ini akan otomatis mengambil nama user yang sedang login --}}
            <a href="#" class="nav-link text-white">
                <i class="bi bi-person-circle"></i>
                <span>{{ Auth::user()->name }}</span>
            </a>
        </div>
    </div>
</aside>
