@extends('layouts.admin')

@section('title', 'Real Count Hasil Pemilihan')

{{-- Menambahkan Font Awesome untuk ikon --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* ... (CSS Anda tetap sama, tidak perlu diubah) ... */
    .result-container .card { border: none; border-radius: 0.75rem; transition: all 0.3s ease-in-out; }
    .leader-card { background: linear-gradient(135deg, #f8f9fe 0%, #eef2f8 100%); border: 1px solid #dee2e6; box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.1) !important; position: relative; overflow: hidden; }
    .leader-card .crown-icon { position: absolute; top: -20px; right: -20px; font-size: 80px; color: #ffc107; opacity: 0.3; transform: rotate(15deg); }
    .leader-card .leader-label { font-size: 0.8rem; font-weight: 700; color: #6c757d; text-transform: uppercase; letter-spacing: 1px; }
    .leader-card .vote-count-animated { font-size: 2.8rem; font-weight: 700; }
    .candidate-card { background-color: #fff; border: 1px solid #e3e6f0; margin-bottom: 1rem; padding: 1.25rem; border-radius: 0.75rem; }
    .candidate-card .candidate-name { font-weight: 600; color: #3a3b45; }
    .candidate-card .vote-count { font-size: 1.2rem; font-weight: 700; }
    .progress-bar-animated { transition: width 0.5s ease-in-out; }
    .percentage-text { font-weight: 600; min-width: 50px; text-align: right; }
</style>
@endpush


@section('content')
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.results.report') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-print fa-sm text-white-50"></i> Buat Laporan Final
    </a>
</div>

<div class="row">
    <div class="col-lg-6 mb-4 result-container" id="osis-result-container">
        <div class="card shadow-sm mb-4">
            <div class="card-header"><h5 class="mb-0 fw-bold">Hasil Pemilihan OSIS</h5></div>
            <div class="card-body text-center">
                <div class="mx-auto" style="max-height: 250px; position: relative;"><canvas id="osisChart"></canvas></div>
                <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold" id="total-osis-votes">0</span></h6>
            </div>
        </div>
        <div id="osis-leader-card-container"></div>
        <h6 class="mb-3 text-muted" id="osis-others-title" style="display: none;">Kandidat Lainnya</h6>
        <div id="osis-others-container"></div>
    </div>

    <div class="col-lg-6 mb-4 result-container" id="mpk-result-container">
        <div class="card shadow-sm mb-4">
            <div class="card-header"><h5 class="mb-0 fw-bold">Hasil Pemilihan MPK</h5></div>
            <div class="card-body text-center">
                <div class="mx-auto" style="max-height: 250px; position: relative;"><canvas id="mpkChart"></canvas></div>
                <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold" id="total-mpk-votes">0</span></h6>
            </div>
        </div>
        <div id="mpk-leader-card-container"></div>
        <h6 class="mb-3 text-muted" id="mpk-others-title" style="display: none;">Kandidat Lainnya</h6>
        <div id="mpk-others-container"></div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let osisChartInstance, mpkChartInstance;
    const colorPalette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];
    const colorPaletteMpk = ['#1cc88a', '#f6c23e', '#4e73df', '#36b9cc', '#fd7e14', '#6610f2'];

    // ... (Fungsi animateValue dan createOrUpdateChart tetap sama)
    function animateValue(element, start, end, duration) { /* ... fungsi sama ... */ }
    function createOrUpdateChart(instance, chartId, labels, data, colors) { /* ... fungsi sama ... */ }

    // ========================================================
    // [REVISI] FUNGSI UPDATE UI JADI LEBIH EFISIEN
    // ========================================================
    function updateUI(data) {
        // --- PROSES DATA OSIS ---
        updateSection('osis', data.osisCandidates, data.totalOsisVotes, colorPalette, osisChartInstance, (c) => `${c.name_ketua} & ${c.name_wakil}`);
        
        // --- PROSES DATA MPK ---
        updateSection('mpk', data.mpkCandidates, data.totalMpkVotes, colorPaletteMpk, mpkChartInstance, (c) => c.name_ketua);
    }

    // ========================================================
    // FUNGSI BARU UNTUK MEMPERBARUI SATU BAGIAN (OSIS atau MPK)
    // ========================================================
    function updateSection(type, candidates, totalVotes, colors, chartInstance, nameFormatter) {
        // Urutkan kandidat
        candidates.sort((a, b) => b.votes_count - a.votes_count);
        const leader = candidates.length > 0 ? candidates[0] : null;
        const others = candidates.slice(1);

        // Update total suara
        document.getElementById(`total-${type}-votes`).innerText = totalVotes.toLocaleString('id-ID');

        // Update Leader Card
        const leaderContainer = document.getElementById(`${type}-leader-card-container`);
        if (leader) {
            const percentage = totalVotes > 0 ? (leader.votes_count / totalVotes * 100).toFixed(1) : 0;
            leaderContainer.innerHTML = `
                <div class="card shadow-sm leader-card mb-4" data-id="${leader.id}">
                    <div class="card-body">
                        <i class="fas fa-crown crown-icon"></i>
                        <div class="text-center">
                            <p class="leader-label mb-2">PEMIMPIN SEMENTARA</p>
                            <h4 class="fw-bold mb-1">${nameFormatter(leader)}</h4>
                            <h2 class="vote-count-animated" data-current-votes="${leader.votes_count}">${leader.votes_count.toLocaleString('id-ID')}</h2>
                            <p class="text-muted fw-bold">${percentage}% dari total suara</p>
                        </div>
                    </div>
                </div>`;
        } else {
            leaderContainer.innerHTML = ''; // Kosongkan jika tidak ada kandidat
        }

        // Update Others Card
        const othersContainer = document.getElementById(`${type}-others-container`);
        const othersTitle = document.getElementById(`${type}-others-title`);
        if (others.length > 0) {
            othersTitle.style.display = 'block';
            let othersHtml = '';
            others.forEach((candidate, index) => {
                const percentage = totalVotes > 0 ? (candidate.votes_count / totalVotes * 100).toFixed(1) : 0;
                othersHtml += `
                    <div class="card candidate-card" data-id="${candidate.id}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="candidate-name mb-1">${nameFormatter(candidate)}</p>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-animated" role="progressbar" style="width: ${percentage}%; background-color:${colors[index + 1] || '#6c757d'};"></div>
                                </div>
                            </div>
                            <div class="ms-3 text-end">
                                <span class="vote-count" data-current-votes="${candidate.votes_count}">${candidate.votes_count.toLocaleString('id-ID')}</span>
                                <p class="small text-muted mb-0">${percentage}%</p>
                            </div>
                        </div>
                    </div>`;
            });
            othersContainer.innerHTML = othersHtml;
        } else {
            othersTitle.style.display = 'none';
            othersContainer.innerHTML = '';
        }

        // Update Chart
        const labels = candidates.map(c => c.name_ketua); // Cukup nama ketua untuk label chart
        const chartData = candidates.map(c => c.votes_count);
        
        if(type === 'osis') {
            osisChartInstance = createOrUpdateChart(chartInstance, 'osisChart', labels, chartData, colors.slice(0, chartData.length));
        } else {
            mpkChartInstance = createOrUpdateChart(chartInstance, 'mpkChart', labels, chartData, colors.slice(0, chartData.length));
        }
    }

    // ========================================================
    // FUNGSI UTAMA UNTUK MENGAMBIL DATA (fetchData)
    // ========================================================
    async function fetchData() {
        try {
            const response = await fetch("{{ route('admin.results.fetch') }}");
            if (!response.ok) throw new Error('Network error');
            const data = await response.json();

            // Simpan nilai suara lama untuk animasi
            const oldVoteCounts = {};
            document.querySelectorAll('[data-current-votes]').forEach(el => {
                const id = el.closest('[data-id]').dataset.id;
                oldVoteCounts[id] = parseInt(el.dataset.currentVotes, 10);
            });
            
            updateUI(data);

            // Jalankan animasi angka untuk SEMUA kandidat setelah UI di-update
            [...data.osisCandidates, ...data.mpkCandidates].forEach(c => {
                const el = document.querySelector(`[data-id="${c.id}"] [data-current-votes]`);
                if(el) {
                    const startVal = oldVoteCounts[c.id] || 0;
                    animateValue(el, startVal, c.votes_count, 1000);
                }
            });

        } catch (error) {
            console.error('Gagal mengambil data terbaru:', error);
        }
    }

    // Inisialisasi awal dan polling
    fetchData(); 
    setInterval(fetchData, 5000);
});
</script>
@endsection