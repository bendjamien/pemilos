@extends('layouts.admin')

@section('title', 'Real Count Hasil MPK')

@push('styles')
<style>
    /* Header dan Indikator */
    .page-header { background: linear-gradient(45deg, #1cc88a, #17a673); color: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .live-indicator { display: inline-flex; align-items: center; background-color: rgba(255, 255, 255, 0.2); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
    .live-indicator .dot { width: 8px; height: 8px; background-color: #f6c23e; border-radius: 50%; margin-right: 0.5rem; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    
    /* Tombol Fullscreen */
    .btn-fullscreen { background-color: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.5); }
    .btn-fullscreen:hover { background-color: rgba(255, 255, 255, 0.3); }

    /* Container Grafik */
    .chart-container { position: relative; height: 50vh; min-height: 300px; }
    .empty-state { text-align: center; padding: 2rem; }

    /* Aturan untuk menyembunyikan sidebar saat fullscreen */
    body.in-fullscreen .sidebar {
        display: none !important;
    }
    body.in-fullscreen .main-wrapper {
        padding-left: 0 !important;
    }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-0 fw-bold">Real Count Pemilihan MPK</h2>
        <p class="mb-0 opacity-75">Total Suara Masuk: <span id="total-votes-header">{{ $totalMpkVotes }}</span></p>
    </div>
    <div>
        <span class="live-indicator me-2"><div class="dot"></div> Live</span>
        <button class="btn btn-sm btn-fullscreen" id="fullscreen-btn">
            <i class="bi bi-arrows-fullscreen"></i> <span id="fullscreen-text">Layar Penuh</span>
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-pie-chart-fill me-2"></i> Grafik Perolehan Suara</span>
        <small class="text-muted">Update setiap 5 detik</small>
    </div>
    <div class="card-body">
        <div class="chart-container" id="chart-parent">
            <canvas id="voteChart"></canvas>
        </div>
        @if(count($mpkCandidates) === 0)
             <div class="empty-state">
                <i class="bi bi-people-fill fs-1 text-muted"></i>
                <h5 class="mt-3">Belum Ada Kandidat MPK</h5>
                <p class="text-muted">Data akan tampil setelah kandidat ditambahkan.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Daftarkan plugin untuk menampilkan label di dalam chart
    Chart.register(ChartDataLabels);

    let voteChartInstance;
    const colorPalette = ['#1cc88a', '#f6c23e', '#4e73df', '#36b9cc', '#e74a3b', '#858796'];

    function createOrUpdateChart(candidates, totalVotes) {
        const container = document.getElementById('chart-parent');
        if (!container) return;
        
        if(voteChartInstance) {
            voteChartInstance.destroy();
        }
        
        if (!candidates || candidates.length === 0) {
            container.innerHTML = `<div class="empty-state"><i class="bi bi-people-fill fs-1 text-muted"></i><h5 class="mt-3">Belum Ada Kandidat MPK</h5></div>`;
            return;
        } else if (container.querySelector('.empty-state')) {
            container.innerHTML = '<canvas id="voteChart"></canvas>';
        }

        const ctx = document.getElementById('voteChart').getContext('2d');
        
        voteChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: candidates.map(c => c.name_ketua + (c.name_wakil ? ` & ${c.name_wakil}` : '')),
                datasets: [{
                    label: 'Jumlah Suara',
                    data: candidates.map(c => c.votes_count),
                    backgroundColor: colorPalette,
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 14 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw || 0;
                                const percentage = totalVotes > 0 ? (value / totalVotes * 100).toFixed(1) : 0;
                                return `${context.label}: ${value} Suara (${percentage}%)`;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const percentage = totalVotes > 0 ? (value / totalVotes * 100).toFixed(1) : 0;
                            if (percentage < 5) return '';
                            return `${percentage}%`;
                        },
                        color: '#fff',
                        font: { weight: 'bold', size: 14 }
                    }
                }
            }
        });
    }

    async function fetchData() {
        try {
            const response = await fetch("{{ route('admin.results.fetch') }}");
            if (!response.ok) throw new Error('Gagal mengambil data');
            const data = await response.json();
            
            document.getElementById('total-votes-header').innerText = data.totalMpkVotes.toLocaleString('id-ID');
            createOrUpdateChart(data.mpkCandidates, data.totalMpkVotes);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    // --- Logika Fullscreen ---
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const fullscreenText = document.getElementById('fullscreen-text');
    const fullscreenIcon = fullscreenBtn.querySelector('i');

    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }

    fullscreenBtn.addEventListener('click', toggleFullScreen);

    document.addEventListener('fullscreenchange', () => {
        if (document.fullscreenElement) {
            document.body.classList.add('in-fullscreen');
            fullscreenText.textContent = 'Keluar';
            fullscreenIcon.className = 'bi bi-fullscreen-exit';
        } else {
            document.body.classList.remove('in-fullscreen');
            fullscreenText.textContent = 'Layar Penuh';
            fullscreenIcon.className = 'bi bi-arrows-fullscreen';
        }
    });

    fetchData();
    setInterval(fetchData, 5000);
});
</script>
@endpush

