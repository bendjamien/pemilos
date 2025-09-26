@extends('layouts.admin')

@section('title', 'Laporan Final Hasil Pemilihan')

@push('styles')
<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #224abe;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --light-bg: #f8f9fc;
        --card-shadow: 0 4px 15px rgba(0,0,0,0.07);
    }

    /* CSS untuk membuat tampilan lebih rapi saat dicetak */
    @media print {
        body * {
            visibility: hidden;
        }
        #report-section, #report-section * {
            visibility: visible;
        }
        #report-section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none;
        }
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
        .chart-container {
            page-break-inside: avoid;
        }
    }

    .report-header {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
        text-align: center;
    }

    .report-header h4 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
    }

    .report-header h5 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.3rem;
    }

    .report-header p {
        margin-bottom: 0;
        opacity: 0.8;
    }

    .stats-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .stats-card .card-body {
        padding: 1.5rem;
        text-align: center;
    }

    .stats-card h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-card h4 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .stats-card.primary {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        color: white;
    }

    .stats-card.primary h6 {
        color: rgba(255, 255, 255, 0.8);
    }

    .stats-card.success {
        background: linear-gradient(45deg, var(--success-color), #13855c);
        color: white;
    }

    .stats-card.success h6 {
        color: rgba(255, 255, 255, 0.8);
    }

    .stats-card.info {
        background: linear-gradient(45deg, var(--info-color), #2a96a5);
        color: white;
    }

    .stats-card.info h6 {
        color: rgba(255, 255, 255, 0.8);
    }

    .result-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .result-card .card-header {
        padding: 1rem 1.5rem;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .result-card.osis .card-header {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        color: white;
    }

    .result-card.mpk .card-header {
        background: linear-gradient(45deg, var(--success-color), #13855c);
        color: white;
    }

    .result-card .card-body {
        padding: 1.5rem;
    }

    .highlight-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }

    .highlight-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .highlight-card.winner {
        border-left: 5px solid var(--success-color);
    }

    .highlight-card.winner .card-body {
        background-color: rgba(28, 200, 138, 0.05);
    }

    .highlight-card.loser {
        border-left: 5px solid var(--danger-color);
    }

    .highlight-card.loser .card-body {
        background-color: rgba(231, 74, 59, 0.05);
    }

    .highlight-card .card-body h6 {
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }

    .highlight-card.winner .card-body h6 {
        color: var(--success-color);
    }

    .highlight-card.loser .card-body h6 {
        color: var(--danger-color);
    }

    .highlight-card .card-body h5 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .highlight-card .card-body p {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .highlight-card.winner .card-body p {
        color: var(--success-color);
    }

    .highlight-card.loser .card-body p {
        color: var(--danger-color);
    }

    .chart-title {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 1rem;
        text-align: center;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
        margin-bottom: 2rem;
    }

    .table-container {
        margin-top: 1.5rem;
    }

    .table-custom {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }

    .table-custom thead th {
        background-color: #f8f9fc;
        color: #5a5c69;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
    }

    .table-custom tbody td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
        border-bottom: 1px solid #e3e6f0;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .table-custom tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .rank-1 {
        background-color: var(--warning-color);
        color: white;
    }

    .rank-2 {
        background-color: #a0aec0;
        color: white;
    }

    .rank-3 {
        background-color: #cd7f32;
        color: white;
    }

    .rank-other {
        background-color: #e3e6f0;
        color: #5a5c69;
    }

    .print-btn {
        background-color: var(--success-color);
        border-color: var(--success-color);
        color: white;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .print-btn:hover {
        background-color: #17a673;
        border-color: #17a673;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    }

    .print-btn i {
        margin-right: 0.5rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .report-header {
            padding: 1.5rem;
        }
        
        .report-header h4 {
            font-size: 1.5rem;
        }
        
        .report-header h5 {
            font-size: 1.1rem;
        }
        
        .stats-card h4 {
            font-size: 1.5rem;
        }
        
        .chart-container {
            height: 250px;
        }
        
        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>
@endpush

@section('content')

<div class="page-header no-print">
    <h1 class="h3 mb-0 text-gray-800">Laporan Final Pemilihan</h1>
    <button onclick="window.print()" class="btn print-btn">
        <i class="fas fa-print fa-sm"></i> Cetak atau Simpan sebagai PDF
    </button>
</div>

<div id="report-section">
    <div class="report-header">
        <h4>LAPORAN AKHIR HASIL PEMILIHAN</h4>
        <h5>KETUA & WAKIL KETUA OSIS SERTA KETUA MPK</h5>
        <p>Tanggal Laporan: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card stats-card primary">
                <div class="card-body">
                    <h6>Total Pemilih</h6>
                    <h4>{{ $totalVoters }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stats-card success">
                <div class="card-body">
                    <h6>Suara Masuk</h6>
                    <h4>{{ $usersVoted }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stats-card info">
                <div class="card-body">
                    <h6>Tingkat Partisipasi</h6>
                    <h4>{{ number_format($participationPercentage, 1) }}%</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card result-card osis">
        <div class="card-header">
            <h5 class="mb-0">HASIL PEMILIHAN OSIS</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                @if($osisWinner)
                <div class="col-md-6 mb-3">
                    <div class="card highlight-card winner">
                        <div class="card-body">
                            <h6><i class="fas fa-trophy me-2"></i>SUARA TERBANYAK</h6>
                            <h5 class="card-title">{{ $osisWinner->name_ketua }} & {{ $osisWinner->name_wakil }}</h5>
                            <p class="card-text">{{ $osisWinner->votes_count }} Suara</p>
                        </div>
                    </div>
                </div>
                @endif
                @if($osisLoser)
                <div class="col-md-6 mb-3">
                    <div class="card highlight-card loser">
                        <div class="card-body">
                            <h6><i class="fas fa-arrow-down-short-wide me-2"></i>SUARA PALING SEDIKIT</h6>
                            <h5 class="card-title">{{ $osisLoser->name_ketua }} & {{ $osisLoser->name_wakil }}</h5>
                            <p class="card-text">{{ $osisLoser->votes_count }} Suara</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <h6 class="chart-title">GRAFIK PEROLEHAN SUARA OSIS</h6>
            <div class="chart-container">
                <canvas id="osisBarChart"></canvas>
            </div>

            <div class="table-container">
                <h6 class="chart-title">RINCIAN SUARA</h6>
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="45%">Nama Kandidat</th>
                            <th width="25%">Jumlah Suara</th>
                            <th width="25%">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($osisCandidates as $candidate)
                        <tr>
                            <td>
                                @if($loop->index == 0)
                                <span class="rank-badge rank-1">1</span>
                                @elseif($loop->index == 1)
                                <span class="rank-badge rank-2">2</span>
                                @elseif($loop->index == 2)
                                <span class="rank-badge rank-3">3</span>
                                @else
                                <span class="rank-badge rank-other">{{ $loop->index + 1 }}</span>
                                @endif
                            </td>
                            <td>{{ $candidate->name_ketua }} & {{ $candidate->name_wakil }}</td>
                            <td>{{ $candidate->votes_count }}</td>
                            <td>{{ $totalOsisVotes > 0 ? number_format(($candidate->votes_count / $totalOsisVotes) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Bagian MPK --}}
    <div class="card result-card mpk">
        <div class="card-header">
            <h5 class="mb-0">HASIL PEMILIHAN MPK</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                @if($mpkWinner)
                <div class="col-md-6 mb-3">
                    <div class="card highlight-card winner">
                        <div class="card-body">
                            <h6><i class="fas fa-trophy me-2"></i>SUARA TERBANYAK</h6>
                            <h5 class="card-title">{{ $mpkWinner->name_ketua }} & {{ $mpkWinner->name_wakil }}</h5>
                            <p class="card-text">{{ $mpkWinner->votes_count }} Suara</p>
                        </div>
                    </div>
                </div>
                @endif
                @if($mpkLoser)
                <div class="col-md-6 mb-3">
                    <div class="card highlight-card loser">
                        <div class="card-body">
                            <h6><i class="fas fa-arrow-down-short-wide me-2"></i>SUARA PALING SEDIKIT</h6>
                            <h5 class="card-title">{{ $mpkLoser->name_ketua }} & {{ $mpkLoser->name_wakil }}</h5>
                            <p class="card-text">{{ $mpkLoser->votes_count }} Suara</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <h6 class="chart-title">GRAFIK PEROLEHAN SUARA MPK</h6>
            <div class="chart-container">
                <canvas id="mpkBarChart"></canvas>
            </div>

            <div class="table-container">
                <h6 class="chart-title">RINCIAN SUARA</h6>
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="45%">Nama Kandidat</th>
                            <th width="25%">Jumlah Suara</th>
                            <th width="25%">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mpkCandidates as $candidate)
                        <tr>
                            <td>
                                @if($loop->index == 0)
                                <span class="rank-badge rank-1">1</span>
                                @elseif($loop->index == 1)
                                <span class="rank-badge rank-2">2</span>
                                @elseif($loop->index == 2)
                                <span class="rank-badge rank-3">3</span>
                                @else
                                <span class="rank-badge rank-other">{{ $loop->index + 1 }}</span>
                                @endif
                            </td>
                            <td>{{ $candidate->name_ketua }} & {{ $candidate->name_wakil }}</td>
                            <td>{{ $candidate->votes_count }}</td>
                            <td>{{ $totalMpkVotes > 0 ? number_format(($candidate->votes_count / $totalMpkVotes) * 100, 1) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 mb-3">
        <p class="text-muted small">Laporan ini dibuat secara otomatis oleh sistem Pemilu OSIS & MPK</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Data untuk Chart OSIS
    const osisLabels = [ @foreach($osisCandidates as $c) '{{ $c->name_ketua }} & {{ $c->name_wakil }}', @endforeach ];
    const osisData = [ @foreach($osisCandidates as $c) {{ $c->votes_count }}, @endforeach ];

    // Buat Bar Chart OSIS
    new Chart(document.getElementById('osisBarChart'), {
        type: 'bar',
        data: {
            labels: osisLabels,
            datasets: [{
                label: 'Jumlah Suara',
                data: osisData,
                backgroundColor: '#4e73df',
                borderColor: '#4e73df',
                borderWidth: 1,
                borderRadius: 5,
                barThickness: 'flex',
                maxBarThickness: 60
            }]
        },
        options: {
            indexAxis: 'y', // Membuat bar chart menjadi horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Suara: ${context.raw.toLocaleString('id-ID')}`;
                        }
                    }
                }
            },
            scales: { 
                x: { 
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Data untuk Chart MPK
    const mpkLabels = [ @foreach($mpkCandidates as $c) '{{ $c->name_ketua }} & {{ $c->name_wakil }}', @endforeach ];
    const mpkData = [ @foreach($mpkCandidates as $c) {{ $c->votes_count }}, @endforeach ];

    // Buat Bar Chart MPK
    new Chart(document.getElementById('mpkBarChart'), {
        type: 'bar',
        data: {
            labels: mpkLabels,
            datasets: [{
                label: 'Jumlah Suara',
                data: mpkData,
                backgroundColor: '#1cc88a',
                borderColor: '#1cc88a',
                borderWidth: 1,
                borderRadius: 5,
                barThickness: 'flex',
                maxBarThickness: 60
            }]
        },
        options: {
            indexAxis: 'y', // Membuat bar chart menjadi horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Suara: ${context.raw.toLocaleString('id-ID')}`;
                        }
                    }
                }
            },
            scales: { 
                x: { 
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endsection