@extends('layouts.admin')

@section('title', 'Kelola Kandidat MPK')

@section('content')
<style>
    .candidate-grid-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .candidate-grid-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .card-header-bg {
        height: 100px;
        background: linear-gradient(45deg, #36b9cc, #2a96a5); /* Warna berbeda untuk MPK */
        border-radius: 12px 12px 0 0;
    }
    .photo-container {
        margin-top: -50px; /* Membuat foto naik ke atas header */
        display: flex;
        justify-content: center;
    }
    .candidate-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    .candidate-photo.wakil {
        margin-left: -25px;
    }
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Daftar Kandidat MPK</h4>
    <a href="{{ route('admin.mpk-candidates.create') }}" class="btn btn-primary rounded-pill">
        <i class="bi bi-plus-circle me-2"></i>Tambah Kandidat
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    @forelse($candidates as $candidate)
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card candidate-grid-card h-100">
            <div class="card-header-bg"></div>
            <div class="card-body text-center pt-0">
                <div class="photo-container">
                    <img src="{{ asset('storage/' . $candidate->photo_ketua) }}" class="candidate-photo ketua" alt="Foto Ketua">
                    @if($candidate->photo_wakil)
                        <img src="{{ asset('storage/' . $candidate->photo_wakil) }}" class="candidate-photo wakil" alt="Foto Wakil">
                    @endif
                </div>
                <h5 class="card-title mt-3">{{ $candidate->name_ketua }}</h5>
                @if($candidate->name_wakil)
                    <p class="card-text text-muted">& {{ $candidate->name_wakil }}</p>
                @endif
                <p class="small text-muted">
                    <strong>Visi:</strong> {{ \Illuminate\Support\Str::limit($candidate->vision, 80) }}
                </p>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('admin.mpk-candidates.edit', $candidate->id) }}" class="btn btn-sm btn-outline-warning mx-1">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <form action="{{ route('admin.mpk-candidates.destroy', $candidate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kandidat ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger mx-1">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="bi bi-people-fill fs-1 text-muted"></i>
                <h5 class="card-title mt-3">Belum Ada Kandidat</h5>
                <p class="card-text text-muted">Silakan tambahkan kandidat pertama Anda.</p>
                <a href="{{ route('admin.mpk-candidates.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Tambah Kandidat Sekarang
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
