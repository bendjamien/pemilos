@extends('layouts.admin')

@section('title', 'Real Count Hasil Pemilihan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

    .page-header {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
    }

    .page-header h2 {
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .page-header p {
        margin-bottom: 0;
        opacity: 0.8;
    }

    .result-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }

    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .card-header-custom {
        background-color: white;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.5rem;
        position: relative;
    }

    .card-header-custom h5 {
        margin-bottom: 0;
        font-weight: 700;
        color: #2c3e50;
        display: flex;
        align-items: center;
    }

    .card-header-custom h5 i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }

    .total-votes {
        font-size: 1.1rem;
        font-weight: 600;
        color: #5a5c69;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e3e6f0;
    }

    .total-votes span {
        color: var(--primary-color);
    }

    .candidate-list-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .candidate-list-title i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }

    .candidate-list-card {
        background-color: white;
        border: 1px solid #e3e6f0;
        margin-bottom: 1rem;
        padding: 1.25rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .candidate-list-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .candidate-list-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background-color: var(--primary-color);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .candidate-list-card:hover::before {
        opacity: 1;
    }

    .candidate-name {
        font-weight: 600;
        color: #3a3b45;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .candidate-rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: var(--light-bg);
        color: #5a5c69;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .candidate-rank.first {
        background-color: var(--warning-color);
        color: white;
    }

    .candidate-rank.second {
        background-color: #a0aec0;
        color: white;
    }

    .candidate-rank.third {
        background-color: #cd7f32;
        color: white;
    }

    .crown-leader {
        color: var(--warning-color);
        margin-left: 8px;
        font-size: 1rem;
    }

    .progress {
        height: 10px;
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

    .vote-stats {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        min-width: 80px;
    }

    .vote-count {
        font-size: 1.2rem;
        font-weight: 700;
        color: #3a3b45;
        line-height: 1.2;
    }

    .vote-percentage {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    .report-btn {
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

    .report-btn:hover {
        background-color: #17a673;
        border-color: #17a673;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    }

    .report-btn i {
        margin-right: 0.5rem;
    }

    .live-indicator {
        display: inline-flex;
        align-items: center;
        background-color: rgba(28, 200, 138, 0.1);
        color: var(--success-color);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 1rem;
    }

    .live-indicator i {
        margin-right: 0.5rem;
        font-size: 0.75rem;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
        100% {
            opacity: 1;
        }
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        background-color: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
    }

    .empty-state i {
        font-size: 3rem;
        color: #d1d3e2;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #5a5c69;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        font-size: 0.95rem;
        color: #858796;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .chart-container {
            height: 250px;
        }
        
        .candidate-list-card {
            padding: 1rem;
        }
        
        .vote-stats {
            min-width: 70px;
        }
        
        .vote-count {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>Real Count Hasil Pemilihan</h2>
            <p>Monitoring hasil pemilihan OSIS dan MPK secara real-time</p>
        </div>
        <div class="d-flex align-items-center">
            <span class="live-indicator">
                <i class="fas fa-circle"></i> Live
            </span>
            <a href="{{ route('admin.results.report') }}" class="btn report-btn ms-3">
                <i class="fas fa-print"></i> Buat Laporan Final
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="result-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-chart-pie"></i> Grafik Pemilihan OSIS</h5>
            </div>
            <div class="card-body-custom">
                <div class="chart-container">
                    <canvas id="osisChart"></canvas>
                </div>
                <div class="total-votes">
                    Total Suara Masuk: <span id="total-osis-votes">0</span>
                </div>
            </div>
        </div>
        
        <h5 class="candidate-list-title mt-4">
            <i class="fas fa-list-ol"></i> Rincian Perolehan Suara OSIS
        </h5>
        <div id="osis-candidates-container"></div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="result-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-chart-pie"></i> Grafik Pemilihan MPK</h5>
            </div>
            <div class="card-body-custom">
                <div class="chart-container">
                    <canvas id="mpkChart"></canvas>
                </div>
                <div class="total-votes">
                    Total Suara Masuk: <span id="total-mpk-votes">0</span>
                </div>
            </div>
        </div>
        
        <h5 class="candidate-list-title mt-4">
            <i class="fas fa-list-ol"></i> Rincian Perolehan Suara MPK
        </h5>
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
                    datasets: [{ 
                        label: 'Jumlah Suara', 
                        data: data, 
                        backgroundColor: colors, 
                        borderColor: '#fff', 
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    label += `${value.toLocaleString('id-ID')} suara (${percentage}%)`;
                                    return label;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true
                    }
                }
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

    // FUNGSI updateSection DENGAN LOGIKA YANG DIJAMIN BENAR
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
                
                // Rank badge
                let rankClass = '';
                let rankText = index + 1;
                if (index === 0) {
                    rankClass = 'first';
                } else if (index === 1) {
                    rankClass = 'second';
                } else if (index === 2) {
                    rankClass = 'third';
                }

                candidatesHtml += `
                    <div class="candidate-list-card" data-id="${candidate.id}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="candidate-name">
                                    <span class="candidate-rank ${rankClass}">${rankText}</span>
                                    ${fullName} ${crownIcon}
                                </div>
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-animated" role="progressbar" 
                                         style="width: ${percentage}%; background-color:${colors[index] || '#6c757d'};">
                                    </div>
                                </div>
                            </div>
                            <div class="vote-stats ms-3">
                                <span class="vote-count" data-current-votes="${candidate.votes_count}">${candidate.votes_count.toLocaleString('id-ID')}</span>
                                <p class="vote-percentage">${percentage}%</p>
                            </div>
                        </div>
                    </div>`;
            });
        } else {
            candidatesHtml = `
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h5>Belum Ada Kandidat</h5>
                    <p>Belum ada kandidat ${type.toUpperCase()} yang terdaftar.</p>
                </div>`;
        }
        container.innerHTML = candidatesHtml;

        const labels = sortedCandidates.map(c => c.name_ketua + (c.name_wakil ? ` & ${c.name_wakil}` : ''));
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