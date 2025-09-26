@extends('layouts.admin')

@section('title', 'Tambah Kandidat OSIS')

@section('content')
<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #224abe;
        --danger-color: #e74a3b;
        --warning-color: #f6c23e;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --light-bg: #f8f9fc;
        --card-shadow: 0 4px 15px rgba(0,0,0,0.07);
    }

    .form-header {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(45deg);
    }

    .form-header h4 {
        position: relative;
        z-index: 1;
        margin: 0;
        font-weight: 700;
    }

    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .form-section {
        padding: 1.5rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        color: var(--primary-color);
        margin-right: 0.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #d1d3e2;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }

    .input-group-text {
        background-color: var(--light-bg);
        border: 1px solid #d1d3e2;
        border-radius: 8px 0 0 8px;
        color: var(--primary-color);
    }

    .form-control:focus + .input-group-text {
        border-color: var(--primary-color);
    }

    .file-upload {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-upload input[type=file] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-upload-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border: 2px dashed #d1d3e2;
        border-radius: 8px;
        background-color: var(--light-bg);
        color: #5a5c69;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .file-upload:hover .file-upload-label {
        border-color: var(--primary-color);
        background-color: rgba(78, 115, 223, 0.05);
    }

    .file-upload-label i {
        font-size: 2rem;
        margin-right: 0.75rem;
        color: var(--primary-color);
    }

    .image-preview {
        margin-top: 1rem;
        text-align: center;
    }

    .image-preview img {
        max-width: 150px;
        max-height: 150px;
        border-radius: 8px;
        box-shadow: var(--card-shadow);
    }

    .form-actions {
        padding: 1.5rem;
        background-color: var(--light-bg);
        border-top: 1px solid #e3e6f0;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .btn i {
        margin-right: 0.5rem;
    }

    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #717384;
        border-color: #717384;
        color: white;
        transform: translateY(-2px);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background-color: #e3e6f0;
    }

    .divider span {
        padding: 0 1rem;
        color: #858796;
        font-size: 0.9rem;
    }

    .required-field::after {
        content: " *";
        color: var(--danger-color);
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: var(--danger-color);
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
    }

    .form-control.is-invalid:focus {
        border-color: var(--danger-color);
        box-shadow: 0 0 0 0.25rem rgba(231, 74, 59, 0.25);
    }
</style>

<div class="form-card">
    <div class="form-header">
        <h4>Tambah Kandidat OSIS Baru</h4>
    </div>
    
    <form action="{{ route('admin.osis-candidates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Bagian Informasi Kandidat -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-people-fill"></i> Informasi Kandidat
            </h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name_ketua" class="form-label required-field">Nama Calon Ketua</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name_ketua" id="name_ketua" class="form-control @error('name_ketua') is-invalid @enderror" value="{{ old('name_ketua') }}" required>
                    </div>
                    @error('name_ketua') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="name_wakil" class="form-label required-field">Nama Calon Wakil</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name_wakil" id="name_wakil" class="form-control @error('name_wakil') is-invalid @enderror" value="{{ old('name_wakil') }}" required>
                    </div>
                    @error('name_wakil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="photo_ketua" class="form-label required-field">Foto Calon Ketua</label>
                    <div class="file-upload">
                        <input type="file" name="photo_ketua" id="photo_ketua" class="@error('photo_ketua') is-invalid @enderror" required onchange="previewImage(this, 'preview-ketua')">
                        <label for="photo_ketua" class="file-upload-label">
                            <i class="bi bi-cloud-upload"></i>
                            <span>Pilih Foto atau Seret ke Sini</span>
                        </label>
                    </div>
                    @error('photo_ketua') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="preview-ketua" class="image-preview"></div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="photo_wakil" class="form-label required-field">Foto Calon Wakil</label>
                    <div class="file-upload">
                        <input type="file" name="photo_wakil" id="photo_wakil" class="@error('photo_wakil') is-invalid @enderror" required onchange="previewImage(this, 'preview-wakil')">
                        <label for="photo_wakil" class="file-upload-label">
                            <i class="bi bi-cloud-upload"></i>
                            <span>Pilih Foto atau Seret ke Sini</span>
                        </label>
                    </div>
                    @error('photo_wakil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="preview-wakil" class="image-preview"></div>
                </div>
            </div>
        </div>
        
        <!-- Bagian Visi & Misi -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-lightbulb"></i> Visi & Misi
            </h5>
            
            <div class="mb-3">
                <label for="vision" class="form-label required-field">Visi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-eye"></i></span>
                    <textarea name="vision" id="vision" rows="3" class="form-control @error('vision') is-invalid @enderror" required>{{ old('vision') }}</textarea>
                </div>
                @error('vision') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="mission" class="form-label required-field">Misi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-flag"></i></span>
                    <textarea name="mission" id="mission" rows="4" class="form-control @error('mission') is-invalid @enderror" required>{{ old('mission') }}</textarea>
                </div>
                @error('mission') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        
        <!-- Tombol Aksi -->
        <div class="form-actions">
            <a href="{{ route('admin.osis-candidates.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Simpan Kandidat
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        
        // Hapus preview sebelumnya jika ada
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection