@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        border: none;
        border-radius: 12px;
        color: white;
        padding: 1.25rem; /* Ukuran padding dikecilkan */
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .stat-card .stat-icon {
        font-size: 3.5rem; /* Ukuran ikon dikecilkan */
        position: absolute;
        top: 50%;
        right: 1.5rem;
        transform: translateY(-50%);
        opacity: 0.2;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .stat-card:hover .stat-icon {
        opacity: 0.4;
        transform: translateY(-50%) scale(1.1) rotate(-10deg);
    }
    .stat-card h5 {
        font-size: 0.9rem; /* Ukuran judul dikecilkan */
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .stat-card .stat-number {
        font-size: 2.25rem; /* Ukuran angka dikecilkan */
        font-weight: 700;
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }
    .bg-gradient-success {
        background: linear-gradient(45deg, #1cc88a, #13855c);
    }
    .bg-gradient-warning {
        background: linear-gradient(45deg, #f6c23e, #dda20a);
    }
    .bg-gradient-info {
        background: linear-gradient(45deg, #36b9cc, #2a96a5);
    }
</style>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-primary shadow-sm">
            <div class="card-body">
                <h5>Total Kandidat</h5>
                <p class="stat-number">{{ $totalCandidates }}</p>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-success shadow-sm">
            <div class="card-body">
                <h5>Suara Masuk</h5>
                <p class="stat-number">{{ $totalVotes }}</p>
                <i class="bi bi-box-arrow-in-down stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-warning shadow-sm">
            <div class="card-body">
                <h5>Total Pemilih</h5>
                <p class="stat-number">{{ $totalVoters }}</p>
                <i class="bi bi-person-check-fill stat-icon"></i>
            </div>
        </div>
    </div>
    {{-- Kartu Partisipasi Pemilih BARU --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-info shadow-sm">
            <div class="card-body">
                <h5>Partisipasi</h5>
                <p class="stat-number">{{ number_format($votePercentage, 1) }}%</p>
                <i class="bi bi-pie-chart-fill stat-icon"></i>
            </div>
        </div>
    </div>
</div>

{{-- Kartu Progress Partisipasi BARU --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Progress Partisipasi Pemilih</h5>
                <p class="card-text text-muted">
                    {{ $totalVotes }} dari {{ $totalVoters }} pemilih telah memberikan suaranya.
                </p>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $votePercentage }}%;" aria-valuenow="{{ $votePercentage }}" aria-valuemin="0" aria-valuemax="100">
                        {{ number_format($votePercentage, 1) }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
