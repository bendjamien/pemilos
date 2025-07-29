@extends('layouts.admin')

@section('title', 'Tambah Kandidat MPK')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Form Tambah Kandidat MPK</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.mpk-candidates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Nama Ketua (Wajib) --}}
            <div class="mb-3">
                <label for="name_ketua" class="form-label">Nama Calon Ketua</label>
                <input type="text" name="name_ketua" id="name_ketua" class="form-control" value="{{ old('name_ketua') }}" required>
            </div>
            {{-- Foto Ketua (Wajib) --}}
            <div class="mb-3">
                <label for="photo_ketua" class="form-label">Foto Calon Ketua</label>
                <input type="file" name="photo_ketua" id="photo_ketua" class="form-control" required>
            </div>

            <hr>
            <p class="text-muted">Isi bagian di bawah ini jika ada calon wakil.</p>

            {{-- Nama Wakil (Opsional) --}}
            <div class="mb-3">
                <label for="name_wakil" class="form-label">Nama Calon Wakil (Opsional)</label>
                <input type="text" name="name_wakil" id="name_wakil" class="form-control" value="{{ old('name_wakil') }}">
            </div>
            {{-- Foto Wakil (Opsional) --}}
            <div class="mb-3">
                <label for="photo_wakil" class="form-label">Foto Calon Wakil (Opsional)</label>
                <input type="file" name="photo_wakil" id="photo_wakil" class="form-control">
            </div>

            <hr>

            {{-- Visi & Misi --}}
            <div class="mb-3">
                <label for="vision" class="form-label">Visi</label>
                <textarea name="vision" id="vision" rows="4" class="form-control" required>{{ old('vision') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="mission" class="form-label">Misi</label>
                <textarea name="mission" id="mission" rows="6" class="form-control" required>{{ old('mission') }}</textarea>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.mpk-candidates.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kandidat</button>
            </div>
        </form>
    </div>
</div>
@endsection
