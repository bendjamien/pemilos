@extends('layouts.app')

@section('title', 'Halaman Pemilihan')

@section('content')


<style>
    body {
        background-color: #f4f7f6;
        padding-bottom: 120px; /* Ruang untuk bilah konfirmasi */
    }
    .main-header {
        background: linear-gradient(135deg, #0a1e79 0%, #350664 100%);
        color: white;
        padding: 2rem 0;
        border-radius: 0 0 30px 30px;
    }
    .candidate-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        border-radius: 12px;
        background-color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .candidate-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .candidate-card.selected {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
    }
    .selected-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.5rem;
        color: #0d6efd;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.2s ease-in-out;
    }
    .candidate-card.selected .selected-icon {
        opacity: 1;
        transform: scale(1);
    }
    .photo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 1.5rem;
        min-height: 100px;
    }
    .candidate-photo {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .candidate-photo.wakil {
        margin-left: -25px;
    }
    .candidate-info {
        padding: 1rem;
        text-align: center;
        line-height: 1.3;
        min-height: 80px;
    }
    .candidate-info h6, .candidate-info p {
        margin-bottom: 0;
    }
    .selection-summary {
        position: sticky;
        top: 2rem; /* Membuat panel ini menempel di atas saat scroll */
        padding: 1.5rem;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .vote-confirmation-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #ffffff;
        padding: 1rem;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 1000;
        text-align: center;
    }
    .vote-confirmation-bar.visible {
        transform: translateY(0);
    }
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.85);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }
</style>

<div class="main-header">
    <div class="container text-center">
        <h1 class="display-5 fw-bold">Kotak Suara Digital</h1>
        <p class="fs-5 opacity-75">Pilihlah dengan bijak untuk Osis & Mpk yang lebih baik.</p>
    </div>
</div>

{{-- Form logout tersembunyi, dipisahkan dari form utama --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="container py-4">
    <form id="vote-form">
        @csrf
        <div class="row">
            <!-- Kolom Utama untuk Kandidat -->
            <div class="col-lg-8">
                <!-- Kandidat OSIS -->
                <section id="osis-section" class="mb-5">
                    <h3 class="mb-4">Calon OSIS</h3>
                    <div class="row">
                        @forelse($osisCandidates as $candidate)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 candidate-card" data-candidate-id="{{ $candidate->id }}" data-type="osis" data-name="{{ $candidate->name_ketua }} & {{ $candidate->name_wakil }}">
                                <i class="bi bi-check-circle-fill selected-icon"></i>
                                <div class="photo-container">
                                    <img src="{{ asset('storage/' . $candidate->photo_ketua) }}" class="candidate-photo ketua" alt="Foto {{ $candidate->name_ketua }}">
                                    <img src="{{ asset('storage/' . $candidate->photo_wakil) }}" class="candidate-photo wakil" alt="Foto {{ $candidate->name_wakil }}">
                                </div>
                                <div class="candidate-info">
                                    <h6>Paslon {{ $loop->iteration }}</h6>
                                    <p class="text-muted">{{ $candidate->name_ketua }} & {{ $candidate->name_wakil }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col"><p class="text-center">Belum ada kandidat OSIS.</p></div>
                        @endforelse
                    </div>
                    <input type="hidden" name="osis_candidate_id" id="osis_candidate_id">
                </section>

                <!-- Kandidat MPK -->
                <section id="mpk-section">
                    <h3 class="mb-4">Calon MPK</h3>
                    <div class="row">
                        @forelse($mpkCandidates as $candidate)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 candidate-card" data-candidate-id="{{ $candidate->id }}" data-type="mpk" data-name="{{ $candidate->name_ketua }}">
                                <i class="bi bi-check-circle-fill selected-icon"></i>
                                <div class="photo-container">
                                    <img src="{{ asset('storage/' . $candidate->photo_ketua) }}" class="candidate-photo" alt="Foto {{ $candidate->name_ketua }}">
                                    @if($candidate->photo_wakil)
                                        <img src="{{ asset('storage/' . $candidate->photo_wakil) }}" class="candidate-photo wakil" alt="Foto {{ $candidate->name_wakil }}">
                                    @endif
                                </div>
                                <div class="candidate-info">
                                    <h6>Calon {{ $loop->iteration }}</h6>
                                    <p class="text-muted">{{ $candidate->name_ketua }}</p>
                                    @if($candidate->name_wakil)
                                        <p class="text-muted" style="margin-top:-5px;">& {{ $candidate->name_wakil }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col"><p class="text-center">Belum ada kandidat MPK.</p></div>
                        @endforelse
                    </div>
                    <input type="hidden" name="mpk_candidate_id" id="mpk_candidate_id">
                </section>
            </div>

            <!-- Kolom Sidebar untuk Ringkasan Pilihan -->
            <div class="col-lg-4">
                <div class="selection-summary">
                    <h5 class="mb-3">Pilihan Anda</h5>
                    <div class="mb-3">
                        <label class="form-label">Kandidat OSIS:</label>
                        <p class="fw-bold fs-5" id="selected-osis-name">Belum memilih</p>
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label class="form-label">Kandidat MPK:</label>
                        <p class="fw-bold fs-5" id="selected-mpk-name">Belum memilih</p>
                    </div>

                    {{-- Tombol Logout sekarang menjadi button biasa yang memicu form tersembunyi --}}
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Bilah Tombol Konfirmasi -->
<div class="vote-confirmation-bar" id="vote-confirmation-bar">
    <button type="submit" form="vote-form" class="btn btn-primary btn-lg rounded-pill px-5 py-3" id="submit-vote-btn" disabled>
        <i class="bi bi-send-check-fill me-2"></i> Konfirmasi Pilihan Saya
    </button>
</div>

<!-- Overlay Loading & Terima Kasih -->
<div class="loader-overlay d-none" id="loader-overlay">
    <div id="loader" class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
    <div id="thank-you" class="d-none text-center">
        <i class="bi bi-patch-check-fill text-success" style="font-size: 5rem;"></i>
        <h1 class="display-4 mt-3">Terima Kasih!</h1>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.candidate-card');
    const submitBtn = document.getElementById('submit-vote-btn');
    const osisInput = document.getElementById('osis_candidate_id');
    const mpkInput = document.getElementById('mpk_candidate_id');
    const form = document.getElementById('vote-form');
    const loaderOverlay = document.getElementById('loader-overlay');
    const loader = document.getElementById('loader');
    const thankYou = document.getElementById('thank-you');
    const confirmationBar = document.getElementById('vote-confirmation-bar');
    const selectedOsisName = document.getElementById('selected-osis-name');
    const selectedMpkName = document.getElementById('selected-mpk-name');

    let selectedOsis = null;
    let selectedMpk = null;

    cards.forEach(card => {
        card.addEventListener('click', function() {
            const type = this.dataset.type;
            const candidateId = this.dataset.candidateId;
            const candidateName = this.dataset.name;

            document.querySelectorAll(`.candidate-card[data-type="${type}"]`).forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            if (type === 'osis') {
                selectedOsis = candidateId;
                osisInput.value = candidateId;
                selectedOsisName.textContent = candidateName;
            } else if (type === 'mpk') {
                selectedMpk = candidateId;
                mpkInput.value = candidateId;
                selectedMpkName.textContent = candidateName;
            }

            if (selectedOsis && selectedMpk) {
                submitBtn.disabled = false;
                confirmationBar.classList.add('visible');
            }
        });
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        confirmationBar.classList.remove('visible');
        loaderOverlay.classList.remove('d-none');
        loader.classList.remove('d-none');
        thankYou.classList.add('d-none');

        fetch('{{ route("vote.store") }}', {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            setTimeout(() => {
                loader.classList.add('d-none');
                thankYou.classList.remove('d-none');
                setTimeout(() => {
                    window.location.reload();
                }, 1000); 
            }, 1000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            loaderOverlay.classList.add('d-none');
            confirmationBar.classList.add('visible');
        });
    });
});
</script>
@endsection
