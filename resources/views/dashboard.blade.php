@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Informasi Umum</h4>
    </div>
    <div class="card-body">
        <p>Selamat datang di panel administrator Sistem Pemilihan Online.</p>
        <p>Gunakan menu di sebelah kiri untuk mengelola berbagai aspek dari sistem pemilihan.</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Kandidat</h5>
                        <p class="card-text fs-4">0</p> {{-- Nanti diisi data dinamis --}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Suara Masuk</h5>
                        <p class="card-text fs-4">0</p> {{-- Nanti diisi data dinamis --}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-dark bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Pemilih</h5>
                        <p class="card-text fs-4">0</p> {{-- Nanti diisi data dinamis --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection