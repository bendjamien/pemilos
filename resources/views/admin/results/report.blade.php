@extends('layouts.admin')

@section('title', 'Laporan Final Hasil Pemilihan')

@push('styles')
<style>
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
    }
    .highlight-card {
        border-left-width: 5px;
    }
    .border-success {
        border-left-color: #1cc88a !important;
    }
    .border-danger {
        border-left-color: #e74a3b !important;
    }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h1 class="h3 mb-0 text-gray-800">Laporan Final Pemilihan</h1>
    <button onclick="window.print()" class="btn btn-success shadow-sm">
        <i class="fas fa-print fa-sm"></i> Cetak atau Simpan sebagai PDF
    </button>
</div>

<div id="report-section">
    <div class="text-center mb-5">
        <h4 class="fw-bold">LAPORAN AKHIR HASIL PEMILIHAN</h4>
        <h5 class="fw-bold">KETUA & WAKIL KETUA OSIS SERTA KETUA MPK</h5>
        <p class="text-muted">Tanggal Laporan: {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-body h-100 text-center shadow-sm">
                <h6 class="text-muted mb-1">Total Pemilih</h6>
                <h4 class="fw-bold">{{ $totalVoters }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body h-100 text-center shadow-sm">
                <h6 class="text-muted mb-1">Suara Masuk</h6>
                <h4 class="fw-bold">{{ $usersVoted }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-body h-100 text-center shadow-sm">
                <h6 class="text-muted mb-1">Tingkat Partisipasi</h6>
                <h4 class="fw-bold">{{ number_format($participationPercentage, 1) }}%</h4>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-bold">HASIL PEMILIHAN OSIS</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                @if($osisWinner)
                <div class="col-md-6 mb-3">
                    <div class="card highlight-card border-success h-100">
                        <div class="card-body">
                            <h6 class="text-success fw-bold"><i class="fas fa-trophy me-2"></i>SUARA TERBANYAK</h6>
                            <h5 class="card-title">{{ $osisWinner->name_ketua }} & {{ $osisWinner->name_wakil }}</h5>
                            <p class="card-text fs-4 fw-bold">{{ $osisWinner->votes_count }} Suara</p>
                        </div>
                    </div>
                </div>
                @endif
                @if($osisLoser)
                <div class="col-md-6 mb-3">
                    <div class="card highlight-card border-danger h-100">
                        <div class="card-body">
                            <h6 class="text-danger fw-bold"><i class="fas fa-arrow-down-short-wide me-2"></i>SUARA PALING SEDIKIT</h6>
                            <h5 class="card-title">{{ $osisLoser->name_ketua }} & {{ $osisLoser->name_wakil }}</h5>
                            <p class="card-text fs-4 fw-bold">{{ $osisLoser->votes_count }} Suara</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <h6 class="text-center fw-bold text-muted mt-4">GRAFIK PEROLEHAN SUARA OSIS</h6>
            <canvas id="osisBarChart" style="max-height: 300px;"></canvas>

            <h6 class="text-center fw-bold text-muted mt-5">RINCIAN SUARA</h6>
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama Kandidat</th>
                        <th>Jumlah Suara</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($osisCandidates as $candidate)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $candidate->name_ketua }} & {{ $candidate->name_wakil }}</td>
                        <td>{{ $candidate->votes_count }}</td>
                        <td>{{ $totalOsisVotes > 0 ? number_format(($candidate->votes_count / $totalOsisVotes) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Anda bisa menduplikasi struktur Card OSIS di atas dan menyesuaikannya untuk data MPK --}}
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Data untuk Chart OSIS
    const osisLabels = [ @foreach($osisCandidates as $c) '{{ $c->name_ketua }}', @endforeach ];
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
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // Membuat bar chart menjadi horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });

    // Anda bisa menambahkan script untuk Bar Chart MPK di sini dengan cara yang sama
});
</script>
@endsection