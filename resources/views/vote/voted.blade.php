@extends('layouts.app')

@section('title', 'Terima Kasih Telah Memilih')

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
        background-color: var(--light-bg);
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 2rem 0;
    }

    .thank-you-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .thank-you-card {
        border: none;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        background: white;
        position: relative;
        z-index: 1;
    }

    .thank-you-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 10px;
        background: linear-gradient(90deg, var(--primary-color), var(--success-color));
    }

    .thank-you-header {
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(28, 200, 138, 0.1) 100%);
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
    }

    .success-icon {
        font-size: 6rem;
        color: var(--success-color);
        margin-bottom: 1rem;
        animation: scaleIn 0.8s ease-out;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .thank-you-title {
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.5rem;
        animation: fadeInUp 0.8s ease-out 0.2s;
        animation-fill-mode: both;
    }

    .thank-you-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        animation: fadeInUp 0.8s ease-out 0.4s;
        animation-fill-mode: both;
    }

    .thank-you-body {
        padding: 2.5rem 2rem;
        text-align: center;
    }

    .thank-you-message {
        color: var(--dark-bg);
        margin-bottom: 2rem;
        font-size: 1.1rem;
        line-height: 1.6;
        animation: fadeInUp 0.8s ease-out 0.6s;
        animation-fill-mode: both;
    }

    .thank-you-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e3e6f0, transparent);
        margin: 2rem 0;
        animation: fadeIn 1s ease-out 0.8s;
        animation-fill-mode: both;
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .info-card {
        background-color: var(--light-bg);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-color);
        animation: slideInRight 0.8s ease-out 1s;
        animation-fill-mode: both;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(30px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .info-card h5 {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .info-card h5 i {
        margin-right: 0.5rem;
    }

    .info-card p {
        margin-bottom: 0;
        color: #6c757d;
    }

    .logout-btn {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border: none;
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        color: white;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        animation: fadeInUp 0.8s ease-out 1.2s;
        animation-fill-mode: both;
    }

    .logout-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
        color: white;
    }

    .logout-btn i {
        margin-right: 0.5rem;
    }

    .particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }

    .particles span {
        position: absolute;
        display: block;
        width: 20px;
        height: 20px;
        background: rgba(78, 115, 223, 0.2);
        border-radius: 50%;
        animation: move 25s infinite linear;
        bottom: -150px;
    }

    .particles span:nth-child(1) {
        left: 25%;
        width: 30px;
        height: 30px;
        animation-delay: 0s;
        animation-duration: 20s;
    }

    .particles span:nth-child(2) {
        left: 10%;
        width: 20px;
        height: 20px;
        animation-delay: 2s;
        animation-duration: 12s;
    }

    .particles span:nth-child(3) {
        left: 70%;
        width: 20px;
        height: 20px;
        animation-delay: 4s;
        animation-duration: 15s;
    }

    .particles span:nth-child(4) {
        left: 40%;
        width: 25px;
        height: 25px;
        animation-delay: 0s;
        animation-duration: 18s;
    }

    .particles span:nth-child(5) {
        left: 65%;
        width: 15px;
        height: 15px;
        animation-delay: 0s;
        animation-duration: 10s;
    }

    .particles span:nth-child(6) {
        left: 20%;
        width: 15px;
        height: 15px;
        animation-delay: 2s;
        animation-duration: 14s;
    }

    .particles span:nth-child(7) {
        left: 65%;
        width: 20px;
        height: 20px;
        animation-delay: 3s;
        animation-duration: 16s;
    }

    @keyframes move {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
            border-radius: 0;
        }
        100% {
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
            border-radius: 50%;
        }
    }

    @media (max-width: 768px) {
        .thank-you-header {
            padding: 2rem 1.5rem;
        }
        
        .success-icon {
            font-size: 5rem;
        }
        
        .thank-you-title {
            font-size: 2rem;
        }
        
        .thank-you-body {
            padding: 2rem 1.5rem;
        }
        
        .thank-you-message {
            font-size: 1rem;
        }
    }
</style>

<div class="particles">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>

<div class="thank-you-container">
    <div class="thank-you-card">
        <div class="thank-you-header">
            <i class="bi bi-patch-check-fill success-icon"></i>
            <h1 class="thank-you-title">Terima Kasih!</h1>
            <p class="thank-you-subtitle">Anda sudah menggunakan hak suara Anda dalam pemilihan ini.</p>
        </div>
        
        <div class="thank-you-body">
            <p class="thank-you-message">
                Partisipasi Anda dalam pemilihan OSIS dan MPK sangat berarti bagi kemajuan sekolah kita. 
                Setiap suara yang Anda berikan akan membentuk masa depan yang lebih baik.
            </p>
            
            <div class="thank-you-divider"></div>
            
            <div class="info-card">
                <h5><i class="bi bi-info-circle"></i> Informasi Penting</h5>
                <p>Hasil pemilihan akan diumumkan oleh panitia sesuai jadwal yang telah ditentukan. 
                Harap pantau terus pengumuman resmi dari sekolah.</p>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection