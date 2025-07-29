@extends('layouts.app')

@section('title', 'Terima Kasih Telah Memilih')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center mt-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <i class="bi bi-patch-check-fill text-success" style="font-size: 5rem;"></i>
                    <h1 class="display-4 mt-3">Terima Kasih!</h1>
                    <p class="lead text-muted">Anda sudah menggunakan hak suara Anda dalam pemilihan ini.</p>
                    <hr class="my-4">
                    <p>Hasil pemilihan akan diumumkan oleh panitia sesuai jadwal.</p>
                    
                    {{-- Tombol Logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
