@extends('layouts.admin')

@section('title', 'Tambah Kandidat OSIS')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Form Tambah Kandidat OSIS</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.osis-candidates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name_ketua" class="form-label">Nama Calon Ketua</label>
                        <input type="text" name="name_ketua" id="name_ketua" class="form-control @error('name_ketua') is-invalid @enderror" value="{{ old('name_ketua') }}" required>
                        @error('name_ketua') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name_wakil" class="form-label">Nama Calon Wakil</label>
                        <input type="text" name="name_wakil" id="name_wakil" class="form-control @error('name_wakil') is-invalid @enderror" value="{{ old('name_wakil') }}" required>
                        @error('name_wakil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="photo_ketua" class="form-label">Foto Calon Ketua</label>
                        <input type="file" name="photo_ketua" id="photo_ketua" class="form-control @error('photo_ketua') is-invalid @enderror" required>
                        @error('photo_ketua') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="photo_wakil" class="form-label">Foto Calon Wakil</label>
                        <input type="file" name="photo_wakil" id="photo_wakil" class="form-control @error('photo_wakil') is-invalid @enderror" required>
                        @error('photo_wakil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="vision" class="form-label">Visi</label>
                <textarea name="vision" id="vision" rows="4" class="form-control @error('vision') is-invalid @enderror" required>{{ old('vision') }}</textarea>
                @error('vision') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="mission" class="form-label">Misi</label>
                <textarea name="mission" id="mission" rows="6" class="form-control @error('mission') is-invalid @enderror" required>{{ old('mission') }}</textarea>
                @error('mission') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.osis-candidates.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kandidat</button>
            </div>
        </form>
    </div>
</div>
@endsection
