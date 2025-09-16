@extends('layouts.admin')

@section('title', 'Hasil Perolehan Suara')

@section('content')
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">Hasil Pemilihan OSIS</h5>
            </div>
            <div class="card-body">
                <div class="mb-4 text-center">
                    <canvas id="osisChart" style="max-height: 250px;"></canvas>
                    <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold" id="total-osis-votes">{{ $totalOsisVotes }}</span></h6>
                </div>
                <hr>
                <div id="osis-candidate-list">
                    @forelse($osisCandidates as $candidate)
                        @php
                            $percentage = $totalOsisVotes > 0 ? ($candidate->votes_count / $totalOsisVotes) * 100 : 0;
                        @endphp
                        <div class="mb-3" data-id="{{ $candidate->id }}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>{{ $candidate->name_ketua }} & {{ $candidate->name_wakil }}</span>
                                <span class="fw-bold candidate-vote-count">{{ $candidate->votes_count }} Suara</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada suara yang masuk.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">Hasil Pemilihan MPK</h5>
            </div>
            <div class="card-body">
                <div class="mb-4 text-center">
                    <canvas id="mpkChart" style="max-height: 250px;"></canvas>
                    <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold" id="total-mpk-votes">{{ $totalMpkVotes }}</span></h6>
                </div>
                <hr>
                <div id="mpk-candidate-list">
                    @forelse($mpkCandidates as $candidate)
                        @php
                            $percentage = $totalMpkVotes > 0 ? ($candidate->votes_count / $totalMpkVotes) * 100 : 0;
                        @endphp
                        <div class="mb-3" data-id="{{ $candidate->id }}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>{{ $candidate->name_ketua }}</span>
                                <span class="fw-bold candidate-vote-count">{{ $candidate->votes_count }} Suara</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada suara yang masuk.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pastikan Anda sudah memuat library Chart.js di layout utama Anda --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    let osisChartInstance, mpkChartInstance;

    // ========================================================
    // FUNGSI UNTUK MEMBUAT ATAU UPDATE CHART
    // ========================================================
    function createOrUpdateChart(instance, chartId, labels, data, colors) {
        const ctx = document.getElementById(chartId).getContext('2d');
        if (!instance) {
            // Jika chart belum ada, buat baru
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Suara',
                        data: data,
                        backgroundColor: colors,
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        } else {
            // Jika chart sudah ada, update datanya saja
            instance.data.labels = labels;
            instance.data.datasets[0].data = data;
            instance.update();
            return instance;
        }
    }
    
    // ========================================================
    // FUNGSI UNTUK MEMPERBARUI TAMPILAN (UI)
    // ========================================================
    function updateUI(data) {
        // --- Update Bagian OSIS ---
        document.getElementById('total-osis-votes').innerText = data.totalOsisVotes;
        const osisLabels = data.osisCandidates.map(c => `${c.name_ketua} & ${c.name_wakil}`);
        const osisData = data.osisCandidates.map(c => c.votes_count);
        
        if (osisData.length > 0) {
            osisChartInstance = createOrUpdateChart(osisChartInstance, 'osisChart', osisLabels, osisData, ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']);
        }

        // Update progress bar OSIS
        const osisList = document.getElementById('osis-candidate-list');
        data.osisCandidates.forEach(candidate => {
            const percentage = data.totalOsisVotes > 0 ? (candidate.votes_count / data.totalOsisVotes) * 100 : 0;
            const element = osisList.querySelector(`[data-id="${candidate.id}"]`);
            if (element) {
                element.querySelector('.candidate-vote-count').innerText = `${candidate.votes_count} Suara`;
                element.querySelector('.progress-bar').style.width = `${percentage}%`;
                element.querySelector('.progress-bar').setAttribute('aria-valuenow', percentage);
            }
        });

        // --- Update Bagian MPK ---
        document.getElementById('total-mpk-votes').innerText = data.totalMpkVotes;
        const mpkLabels = data.mpkCandidates.map(c => c.name_ketua);
        const mpkData = data.mpkCandidates.map(c => c.votes_count);

        if (mpkData.length > 0) {
            mpkChartInstance = createOrUpdateChart(mpkChartInstance, 'mpkChart', mpkLabels, mpkData, ['#1cc88a', '#f6c23e', '#4e73df', '#36b9cc']);
        }
        
        // Update progress bar MPK
        const mpkList = document.getElementById('mpk-candidate-list');
        data.mpkCandidates.forEach(candidate => {
            const percentage = data.totalMpkVotes > 0 ? (candidate.votes_count / data.totalMpkVotes) * 100 : 0;
            const element = mpkList.querySelector(`[data-id="${candidate.id}"]`);
            if (element) {
                element.querySelector('.candidate-vote-count').innerText = `${candidate.votes_count} Suara`;
                element.querySelector('.progress-bar').style.width = `${percentage}%`;
                element.querySelector('.progress-bar').setAttribute('aria-valuenow', percentage);
            }
        });
    }

    // ========================================================
    // FUNGSI UTAMA UNTUK MENGAMBIL DATA DARI SERVER
    // ========================================================
    async function fetchData() {
        try {
            const response = await fetch("{{ route('admin.results.fetch') }}");
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            updateUI(data); // Panggil fungsi untuk update tampilan
        } catch (error) {
            console.error('Gagal mengambil data terbaru:', error);
        }
    }

    // ========================================================
    // INISIALISASI DAN POLLING
    // ========================================================
    // Inisialisasi Chart dengan data awal saat halaman dimuat
    const initialData = {
        totalOsisVotes: {{ $totalOsisVotes }},
        osisCandidates: @json($osisCandidates),
        totalMpkVotes: {{ $totalMpkVotes }},
        mpkCandidates: @json($mpkCandidates)
    };
    updateUI(initialData);

    // Jalankan fungsi fetchData() setiap 5 detik (5000 milidetik)
    setInterval(fetchData, 5000);
});
</script>
@endsection