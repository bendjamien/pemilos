@extends('layouts.admin')

@section('title', 'Kelola Kandidat OSIS')

@section('content')
<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #224abe;
        --danger-color: #e74a3b;
        --warning-color: #f6c23e;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --light-bg: #f8f9fc;
        --card-shadow: 0 4px 15px rgba(0,0,0,0.07);
    }

    .page-header {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        padding: 1.25rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
    }

    .candidate-grid-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        max-height: 450px; /* Batasi tinggi maksimum */
    }

    .candidate-grid-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .card-header-bg {
        height: 80px; /* Diperkecil dari 100px */
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        position: relative;
        overflow: hidden;
    }

    .card-header-bg::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(45deg);
    }

    .photo-container {
        margin-top: -40px; /* Diperkecil dari -50px */
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .candidate-photo {
        width: 70px; /* Diperkecil dari 80px */
        height: 70px; /* Diperkecil dari 80px */
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff; /* Diperkecil dari 4px */
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .candidate-photo:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 12px rgba(0,0,0,0.2);
    }

    .candidate-photo.wakil {
        margin-left: -20px; /* Diperkecil dari -25px */
        z-index: 1;
    }

    .candidate-photo.wakil:hover {
        transform: scale(1.05) translateX(5px);
        z-index: 3;
    }

    .candidate-badge {
        position: absolute;
        top: 8px; /* Diperkecil dari 10px */
        right: 8px; /* Diperkecil dari 10px */
        background-color: rgba(255,255,255,0.9);
        color: var(--primary-color);
        padding: 0.2rem 0.6rem; /* Diperkecil */
        border-radius: 20px;
        font-size: 0.7rem; /* Diperkecil */
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .candidate-info {
        padding: 1rem; /* Diperkecil dari 1.5rem */
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .candidate-name {
        font-size: 1.1rem; /* Diperkecil dari 1.25rem */
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #2c3e50;
    }

    .candidate-pair {
        font-size: 0.9rem; /* Diperkecil dari 1rem */
        color: #6c757d;
        margin-bottom: 0.75rem; /* Diperkecil dari 1rem */
    }

    .candidate-vision {
        background-color: var(--light-bg);
        border-radius: 8px;
        padding: 0.75rem; /* Diperkecil dari 1rem */
        margin-bottom: 0.75rem; /* Diperkecil dari 1rem */
        flex-grow: 1;
        border-left: 4px solid var(--primary-color);
    }

    .candidate-vision h6 {
        font-size: 0.8rem; /* Diperkecil dari 0.85rem */
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.4rem; /* Diperkecil dari 0.5rem */
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .candidate-vision p {
        font-size: 0.85rem; /* Diperkecil dari 0.9rem */
        color: #5a5c69;
        margin-bottom: 0;
        line-height: 1.4; /* Diperkecil dari 1.5 */
    }

    .candidate-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 1rem; /* Diperkecil dari 0.75rem 1.5rem */
        background-color: var(--light-bg);
        border-top: 1px solid #e3e6f0;
    }

    .vote-count {
        font-size: 0.85rem; /* Diperkecil dari 0.9rem */
        font-weight: 600;
        color: #5a5c69;
    }

    .vote-count i {
        color: var(--success-color);
        margin-right: 0.25rem;
    }

    .card-footer {
        background-color: white;
        border-top: 1px solid #e3e6f0;
        padding: 0.75rem 1rem; /* Diperkecil dari 1rem 1.5rem */
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-action {
        border-radius: 6px; /* Diperkecil dari 8px */
        padding: 0.4rem 0.8rem; /* Diperkecil dari 0.5rem 1rem */
        font-weight: 500;
        font-size: 0.85rem; /* Diperkecil */
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem; /* Diperkecil dari 0.5rem */
    }

    .btn-edit {
        background-color: var(--warning-color);
        border-color: var(--warning-color);
        color: white;
    }

    .btn-edit:hover {
        background-color: #dda20a;
        border-color: #dda20a;
        color: white;
        transform: translateY(-2px);
    }

    .btn-delete {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
        color: white;
    }

    .btn-delete:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: white;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 2rem; /* Diperkecil dari 3rem */
        background-color: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
    }

    .empty-state i {
        font-size: 3rem; /* Diperkecil dari 4rem */
        color: #d1d3e2;
        margin-bottom: 1rem; /* Diperkecil dari 1.5rem */
    }

    .empty-state h5 {
        font-size: 1.3rem; /* Diperkecil dari 1.5rem */
        font-weight: 700;
        color: #5a5c69;
        margin-bottom: 0.75rem; /* Diperkecil dari 1rem */
    }

    .empty-state p {
        font-size: 0.95rem; /* Diperkecil dari 1rem */
        color: #858796;
        margin-bottom: 1.5rem; /* Diperkecil dari 2rem */
    }

    .alert {
        border-radius: 8px;
        border: none;
        padding: 0.8rem 1.2rem; /* Diperkecil dari 1rem 1.5rem */
        margin-bottom: 1.2rem; /* Diperkecil dari 1.5rem */
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert i {
        margin-right: 0.5rem;
    }

    @media (max-width: 768px) {
        .candidate-photo {
            width: 60px; /* Diperkecil dari 80px */
            height: 60px; /* Diperkecil dari 80px */
        }
        
        .candidate-photo.wakil {
            margin-left: -15px; /* Diperkecil dari -20px */
        }
        
        .candidate-grid-card {
            max-height: 420px; /* Sesuaikan untuk mobile */
        }
    }
</style>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0">Daftar Kandidat OSIS</h2>
            <p class="mb-0 opacity-75">Kelola kandidat untuk pemilihan OSIS</p>
        </div>
        <a href="{{ route('admin.osis-candidates.create') }}" class="btn btn-light rounded-pill">
            <i class="bi bi-plus-circle me-2"></i>Tambah Kandidat
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
    </div>
@endif

<div class="row">
    @forelse($candidates as $candidate)
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="candidate-grid-card">
            <div class="card-header-bg">
                <div class="candidate-badge">OSIS</div>
            </div>
            <div class="candidate-info">
                <div class="photo-container">
                    <img src="{{ asset('storage/' . $candidate->photo_ketua) }}" class="candidate-photo ketua" alt="Foto Ketua">
                    <img src="{{ asset('storage/' . $candidate->photo_wakil) }}" class="candidate-photo wakil" alt="Foto Wakil">
                </div>
                <h5 class="candidate-name">{{ $candidate->name_ketua }}</h5>
                <p class="candidate-pair">& {{ $candidate->name_wakil }}</p>
                <div class="candidate-vision">
                    <h6>Visi</h6>
                    <p>{{ \Illuminate\Support\Str::limit($candidate->vision, 100) }}</p>
                </div>
            </div>
            <div class="candidate-stats">
                <div class="vote-count">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ $candidate->votes_count ?? 0 }} Suara
                </div>
                <div>
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-person-check me-1"></i> {{ $candidate->votes_count ?? 0 }} Pemilih
                    </span>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.osis-candidates.edit', $candidate->id) }}" class="btn btn-action btn-edit">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <form action="{{ route('admin.osis-candidates.destroy', $candidate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kandidat ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-action btn-delete">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="bi bi-people-fill"></i>
            <h5>Belum Ada Kandidat OSIS</h5>
            <p>Silakan tambahkan kandidat OSIS untuk memulai pemilihan.</p>
            <a href="{{ route('admin.osis-candidates.create') }}" class="btn btn-primary rounded-pill">
                <i class="bi bi-plus-circle me-2"></i> Tambah Kandidat Sekarang
            </a>
        </div>
    </div>
    @endforelse
</div>
@endsection