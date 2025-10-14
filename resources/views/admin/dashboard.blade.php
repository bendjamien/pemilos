@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(45deg, #4e73df, #224abe);
        --success-gradient: linear-gradient(45deg, #1cc88a, #13855c);
        --warning-gradient: linear-gradient(45deg, #f6c23e, #dda20a);
        --info-gradient: linear-gradient(45deg, #36b9cc, #2a96a5);
    }
    .stat-card {
        border: none; border-radius: 12px; color: white; padding: 1.5rem;
        position: relative; overflow: hidden; transition: all 0.3s ease;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); height: 100%;
    }
    .stat-card:hover { transform: translateY(-8px); box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175); }
    .stat-card .stat-icon {
        font-size: 3.5rem; position: absolute; top: 50%; right: 1.5rem;
        transform: translateY(-50%); opacity: 0.2; transition: all 0.3s ease;
    }
    .stat-card:hover .stat-icon { opacity: 0.4; transform: translateY(-50%) scale(1.1) rotate(-10deg); }
    .stat-card h5 { font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
    .stat-card .stat-number { font-size: 2.25rem; font-weight: 700; margin-bottom: 0; }
    .stat-card .stat-label { font-size: 0.8rem; opacity: 0.8; margin-top: 0.25rem; }
    .bg-gradient-primary { background: var(--primary-gradient); }
    .bg-gradient-success { background: var(--success-gradient); }
    .bg-gradient-warning { background: var(--warning-gradient); }
    .bg-gradient-info { background: var(--info-gradient); }
    .chart-container { position: relative; height: 300px; width: 100%; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-primary">
            <div class="card-body">
                <h5>Total Kandidat</h5>
                <p class="stat-number">{{ $totalCandidates }}</p>
                <p class="stat-label">OSIS & MPK</p>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-success">
            <div class="card-body">
                <h5>Pemilih Masuk</h5>
                <p class="stat-number">{{ $usersVoted }}</p>
                <p class="stat-label">Total pengguna yang memilih</p>
                <i class="bi bi-person-check-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-warning">
            <div class="card-body">
                <h5>Total Pemilih</h5>
                <p class="stat-number">{{ $totalVoters }}</p>
                <p class="stat-label">Pemilih terdaftar</p>
                <i class="bi bi-person-lines-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-gradient-info">
            <div class="card-body">
                <h5>Partisipasi</h5>
                <p class="stat-number">{{ number_format($votePercentage, 1) }}%</p>
                <p class="stat-label">Tingkat partisipasi</p>
                <i class="bi bi-pie-chart-fill stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Perolehan Suara Gabungan</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="voteChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari Controller
        const candidateNames = @json($candidateNames);
        const voteCounts = @json($voteCounts);
        
        // Inisialisasi Chart
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
                        'rgba(231, 74, 59, 0.8)',
                        'rgba(133, 135, 150, 0.8)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(231, 74, 59, 1)',
                        'rgba(133, 135, 150, 1)'
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
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush