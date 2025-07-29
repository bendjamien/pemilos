{{-- Form Logout tersembunyi yang akan dipicu oleh dropdown --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<nav class="navbar navbar-expand-lg navbar-admin shadow-sm">
    <div class="container-fluid">
        <!-- Tombol Toggle Sidebar BARU -->
        <button class="btn btn-light me-2" type="button" id="sidebar-toggler">
            <i class="bi bi-list fs-5"></i>
        </button>

        {{-- Judul Halaman Dinamis --}}
        <span class="navbar-brand mb-0 h1 fw-bold">@yield('title', 'Admin Panel')</span>

        {{-- Dropdown Pengguna di Sebelah Kanan --}}
        <div class="ms-auto">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
