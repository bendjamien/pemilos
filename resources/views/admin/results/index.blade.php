@extends('layouts.admin')

@section('title', 'Real Count Hasil Pemilihan')

{{-- Menambahkan Font Awesome untuk ikon --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* Custom CSS untuk tampilan Real Count yang modern */
    .result-container .card {
        border: none;
        border-radius: 0.75rem;
        transition: all 0.3s ease-in-out;
    }

    .leader-card {
        background: linear-gradient(135deg, #f8f9fe 0%, #eef2f8 100%);
        border: 1px solid #dee2e6;
        box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.1) !important;
        position: relative;
        overflow: hidden;
    }

    .leader-card .crown-icon {
        position: absolute;
        top: -20px;
        right: -20px;
        font-size: 80px;
        color: #ffc107;
        opacity: 0.3;
        transform: rotate(15deg);
    }
    
    .leader-card .leader-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .leader-card .vote-count-animated {
        font-size: 2.8rem;
        font-weight: 700;
    }

    .candidate-card {
        background-color: #fff;
        border: 1px solid #e3e6f0;
        margin-bottom: 1rem;
        padding: 1.25rem;
        border-radius: 0.75rem;
    }
    
    .candidate-card .candidate-name {
        font-weight: 600;
        color: #3a3b45;
    }
    
    .candidate-card .vote-count {
        font-size: 1.2rem;
        font-weight: 700;
    }

    .progress-bar-animated {
        transition: width 0.5s ease-in-out;
    }

    .percentage-text {
        font-weight: 600;
        min-width: 50px;
        text-align: right;
    }
</style>
@endpush


@section('content')
<div class="row">
    <div class="col-lg-6 mb-4 result-container" id="osis-result-container">
        {{-- Konten OSIS akan di-generate oleh JavaScript --}}
    </div>

    <div class="col-lg-6 mb-4 result-container" id="mpk-result-container">
        {{-- Konten MPK akan di-generate oleh JavaScript --}}
    </div>
</div>

{{-- Chart Canvas (dipisah agar tidak ikut ter-render ulang) --}}
<div style="display: none;">
    <canvas id="osisChart" style="max-height: 250px;"></canvas>
    <canvas id="mpkChart" style="max-height: 250px;"></canvas>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    let osisChartInstance, mpkChartInstance;
    const colorPalette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];
    const colorPaletteMpk = ['#1cc88a', '#f6c23e', '#4e73df', '#36b9cc', '#fd7e14', '#6610f2'];

    // ========================================================
    // FUNGSI ANIMASI ANGKA
    // ========================================================
    function animateValue(element, start, end, duration) {
        if (start === end) return;
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.innerText = Math.floor(progress * (end - start) + start).toLocaleString('id-ID');
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }
    
    // ========================================================
    // FUNGSI UNTUK MEMBUAT ATAU UPDATE CHART
    // ========================================================
    function createOrUpdateChart(instance, chartId, labels, data, colors) {
        const ctx = document.getElementById(chartId).getContext('2d');
        if (!instance) {
            return new Chart(ctx, { /* ... Opsi Chart ... */ }); // (Opsi chart sama seperti sebelumnya)
        } else {
            instance.data.labels = labels;
            instance.data.datasets[0].data = data;
            instance.data.datasets[0].backgroundColor = colors;
            instance.update();
            return instance;
        }
    }
    
    // ========================================================
    // FUNGSI UTAMA UNTUK RENDER DAN UPDATE UI
    // ========================================================
    function updateUI(data) {
        // --- PROSES DATA OSIS ---
        const osisContainer = document.getElementById('osis-result-container');
        // Urutkan kandidat berdasarkan suara terbanyak
        data.osisCandidates.sort((a, b) => b.votes_count - a.votes_count);
        const osisLeader = data.osisCandidates.length > 0 ? data.osisCandidates[0] : null;
        const otherOsisCandidates = data.osisCandidates.slice(1);
        
        let osisHtml = `
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0 fw-bold">Hasil Pemilihan OSIS</h5></div>
                <div class="card-body text-center">
                    <canvas id="osisChartNew" style="max-height: 250px;"></canvas>
                    <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold" id="total-osis-votes">${data.totalOsisVotes.toLocaleString('id-ID')}</span></h6>
                </div>
            </div>`;
        
        if (osisLeader) {
            const leaderPercentage = data.totalOsisVotes > 0 ? (osisLeader.votes_count / data.totalOsisVotes * 100).toFixed(1) : 0;
            osisHtml += `
                <div class="card shadow-sm leader-card mb-4" data-id="${osisLeader.id}">
                    <div class="card-body">
                        <i class="fas fa-crown crown-icon"></i>
                        <div class="text-center">
                             <p class="leader-label mb-2">PEMIMPIN SEMENTARA</p>
                             <h4 class="fw-bold mb-1">${osisLeader.name_ketua} & ${osisLeader.name_wakil}</h4>
                             <h2 class="vote-count-animated" data-current-votes="${osisLeader.votes_count}">${osisLeader.votes_count.toLocaleString('id-ID')}</h2>
                             <p class="text-muted fw-bold">${leaderPercentage}% dari total suara</p>
                        </div>
                    </div>
                </div>`;
        }

        if (otherOsisCandidates.length > 0) {
            osisHtml += `<h6 class="mb-3 text-muted">Kandidat Lainnya</h6>`;
            otherOsisCandidates.forEach((candidate, index) => {
                const percentage = data.totalOsisVotes > 0 ? (candidate.votes_count / data.totalOsisVotes * 100) : 0;
                osisHtml += `
                    <div class="card candidate-card" data-id="${candidate.id}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="candidate-name mb-1">${candidate.name_ketua} & ${candidate.name_wakil}</p>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-animated" role="progressbar" style="width: ${percentage}%; background-color:${colorPalette[index + 1] || '#6c757d'};" aria-valuenow="${percentage}"></div>
                                </div>
                            </div>
                            <div class="ms-3 text-end">
                                 <span class="vote-count" data-current-votes="${candidate.votes_count}">${candidate.votes_count.toLocaleString('id-ID')}</span>
                                 <p class="small text-muted mb-0">${percentage.toFixed(1)}%</p>
                            </div>
                        </div>
                    </div>`;
            });
        }
        
        // Cek apakah konten berubah sebelum me-replace
        if (osisContainer.innerHTML !== osisHtml) {
            osisContainer.innerHTML = osisHtml;
            // Pindahkan canvas chart ke tempat baru
            const newOsisCanvasContainer = osisContainer.querySelector('.card-body.text-center');
            const osisChartCanvas = document.getElementById('osisChart');
            newOsisCanvasContainer.replaceChild(osisChartCanvas, osisContainer.querySelector('#osisChartNew'));
        }
        
        // Update Chart OSIS
        const osisLabels = data.osisCandidates.map(c => c.name_ketua);
        const osisData = data.osisCandidates.map(c => c.votes_count);
        osisChartInstance = createOrUpdateChart(osisChartInstance, 'osisChart', osisLabels, osisData, colorPalette);
        
        // --- Lakukan hal yang sama untuk MPK ---
        const mpkContainer = document.getElementById('mpk-result-container');
        data.mpkCandidates.sort((a, b) => b.votes_count - a.votes_count);
        // (Logika render HTML untuk MPK mirip dengan OSIS, disesuaikan untuk field MPK)
        // ... (Kode HTML untuk MPK bisa Anda buat dengan meniru pola OSIS di atas)
    }

    // ========================================================
    // FUNGSI UTAMA UNTUK MENGAMBIL DATA DARI SERVER
    // ========================================================
    async function fetchData() {
        try {
            const response = await fetch("{{ route('admin.results.fetch') }}");
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();

            // Ambil data suara lama sebelum UI di-update
            const oldVoteCounts = {};
            document.querySelectorAll('[data-current-votes]').forEach(el => {
                oldVoteCounts[el.closest('[data-id]').dataset.id] = parseInt(el.dataset.currentVotes, 10);
            });
            
            updateUI(data);

            // Jalankan animasi angka setelah UI di-render
            data.osisCandidates.forEach(c => {
                const el = document.querySelector(`#osis-result-container [data-id="${c.id}"] [data-current-votes]`);
                if(el) {
                    const startVal = oldVoteCounts[c.id] || 0;
                    animateValue(el, startVal, c.votes_count, 1000);
                }
            });
            // ... (lakukan juga untuk animasi angka MPK)

        } catch (error) {
            console.error('Gagal mengambil data terbaru:', error);
        }
    }

    // Panggil pertama kali saat halaman dimuat
    fetchData(); 

    // Jalankan fungsi fetchData() setiap 5 detik
    setInterval(fetchData, 5000);
});
</script>
@endsection