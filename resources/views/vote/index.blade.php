@extends('layouts.app')

@section('title', 'Halaman Pemilihan')

@section('content')
<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #224abe;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --light-bg: #f8f9fc;
        --dark-bg: #5a5c69;
        --card-shadow: 0 4px 15px rgba(0,0,0,0.07);
    }

    body {
        background-color: #f4f7f6;
        padding-bottom: 120px;
        font-family: 'Poppins', sans-serif;
    }

    .main-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 3rem 0;
        border-radius: 0 0 30px 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .main-header::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(45deg);
    }

    .main-header h1 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .main-header p {
        margin-bottom: 0;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .section-title {
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 1.5rem;
        position: relative;
        padding-left: 1rem;
    }

    .section-title::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 5px;
        height: 70%;
        background-color: var(--primary-color);
        border-radius: 3px;
    }

    .candidate-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        border-radius: 15px;
        background-color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        height: 100%;
    }

    .candidate-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }

    .candidate-card.selected {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.25);
    }

    .selected-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 1.8rem;
        color: var(--primary-color);
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.3s ease-in-out;
        z-index: 2;
    }

    .candidate-card.selected .selected-icon {
        opacity: 1;
        transform: scale(1);
    }

    .candidate-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .candidate-card.selected::before {
        transform: scaleX(1);
    }

    .photo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 1.5rem;
        min-height: 120px;
        position: relative;
        z-index: 1;
    }

    .candidate-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .candidate-card:hover .candidate-photo {
        transform: scale(1.05);
    }

    .candidate-photo.wakil {
        margin-left: -25px;
        z-index: 0;
    }

    .candidate-card:hover .candidate-photo.wakil {
        transform: scale(1.05) translateX(5px);
    }

    .candidate-info {
        padding: 1.5rem;
        text-align: center;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .candidate-info h6 {
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--dark-bg);
    }

    .candidate-info p {
        margin-bottom: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .selection-summary {
        position: sticky;
        top: 2rem;
        padding: 1.5rem;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0,0,0,0.05);
    }

    .selection-summary h5 {
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .selection-summary .form-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .selection-summary p {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
    }

    .vote-confirmation-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #ffffff;
        padding: 1.5rem;
        box-shadow: 0 -5px 25px rgba(0,0,0,0.1);
        transform: translateY(100%);
        transition: transform 0.4s ease-in-out;
        z-index: 1000;
        text-align: center;
        border-top: 1px solid #e3e6f0;
    }

    .vote-confirmation-bar.visible {
        transform: translateY(0);
    }

    .btn-submit {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border: none;
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: white;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
        color: white;
    }

    .btn-submit:disabled {
        background: #6c757d;
        transform: none;
        box-shadow: none;
        cursor: not-allowed;
    }

    .btn-logout {
        border-radius: 10px;
        padding: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-logout:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(231, 74, 59, 0.3);
    }

    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.9);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .loader-overlay .spinner-border {
        width: 4rem;
        height: 4rem;
        border-width: 0.3em;
    }

    .thank-you-message {
        text-align: center;
    }

    .thank-you-message i {
        font-size: 5rem;
        color: var(--success-color);
        margin-bottom: 1rem;
        animation: scaleIn 0.5s ease-out;
    }

    .thank-you-message h1 {
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.5rem;
        animation: fadeInUp 0.5s ease-out;
    }

    .thank-you-message p {
        color: #6c757d;
        animation: fadeInUp 0.5s ease-out 0.2s;
        animation-fill-mode: both;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background-color: white;
        border-radius: 15px;
        box-shadow: var(--card-shadow);
    }

    .empty-state i {
        font-size: 3rem;
        color: #d1d3e2;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #6c757d;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .main-header {
            padding: 2rem 0;
        }

        .main-header h1 {
            font-size: 1.8rem;
        }

        .candidate-photo {
            width: 70px;
            height: 70px;
        }

        .candidate-info {
            padding: 1rem;
        }

        .selection-summary {
            margin-top: 2rem;
            position: static;
        }

        .vote-confirmation-bar {
            padding: 1rem;
        }

        .btn-submit {
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
        }
    }
</style>

<div class="main-header">
    <div class="container text-center">
        <h1 class="display-5 fw-bold">Kotak Suara Digital</h1>
        <p class="fs-5 opacity-75">Pilihlah dengan bijak untuk OSIS & MPK yang lebih baik.</p>
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
                    <h3 class="section-title">Calon OSIS</h3>
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
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="bi bi-exclamation-triangle"></i>
                                <p>Belum ada kandidat OSIS.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                    <input type="hidden" name="osis_candidate_id" id="osis_candidate_id">
                </section>

                <!-- Kandidat MPK -->
                <section id="mpk-section">
                    <h3 class="section-title">Calon MPK</h3>
                    <div class="row">
                        @forelse($mpkCandidates as $candidate)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 candidate-card" data-candidate-id="{{ $candidate->id }}" data-type="mpk" data-name="{{ $candidate->name_ketua }}{{ $candidate->name_wakil ? ' & ' . $candidate->name_wakil : '' }}">
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
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="bi bi-exclamation-triangle"></i>
                                <p>Belum ada kandidat MPK.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                    <input type="hidden" name="mpk_candidate_id" id="mpk_candidate_id">
                </section>
            </div>

            <!-- Kolom Sidebar untuk Ringkasan Pilihan -->
            <div class="col-lg-4">
                <div class="selection-summary">
                    <h5><i class="bi bi-clipboard-check me-2"></i>Pilihan Anda</h5>
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
                        <button type="button" class="btn btn-outline-danger btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
    <button type="submit" form="vote-form" class="btn btn-submit btn-lg" id="submit-vote-btn" disabled>
        <i class="bi bi-send-check-fill me-2"></i> Konfirmasi Pilihan Saya
    </button>
</div>

<!-- Overlay Loading & Terima Kasih -->
<div class="loader-overlay d-none" id="loader-overlay">
    <div id="loader" class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
    <div id="thank-you" class="thank-you-message d-none">
        <i class="bi bi-patch-check-fill"></i>
        <h1>Terima Kasih!</h1>
        <p>Vote Anda telah berhasil disimpan.</p>
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
                }, 2000); 
            }, 1500);
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