@extends('layouts.app')

@section('title', 'Login - Sistem Pemilihan Online')

@section('content')
<!-- Link untuk memuat Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .login-container {
        display: flex;
        min-height: 100vh;
        width: 100%;
        background-color: #f8f9fa; /* Latar belakang dasar */
    }
    .login-left {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem;
        text-align: center;
        position: relative; /* Diperlukan untuk elemen pseudo */
        overflow: hidden;
    }
    /* Efek gelembung animasi di latar belakang */
    .login-left::before {
        content: '';
        position: absolute;
        bottom: -150px;
        left: -50px;
        width: 300px;
        height: 300px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: bubble-float 15s infinite linear;
    }
    @keyframes bubble-float {
        0% { transform: translateY(0) translateX(0) scale(1); }
        50% { transform: translateY(-80px) translateX(40px) scale(1.2); }
        100% { transform: translateY(0) translateX(0) scale(1); }
    }
    .login-left .icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .login-right {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }
    .login-form-wrapper {
        width: 100%;
        max-width: 400px;
        background-color: #ffffff;
        padding: 2.5rem;
        border-radius: 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
    }
    .form-control-lg {
        min-height: 50px;
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-primary {
        background-color: #6a5af9;
        border-color: #6a5af9;
        padding: 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }
    .btn-primary:hover {
        background-color: #5a4ae9;
        border-color: #5a4ae9;
    }
    .input-group-text {
        background-color: #fff;
        border-right: 0;
        border-radius: 0.5rem 0 0 0.5rem;
    }
    .form-control {
        border-left: 0;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(106, 90, 249, 0.25);
        border-color: #6a5af9;
    }
    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control {
        border-color: #6a5af9;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .login-left {
            display: none; /* Sembunyikan sisi kiri di HP */
        }
        .login-right {
            padding: 1rem;
        }
        .login-form-wrapper {
            box-shadow: none;
            padding: 1.5rem;
        }
    }
</style>

<div class="login-container">
    <!-- Sisi Kiri untuk Branding -->
    <div class="col-md-6 login-left">
        <i class="bi bi-box-seam icon"></i>
        <h2 class="fw-bold">Sistem Pemilihan Online</h2>
        <p class="lead opacity-75">Gunakan hak suara Anda secara digital, transparan, dan efisien.</p>
    </div>

    <!-- Sisi Kanan untuk Form Login -->
    <div class="col-md-6 login-right">
        <div class="login-form-wrapper">
            <h3 class="mb-4 text-center fw-bold">Selamat Datang!</h3>
            <p class="text-muted text-center mb-4">Silakan masuk untuk melanjutkan.</p>

            {{-- Menampilkan pesan error jika login gagal --}}
            @if ($errors->any())
                <div class="alert alert-danger p-2 text-center small">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="contoh@email.com">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="Masukkan password Anda">
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Login Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
