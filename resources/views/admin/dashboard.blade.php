@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(45deg, #4e73df, #224abe);
        --success-gradient: linear-gradient(45deg, #1cc88a, #13855c);
        --warning-gradient: linear-gradient(45deg, #f6c23e, #dda20a);
        --info-gradient: linear-gradient(45deg, #36b9cc, #2a96a5);
        --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        color: white;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow);
        height: 100%;
    }

    .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .stat-card:hover::before {
        transform: translateX(0);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    .stat-card .stat-icon {
        font-size: 3.5rem;
        position: absolute;
        top: 50%;
        right: 1.5rem;
        transform: translateY(-50%);
        opacity: 0.2;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        opacity: 0.4;
        transform: translateY(-50%) scale(1.1) rotate(-10deg);
    }

    .stat-card h5 {
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-number {
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-label {
        font-size: 0.8rem;
        opacity: 0.8;
        margin-top: 0.25rem;
        position: relative;
        z-index: 1;
    }

    .bg-gradient-primary {
        background: var(--primary-gradient);
    }

    .bg-gradient-success {
        background: var(--success-gradient);
    }

    .bg-gradient-warning {
        background: var(--warning-gradient);
    }

    .bg-gradient-info {
        background: var(--info-gradient);
    }

    /* Progress card styling */
    .progress-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .progress-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    .progress-card .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
    }

    .progress {
        height: 25px;
        border-radius: 10px;
        background-color: #eaecf4;
        overflow: visible;
        position: relative;
    }

    .progress-bar {
        border-radius: 10px;
        position: relative;
        overflow: visible;
        transition: width 1.5s ease-in-out;
    }

    .progress-bar::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background-image: linear-gradient(
            -45deg,
            rgba(255, 255, 255, 0.2) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, 0.2) 50%,
            rgba(255, 255, 255, 0.2) 75%,
            transparent 75%,
            transparent
        );
        background-size: 30px 30px;
        animation: progress-bar-stripes 1s linear infinite;
    }

    @keyframes progress-bar-stripes {
        0% {
            background-position: 30px 0;
        }
        100% {
            background-position: 0 0;
        }
    }

    /* Recent activity styling */
    .activity-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
    }

    .activity-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    .activity-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item:hover {
        background-color: #f8f9fc;
    }

    .activity-time {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Chart container */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<!-- Stat Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-primary shadow-sm">
            <div class="card-body">
                <h5>Total Kandidat</h5>
                <p class="stat-number">{{ $totalCandidates }}</p>
                <p class="stat-label">OSIS & MPK</p>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-success shadow-sm">
            <div class="card-body">
                <h5>Suara Masuk</h5>
                <p class="stat-number">{{ $totalVotes }}</p>
                <p class="stat-label">Total pengguna yang memilih</p>
                <i class="bi bi-box-arrow-in-down stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-warning shadow-sm">
            <div class="card-body">
                <h5>Total Pemilih</h5>
                <p class="stat-number">{{ $totalVoters }}</p>
                <p class="stat-label">Pemilih terdaftar</p>
                <i class="bi bi-person-check-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-info shadow-sm">
            <div class="card-body">
                <h5>Partisipasi</h5>
                <p class="stat-number">{{ number_format($votePercentage, 1) }}%</p>
                <p class="stat-label">Tingkat partisipasi</p>
                <i class="bi bi-pie-chart-fill stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Progress and Activity Row -->
<div class="row">
    <!-- Progress Card -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card progress-card shadow-sm">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Progress Partisipasi Pemilih</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Opsi:</div>
                        <a class="dropdown-item" href="#">Lihat Detail</a>
                        <a class="dropdown-item" href="#">Export Data</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Refresh</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <p class="card-text text-muted mb-0">
                            {{ $totalVotes }} dari {{ $totalVoters }} pemilih telah memberikan suaranya.
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-muted">Target: 85%</span>
                    </div>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: {{ $votePercentage }}%;" aria-valuenow="{{ $votePercentage }}" aria-valuemin="0" aria-valuemax="100">
                        {{ number_format($votePercentage, 1) }}%
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col">
                        <div class="small text-muted">OSIS</div>
                        <div class="h5 mb-0">{{ $osisVotes ?? 0 }}</div>
                    </div>
                    <div class="col">
                        <div class="small text-muted">MPK</div>
                        <div class="h5 mb-0">{{ $mpkVotes ?? 0 }}</div>
                    </div>
                    <div class="col">
                        <div class="small text-muted">Belum Memilih</div>
                        <div class="h5 mb-0">{{ $totalVoters - $totalVotes }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Card -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card activity-card shadow-sm">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terkini</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Opsi:</div>
                        <a class="dropdown-item" href="#">Lihat Semua</a>
                        <a class="dropdown-item" href="#">Mark as Read</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="activity-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-person-plus text-white"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Pemilih baru terdaftar</h6>
                            <div class="activity-time">2 menit yang lalu</div>
                        </div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Suara baru masuk</h6>
                            <div class="activity-time">15 menit yang lalu</div>
                        </div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-person-dash text-white"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Kandidat diperbarui</h6>
                            <div class="activity-time">1 jam yang lalu</div>
                        </div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-gear text-white"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Pengaturan sistem diubah</h6>
                            <div class="activity-time">3 jam yang lalu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Row -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Perolehan Suara</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Opsi:</div>
                        <a class="dropdown-item" href="#">Lihat Detail</a>
                        <a class="dropdown-item" href="#">Export Data</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Refresh</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="voteChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define data variables
        const candidateNames = @json($candidateNames ?? []);
        const voteCounts = @json($voteCounts ?? []);
        
        // Initialize Chart
        const ctx = document.getElementById('voteChart').getContext('2d');
        const voteChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: candidateNames,
                datasets: [{
                    label: 'Perolehan Suara',
                    data: voteCounts,
                    backgroundColor: [
                        'rgba(78, 115, 223, 0.8)',
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(54, 185, 204, 0.8)',
                        'rgba(129, 23, 153, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(129, 23, 153, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Suara: ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection