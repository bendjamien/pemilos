@extends('layouts.admin')

@section('title', 'Edit Kandidat MPK')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Form Edit Kandidat MPK</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.mpk-candidates.update', $mpkCandidate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama Ketua (Wajib) --}}
            <div class="mb-3">
                <label for="name_ketua" class="form-label">Nama Calon Ketua</label>
                <input type="text" name="name_ketua" id="name_ketua" class="form-control" value="{{ old('name_ketua', $mpkCandidate->name_ketua) }}" required>
            </div>
            {{-- Foto Ketua (Opsional saat edit) --}}
            <div class="mb-3">
                <label for="photo_ketua" class="form-label">Foto Calon Ketua (Ganti jika perlu)</label>
                <input type="file" name="photo_ketua" id="photo_ketua" class="form-control">
                <img src="{{ asset('storage/' . $mpkCandidate->photo_ketua) }}" alt="Foto Ketua Saat Ini" width="120" class="img-thumbnail mt-2">
            </div>

            <hr>
            <p class="text-muted">Isi atau ubah bagian di bawah ini jika ada calon wakil.</p>

            {{-- Nama Wakil (Opsional) --}}
            <div class="mb-3">
                <label for="name_wakil" class="form-label">Nama Calon Wakil (Opsional)</label>
                <input type="text" name="name_wakil" id="name_wakil" class="form-control" value="{{ old('name_wakil', $mpkCandidate->name_wakil) }}">
            </div>
            {{-- Foto Wakil (Opsional) --}}
            <div class="mb-3">
                <label for="photo_wakil" class="form-label">Foto Calon Wakil (Opsional)</label>
                <input type="file" name="photo_wakil" id="photo_wakil" class="form-control">
                @if($mpkCandidate->photo_wakil)
                    <img src="{{ asset('storage/' . $mpkCandidate->photo_wakil) }}" alt="Foto Wakil Saat Ini" width="120" class="img-thumbnail mt-2">
                @else
                    <small class="text-muted">Belum ada foto wakil.</small>
                @endif
            </div>

            <hr>

            {{-- Visi & Misi --}}
            <div class="mb-3">
                <label for="vision" class="form-label">Visi</label>
                <textarea name="vision" id="vision" rows="4" class="form-control" required>{{ old('vision', $mpkCandidate->vision) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="mission" class="form-label">Misi</label>
                <textarea name="mission" id="mission" rows="6" class="form-control" required>{{ old('mission', $mpkCandidate->mission) }}</textarea>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.mpk-candidates.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update Kandidat</button>
            </div>
        </form>
    </div>
</div>
@endsection
