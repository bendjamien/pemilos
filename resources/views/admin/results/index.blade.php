@extends('layouts.admin')

@section('title', 'Real Count Hasil Pemilihan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* CSS Anda sudah bagus, tidak ada perubahan */
    .result-container .card { border: none; border-radius: 0.75rem; transition: all 0.3s ease-in-out; }
    .candidate-list-card { background-color: #fff; border: 1px solid #e3e6f0; margin-bottom: 1rem; padding: 1.25rem; border-radius: 0.75rem; }
    .candidate-list-card .candidate-name { font-weight: 600; color: #3a3b45; }
    .candidate-list-card .vote-count { font-size: 1.2rem; font-weight: 700; }
    .progress-bar-animated { transition: width 0.5s ease-in-out; }
    .crown-leader { color: #f6c23e; margin-left: 8px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.results.report') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-print fa-sm text-white-50"></i> Buat Laporan Final
    </a>
</div>

<div class="row">
    <div class="col-lg-6 mb-4 result-container">
        <div class="card shadow-sm mb-4">
            <div class="card-header"><h5 class="mb-0 fw-bold">Grafik Pemilihan OSIS</h5></div>
            <div class="card-body text-center">
                <div class="mx-auto" style="max-height: 280px; position: relative;"><canvas id="osisChart"></canvas></div>
                <hr>
                <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold" id="total-osis-votes">0</span></h6>
            </div>
        </div>
        <h5 class="mb-3 fw-bold">Rincian Perolehan Suara</h5>
        <div id="osis-candidates-container"></div>
    </div>

    <div class="col-lg-6 mb-4 result-container">
        <div class="card shadow-sm mb-4">
            <div class="card-header"><h5 class="mb-0 fw-bold">Grafik Pemilihan MPK</h5></div>
            <div class="card-body text-center">
                <div class="mx-auto" style="max-height: 280px; position: relative;"><canvas id="mpkChart"></canvas></div>
                <hr>
                <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold" id="total-mpk-votes">0</span></h6>
            </div>
        </div>
        <h5 class="mb-3 fw-bold">Rincian Perolehan Suara</h5>
        <div id="mpk-candidates-container"></div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let osisChartInstance, mpkChartInstance;
    const colorPalette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];
    const colorPaletteMpk = ['#1cc88a', '#f6c23e', '#4e73df', '#36b9cc', '#fd7e14', '#6610f2'];

    // FUNGSI ANIMASI ANGKA
    function animateValue(element, start, end, duration) {
        if (start === end) {
            element.innerText = end.toLocaleString('id-ID');
            return;
        }
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.innerText = Math.floor(progress * (end - start) + start).toLocaleString('id-ID');
            if (progress < 1) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
    }
    
    // FUNGSI UNTUK MEMBUAT ATAU UPDATE CHART
    function createOrUpdateChart(instance, chartId, labels, data, colors) {
        const ctx = document.getElementById(chartId).getContext('2d');
        if (!instance) {
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{ label: 'Jumlah Suara', data: data, backgroundColor: colors, borderColor: '#fff', borderWidth: 2 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        } else {
            instance.data.labels = labels;
            instance.data.datasets[0].data = data;
            instance.data.datasets[0].backgroundColor = colors;
            instance.update();
            return instance;
        }
    }
    
    // FUNGSI UTAMA UNTUK RENDER DAN UPDATE UI
    function updateUI(data) {
        osisChartInstance = updateSection('osis', data.osisCandidates, data.totalOsisVotes, colorPalette, osisChartInstance);
        mpkChartInstance = updateSection('mpk', data.mpkCandidates, data.totalMpkVotes, colorPaletteMpk, mpkChartInstance);
    }

    // [PERBAIKAN FINAL] FUNGSI updateSection DENGAN LOGIKA YANG DIJAMIN BENAR
    function updateSection(type, candidates, totalVotes, colors, chartInstance) {
        const sortedCandidates = [...candidates].sort((a, b) => b.votes_count - a.votes_count);

        document.getElementById(`total-${type}-votes`).innerText = totalVotes.toLocaleString('id-ID');

        const container = document.getElementById(`${type}-candidates-container`);
        let candidatesHtml = '';

        if (sortedCandidates.length > 0) {
            sortedCandidates.forEach((candidate, index) => {
                // Kalkulasi persentase yang DIJAMIN BENAR untuk setiap kandidat
                const percentage = totalVotes > 0 ? (candidate.votes_count / totalVotes * 100).toFixed(1) : 0;
                let fullName = candidate.name_ketua + (candidate.name_wakil ? ` & ${candidate.name_wakil}` : '');
                let crownIcon = index === 0 ? '<i class="fas fa-crown crown-leader"></i>' : '';

                candidatesHtml += `
                    <div class="card candidate-list-card" data-id="${candidate.id}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="candidate-name mb-1">${fullName} ${crownIcon}</p>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-animated" role="progressbar" 
                                         style="width: ${percentage}%; background-color:${colors[index] || '#6c757d'};">
                                    </div>
                                </div>
                            </div>
                            <div class="ms-3 text-end" style="min-width: 60px;">
                                 <span class="vote-count" data-current-votes="${candidate.votes_count}">${candidate.votes_count.toLocaleString('id-ID')}</span>
                                 <p class="small text-muted mb-0">${percentage}%</p>
                            </div>
                        </div>
                    </div>`;
            });
        } else {
            candidatesHtml = '<p class="text-center text-muted">Belum ada kandidat.</p>';
        }
        container.innerHTML = candidatesHtml;

        const labels = sortedCandidates.map(c => c.name_ketua);
        const chartData = sortedCandidates.map(c => c.votes_count);
        return createOrUpdateChart(chartInstance, `${type}Chart`, labels, chartData, colors.slice(0, chartData.length));
    }

    // FUNGSI UTAMA UNTUK MENGAMBIL DATA
    async function fetchData() {
        try {
            const response = await fetch("{{ route('admin.results.fetch') }}");
            if (!response.ok) throw new Error('Network error');
            const data = await response.json();

            const oldVoteCounts = {};
            document.querySelectorAll('[data-current-votes]').forEach(el => {
                const id = el.closest('[data-id]').dataset.id;
                oldVoteCounts[id] = parseInt(el.dataset.currentVotes);
            });
            
            updateUI(data);

            const allCandidates = [...data.osisCandidates, ...data.mpkCandidates];
            allCandidates.forEach(c => {
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