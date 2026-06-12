@extends('layouts.admin')

@section('title', 'Tambah Nasabah Baru')
@section('page-title', 'Tambah Nasabah')

@push('styles')
<style>
    /* Styling Modern untuk Form */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.85rem;
        letter-spacing: 0.3px;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .input-icon-addon {
        color: #94a3b8;
    }

    .section-title {
        color: #0f172a;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 20px;
    }

    .icon-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    .upload-preview-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }

    .upload-preview-area:hover {
        border-color: #10b981;
        background-color: #f0fdf4;
    }

    /* Warna Kustom */
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }

    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-emerald-lt rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-3">Form Registrasi Nasabah</h3>
                        <p class="text-slate-500 small m-0 mt-1">Lengkapi data di bawah ini untuk menambahkan nasabah baru.</p>
                    </div>
                </div>
                <div class="card-options">
                    <a href="{{ route('admin.nasabah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('admin.nasabah.store') }}" enctype="multipart/form-data" id="form-nasabah">
                    @csrf

                    <!-- Section: Data Pribadi -->
                    <div class="mb-5">
                        <h4 class="section-title">
                            <div class="icon-wrapper bg-blue-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            Data Pribadi
                        </h4>
                        <div class="row g-4">
                            <!-- Nama Lengkap -->
                            <div class="col-md-6">
                                <label class="form-label required">Nama Lengkap</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </span>
                                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" placeholder="Sesuai KTP" value="{{ old('nama_lengkap') }}" required>
                                </div>
                                @error('nama_lengkap') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>

                            <!-- No KTP -->
                            <div class="col-md-6">
                                <label class="form-label">No KTP (NIK)</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="14" x="3" y="5" rx="2" ry="2"/><path d="M7 15h4M15 15h2M7 11h2M15 11h2"/></svg>
                                    </span>
                                    <input type="text" name="no_ktp" class="form-control @error('no_ktp') is-invalid @enderror" placeholder="16 digit NIK" value="{{ old('no_ktp') }}" maxlength="16">
                                </div>
                                @error('no_ktp') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Kontak & Akun -->
                    <div class="mb-5">
                        <h4 class="section-title">
                            <div class="icon-wrapper bg-amber-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </div>
                            Kontak & Kredensial Akun
                        </h4>
                        <div class="row g-4">
                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label required">Alamat Email</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>
                                    </span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="email@domain.com" value="{{ old('email') }}" required>
                                </div>
                                @error('email') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>

                            <!-- No Telepon -->
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon (WhatsApp)</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    </span>
                                    <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" placeholder="08xxxxxxxxxx" value="{{ old('no_telepon') }}">
                                </div>
                                @error('no_telepon') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Info Password Modern -->
                        <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded-3 d-flex align-items-start">
                            <div class="text-blue-modern me-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Informasi Pembuatan Akun Otomatis</h5>
                                <p class="text-slate-600 small mb-0">
                                    Nasabah dapat login menggunakan <strong>Email</strong>. Password default akan di-generate otomatis mengikuti urutan:
                                    <span class="badge bg-slate-200 text-slate-700 mx-1">1. No. Telepon</span> ➔
                                    <span class="badge bg-slate-200 text-slate-700 mx-1">2. No. KTP</span> ➔
                                    <span class="badge bg-slate-200 text-slate-700 mx-1">3. banksampah123</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Alamat & Dokumen -->
                    <div class="mb-2">
                        <h4 class="section-title">
                            <div class="icon-wrapper bg-emerald-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            Alamat & Dokumen Pendukung
                        </h4>

                        <div class="row g-4">
                            <!-- Alamat -->
                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap Tempat Tinggal</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Masukkan nama jalan, RT/RW, desa/kelurahan, kecamatan..." style="resize: none;">{{ old('alamat') }}</textarea>
                                @error('alamat') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>

                            <!-- Foto Profil -->
                            <div class="col-md-6">
                                <label class="form-label mb-2">Foto Profil <span class="text-muted fw-normal">(Opsional)</span></label>
                                <div class="row g-3">
                                    <div class="col-7">
                                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg, image/png, image/jpg" id="input-foto-profil" onchange="previewFotoProfil(this)">
                                        @error('foto') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                                        <div class="text-muted small mt-2 d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                            JPG/PNG maks 2MB
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="upload-preview-area text-center d-flex align-items-center justify-content-center overflow-hidden position-relative" style="height: 100px;" id="preview-container-profil">
                                            <div id="placeholder-profil" class="text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="d-block mx-auto mb-1 text-slate-300" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                                                <span class="small fw-medium">Preview</span>
                                            </div>
                                            <img id="preview-foto-profil" src="#" alt="Preview" class="img-fluid w-100 h-100 object-fit-cover rounded-3" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Foto KTP -->
                            <div class="col-md-6">
                                <label class="form-label mb-2">Upload Foto KTP <span class="text-muted fw-normal">(Opsional)</span></label>
                                <div class="row g-3">
                                    <div class="col-md-7 col-lg-8">
                                        <input type="file" name="foto_ktp" class="form-control form-control-lg @error('foto_ktp') is-invalid @enderror" accept="image/jpeg, image/png, image/jpg" id="input-foto" onchange="previewImage(this)">
                                        @error('foto_ktp') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                                        <div class="text-muted small mt-2 d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                            Format yang diizinkan: JPG, JPEG, PNG. Ukuran maksimal 2MB.
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-lg-4">
                                        <div class="upload-preview-area text-center d-flex align-items-center justify-content-center overflow-hidden position-relative" style="height: 160px;" id="preview-container">
                                            <div id="placeholder-text" class="text-slate-400 p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 d-block mx-auto text-slate-300" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                                <span class="small fw-medium">Preview Gambar</span>
                                            </div>
                                            <img id="preview-image" src="#" alt="Preview KTP" class="img-fluid w-100 h-100 object-fit-cover" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.nasabah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary shadow-sm rounded-pill fw-bold px-4 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Data Nasabah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('preview-image');
        const placeholder = document.getElementById('placeholder-text');
        const container = document.getElementById('preview-container');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.setAttribute('src', e.target.result);
                preview.style.display = 'block';
                placeholder.style.display = 'none';

                container.style.borderStyle = 'solid';
                container.style.borderColor = '#e2e8f0';
                container.style.backgroundColor = '#ffffff';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            preview.removeAttribute('src');
            placeholder.style.display = 'block';

            container.style.borderStyle = 'dashed';
            container.style.borderColor = '#cbd5e1';
            container.style.backgroundColor = '#f8fafc';
        }
    }

    function previewFotoProfil(input) {
        const preview = document.getElementById('preview-foto-profil');
        const placeholder = document.getElementById('placeholder-profil');
        const container = document.getElementById('preview-container-profil');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.setAttribute('src', e.target.result);
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                container.style.borderStyle = 'solid';
                container.style.borderColor = '#10b981';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
            preview.removeAttribute('src');
            placeholder.style.display = 'block';
            container.style.borderStyle = 'dashed';
            container.style.borderColor = '#cbd5e1';
        }
    }
</script>
@endpush
