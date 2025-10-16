@extends('layouts.app')



@section('title', 'Pemilihan ' . strtoupper($type))



@section('content')

<style>

    :root { --primary-color: #4e73df; --secondary-color: #224abe; --light-bg: #f8f9fc; --card-shadow: 0 4px 15px rgba(0,0,0,0.07); }

    body { background-color: #f4f7f6; padding-bottom: 120px; font-family: 'Poppins', sans-serif; }

    .main-header { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; padding: 3rem 0; border-radius: 0 0 30px 30px; }

    .section-title { font-weight: 700; color: #5a5c69; margin-bottom: 1.5rem; position: relative; padding-left: 1rem; }

    .section-title::before { content: ""; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 5px; height: 70%; background-color: var(--primary-color); border-radius: 3px; }

    .candidate-card { cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent; border-radius: 15px; background-color: #ffffff; position: relative; overflow: hidden; box-shadow: var(--card-shadow); height: 100%; }

    .candidate-card:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }

    .candidate-card.selected { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.25); }

    .selected-icon { position: absolute; top: 15px; right: 15px; font-size: 1.8rem; color: var(--primary-color); opacity: 0; transform: scale(0.5); transition: all 0.3s ease-in-out; z-index: 2; }

    .candidate-card.selected .selected-icon { opacity: 1; transform: scale(1); }

    .photo-container { display: flex; justify-content: center; align-items: center; padding-top: 1.5rem; min-height: 120px; }

    .candidate-photo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }

    .candidate-photo.wakil { margin-left: -25px; z-index: 0; }

    .candidate-info { padding: 1.5rem; text-align: center; }

    .selection-summary { position: sticky; top: 2rem; padding: 1.5rem; background-color: #fff; border-radius: 15px; box-shadow: var(--card-shadow); }

    .vote-confirmation-bar { position: fixed; bottom: 0; left: 0; width: 100%; background-color: #ffffff; padding: 1.5rem; box-shadow: 0 -5px 25px rgba(0,0,0,0.1); transform: translateY(100%); transition: transform 0.4s ease-in-out; z-index: 1000; text-align: center; }

    .vote-confirmation-bar.visible { transform: translateY(0); }

    .loader-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.9); display: flex; flex-direction: column; justify-content: center; align-items: center; z-index: 9999; backdrop-filter: blur(5px); }

    .thank-you-message i { font-size: 5rem; color: #1cc88a; margin-bottom: 1rem; }

</style>



<div class="main-header">

    <div class="container text-center">

        <h1 class="display-5 fw-bold">Kotak Suara Digital</h1>

        <p class="fs-5 opacity-75">Pemilihan Calon {{ strtoupper($type) }} (JIVA ABISATYA SMKS NURUL ISLAM)</p>

    </div>

</div>



<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>



<div class="container py-4">



    {{-- [UBAH UNTUK TESTING] Kondisi diubah menjadi 'false' agar tampilan "Terima Kasih" tidak pernah muncul --}}

    @if(false)

        <div class="voted-container">

            {{-- Blok ini tidak akan pernah ditampilkan dalam mode testing --}}

        </div>

    @else

        {{-- TAMPILAN PEMILIHAN AKAN SELALU MUNCUL --}}

        <form id="vote-form">

            @csrf

            <div class="row">

                <div class="col-lg-8">

                    <section class="mb-5">

                        <h3 class="section-title">Calon {{ strtoupper($type) }}</h3>

                        <div class="row">

                            @forelse($candidates as $candidate)

                            <div class="col-md-6 mb-4">

                                <div class="card h-100 candidate-card" data-candidate-id="{{ $candidate->id }}" data-name="{{ $candidate->name_ketua }}{{ $candidate->name_wakil ? ' & ' . $candidate->name_wakil : '' }}">

                                    <i class="bi bi-check-circle-fill selected-icon"></i>

                                    <div class="photo-container">

                                        <img src="{{ asset('storage/' . $candidate->photo_ketua) }}" class="candidate-photo ketua" alt="Foto {{ $candidate->name_ketua }}">

                                        @if($candidate->photo_wakil)

                                        <img src="{{ asset('storage/' . $candidate->photo_wakil) }}" class="candidate-photo wakil" alt="Foto {{ $candidate->name_wakil }}">

                                        @endif

                                    </div>

                                    <div class="candidate-info">

                                        <h6>{{ $type === 'osis' ? 'Paslon' : 'Calon' }} {{ $loop->iteration }}</h6>

                                        <p class="text-muted">{{ $candidate->name_ketua }}{{ $candidate->name_wakil ? ' & ' . $candidate->name_wakil : '' }}</p>

                                    </div>

                                </div>

                            </div>

                            @empty

                            <div class="col-12"><p class="text-center">Belum ada kandidat {{ strtoupper($type) }}.</p></div>

                            @endforelse

                        </div>

                    </section>

                    <input type="hidden" name="candidate_id" id="candidate_id">

                </div>

                <div class="col-lg-4">

                    <div class="selection-summary">

                        <h5><i class="bi bi-clipboard-check me-2"></i>Pilihan Anda</h5>

                        <div class="mb-3">

                            <label class="form-label">Kandidat {{ strtoupper($type) }}:</label>

                            <p class="fw-bold fs-5" id="selected-name">Belum memilih</p>

                        </div>

                        <hr>

                        <div class="d-grid">

                            <button type="button" class="btn btn-outline-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                                <i class="bi bi-box-arrow-right me-1"></i> Logout

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    @endif

</div>



<div class="vote-confirmation-bar" id="vote-confirmation-bar">

    <button type="submit" form="vote-form" class="btn btn-primary btn-lg" id="submit-vote-btn" disabled>

        <i class="bi bi-send-check-fill me-2"></i> Konfirmasi Pilihan Saya

    </button>

</div>



<div class="loader-overlay d-none" id="loader-overlay">

    <div id="loader" class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>

    <div id="thank-you" class="thank-you-message d-none">

        <i class="bi bi-patch-check-fill"></i>

        <h1>Terima Kasih!</h1>

        <p>Vote Anda telah berhasil direkam.</p>

    </div>

</div>



@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('vote-form');

    if (form) {

        const cards = document.querySelectorAll('.candidate-card');

        const submitBtn = document.getElementById('submit-vote-btn');

        const candidateInput = document.getElementById('candidate_id');

        const loaderOverlay = document.getElementById('loader-overlay');

        const loader = document.getElementById('loader');

        const thankYou = document.getElementById('thank-you');

        const confirmationBar = document.getElementById('vote-confirmation-bar');

        const selectedName = document.getElementById('selected-name');

        let selectedCandidate = null;



        cards.forEach(card => {

            card.addEventListener('click', function() {

                document.querySelectorAll('.candidate-card').forEach(c => c.classList.remove('selected'));

                this.classList.add('selected');

                selectedCandidate = this.dataset.candidateId;

                candidateInput.value = selectedCandidate;

                selectedName.textContent = this.dataset.name;

                if (selectedCandidate) {

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



            fetch('{{ route("vote.store", $type) }}', {

                method: 'POST',

                body: new FormData(form),

                headers: {

                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,

                    'Accept': 'application/json',

                }

            })

            .then(response => response.ok ? response.json() : response.json().then(err => Promise.reject(err)))

            .then(data => {

                setTimeout(() => {

                    loader.classList.add('d-none');

                    thankYou.classList.remove('d-none');

                    setTimeout(() => {

                        window.location.href = data.next_url;

                    }, 1500);

                }, 1000);

            })

            .catch(error => {

                console.error('Error:', error);

                alert(error.message || 'Terjadi kesalahan. Silakan coba lagi.');

                loaderOverlay.classList.add('d-none');

            });

        });

    }

});

</script>

@endpush

@endsection