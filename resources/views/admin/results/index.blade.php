@extends('layouts.admin')

@section('title', 'Hasil Perolehan Suara')

@section('content')
<div class="row">
    <!-- Kolom Hasil Pemilihan OSIS -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">Hasil Pemilihan OSIS</h5>
            </div>
            <div class="card-body">
                <div class="mb-4 text-center">
                    {{-- Canvas untuk diagram lingkaran OSIS --}}
                    <canvas id="osisChart" style="max-height: 250px;"></canvas>
                    <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold">{{ $totalOsisVotes }}</span></h6>
                </div>
                <hr>
                @forelse($osisCandidates as $candidate)
                    @php
                        $percentage = $totalOsisVotes > 0 ? ($candidate->votes_count / $totalOsisVotes) * 100 : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>{{ $candidate->name_ketua }} & {{ $candidate->name_wakil }}</span>
                            <span class="fw-bold">{{ $candidate->votes_count }} Suara</span>
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

    <!-- Kolom Hasil Pemilihan MPK -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">Hasil Pemilihan MPK</h5>
            </div>
            <div class="card-body">
                <div class="mb-4 text-center">
                    {{-- Canvas untuk diagram lingkaran MPK --}}
                    <canvas id="mpkChart" style="max-height: 250px;"></canvas>
                    <h6 class="mt-3">Total Suara Masuk: <span class="fw-bold">{{ $totalMpkVotes }}</span></h6>
                </div>
                <hr>
                @forelse($mpkCandidates as $candidate)
                    @php
                        $percentage = $totalMpkVotes > 0 ? ($candidate->votes_count / $totalMpkVotes) * 100 : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>{{ $candidate->name_ketua }}</span>
                            <span class="fw-bold">{{ $candidate->votes_count }} Suara</span>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Data untuk Chart OSIS
    const osisLabels = [
        @foreach($osisCandidates as $candidate)
            '{{ $candidate->name_ketua }}',
        @endforeach
    ];
    const osisData = [
        @foreach($osisCandidates as $candidate)
            {{ $candidate->votes_count }},
        @endforeach
    ];

    // Data untuk Chart MPK
    const mpkLabels = [
        @foreach($mpkCandidates as $candidate)
            '{{ $candidate->name_ketua }}',
        @endforeach
    ];
    const mpkData = [
        @foreach($mpkCandidates as $candidate)
            {{ $candidate->votes_count }},
        @endforeach
    ];

    // Fungsi untuk membuat chart
    function createPieChart(chartId, labels, data, colors) {
        const ctx = document.getElementById(chartId).getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut', // Tipe diagram: donat (lingkaran dengan lubang)
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
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' suara';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Buat Chart OSIS (jika ada data)
    if (osisData.length > 0) {
        createPieChart('osisChart', osisLabels, osisData, ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']);
    }

    // Buat Chart MPK (jika ada data)
    if (mpkData.length > 0) {
        createPieChart('mpkChart', mpkLabels, mpkData, ['#1cc88a', '#f6c23e', '#4e73df', '#36b9cc']);
    }
});
</script>
@endsection
