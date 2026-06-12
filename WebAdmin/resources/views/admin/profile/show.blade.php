@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Profil')

@push('styles')
<style>
    /* Styling Modern yang Seragam */
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

    .form-control {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .icon-shape {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Warna Kustom Modern */
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }

    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }

    .text-slate-custom { color: #475569 !important; }
    .bg-slate-custom-lt { background-color: #f1f5f9 !important; color: #475569 !important; }

    /* Custom Photo Upload */
    .photo-upload-wrapper {
        position: relative;
        display: inline-block;
    }
    .photo-upload-overlay {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #10b981;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    }
    .photo-upload-overlay:hover {
        transform: scale(1.1);
        background: #059669;
    }
    .file-input-hidden {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* List Hak Akses */
    .privilege-list {
        padding-left: 0;
        list-style: none;
        margin-bottom: 0;
    }
    .privilege-list li {
        position: relative;
        padding-left: 28px;
        margin-bottom: 10px;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 500;
    }
    .privilege-list li:last-child {
        margin-bottom: 0;
    }
    .privilege-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 2px;
        width: 18px;
        height: 18px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2310b981' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
    }
</style>
@endpush

@section('content')
<div class="row g-4">

    <!-- Kolom Kanan: Card Info Singkat & Sesi Aktif (Di HP muncul paling atas) -->
    <div class="col-lg-4 order-first order-lg-last">

        <!-- Kartu Identitas Profil -->
        <div class="card card-modern text-center position-relative overflow-hidden">
            <!-- Dekorasi Latar -->
            <div class="bg-primary" style="height: 110px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);"></div>

            <div class="card-body pt-0">
                <div class="photo-upload-wrapper mt-n5 mb-3">
                    @if($admin->foto)
                        <img src="{{ asset('storage/' . $admin->foto) }}" alt="Profil" id="preview-photo-card" class="avatar avatar-xl rounded-circle shadow-sm bg-white" style="width:110px; height:110px; object-fit:cover; border: 4px solid #ffffff;">
                    @else
                        <div id="preview-photo-card" class="avatar avatar-xl rounded-circle shadow-sm bg-white text-slate-700 fw-bold d-flex align-items-center justify-content-center" style="width:110px; height:110px; font-size:2.5rem; border: 4px solid #ffffff;">
                            {{ Str::upper(Str::substr($admin->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h3 class="m-0 mb-1 fw-bold text-dark fs-4">{{ $admin->name }}</h3>
                <div class="text-slate-500 mb-3">{{ $admin->email }}</div>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span class="badge bg-emerald-lt text-emerald px-3 py-2 rounded-pill fw-semibold border border-emerald d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        Administrator
                    </span>
                    <span class="badge bg-slate-custom-lt px-3 py-2 rounded-pill fw-semibold border d-flex align-items-center">
                        Terverifikasi
                    </span>
                </div>

                <div class="pt-3 border-top border-slate-100 d-flex justify-content-between align-items-center text-start">
                    <div>
                        <div class="text-slate-400 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Bergabung Sejak</div>
                        <div class="fw-bold text-dark">{{ $admin->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="icon-shape bg-slate-50 border">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Sesi Perangkat Aktif -->
        <div class="card card-modern mt-4">
            <div class="card-header bg-white border-bottom p-3 px-4">
                <h4 class="card-title fw-bold text-dark m-0 fs-6 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Sesi Perangkat Saat Ini
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-emerald-lt me-3" style="width: 42px; height: 42px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6">IP: {{ request()->ip() }}</div>
                        <div class="text-slate-500 small d-flex align-items-center mt-1">
                            <span class="badge bg-emerald rounded-circle p-1 me-1"></span>
                            Sedang Aktif
                        </div>
                    </div>
                </div>
                <!-- Menampilkan info Browser/Sistem Operasi secara rapi -->
                <div class="mt-3 pt-3 border-top border-slate-100 text-slate-400 font-monospace" style="font-size: 0.7rem; line-height: 1.5; word-break: break-all;">
                    {{ request()->userAgent() }}
                </div>
            </div>
        </div>

        <!-- Kartu Hak Akses -->
        <div class="card card-modern mt-4 bg-slate-50 border-0 shadow-none">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-500 me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M8 11h8"/><path d="M12 7v8"/></svg>
                    <span class="fw-bold text-dark">Hak Akses Sistem</span>
                </div>
                <ul class="privilege-list">
                    <li>Mengelola Data Nasabah & Rekening</li>
                    <li>Menyetujui Penarikan Dana</li>
                    <li>Validasi Transaksi Tabungan Masuk</li>
                    <li>Update Harga & Jenis Master Sampah</li>
                    <li>Mencetak Laporan Keuangan & Audit</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom Kiri: Form Profil & Password -->
    <div class="col-lg-8">

        <!-- Form Ubah Profil -->
        <div class="card card-modern mb-4">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                <div class="icon-shape bg-blue-lt me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <h3 class="card-title fw-bold text-dark m-0 fs-4">Informasi Akun</h3>
                    <p class="text-slate-500 small m-0 mt-1">Perbarui data diri dan foto profil Anda di sini.</p>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Upload Foto Custom -->
                    <div class="d-flex align-items-center mb-5 pb-4 border-bottom border-slate-100">
                        <div class="photo-upload-wrapper me-4">
                            @if($admin->foto)
                                <img src="{{ asset('storage/' . $admin->foto) }}" alt="Profil" id="preview-photo-form" class="avatar rounded-circle shadow-sm" style="width: 85px; height: 85px; object-fit: cover;">
                            @else
                                <div id="preview-photo-form" class="avatar rounded-circle shadow-sm bg-slate-100 text-slate-500 fw-bold d-flex align-items-center justify-content-center" style="width: 85px; height: 85px; font-size: 2rem;">
                                    {{ Str::upper(Str::substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                            <label for="fotoInput" class="photo-upload-overlay" title="Pilih Foto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                            </label>
                            <input type="file" name="foto" id="fotoInput" class="file-input-hidden @error('foto') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(this)">
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Unggah Foto Profil Baru</h5>
                            <p class="text-slate-500 small mb-0">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</p>
                            @error('foto')
                                <div class="text-danger small fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label required">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control fw-semibold text-dark @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Alamat Email</label>
                            <input type="email" name="email" class="form-control fw-semibold text-dark @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm px-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Ubah Password -->
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                <div class="icon-shape bg-amber-lt me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <h3 class="card-title fw-bold text-dark m-0 fs-4">Ganti Password</h3>
                    <p class="text-slate-500 small m-0 mt-1">Pastikan kredensial akun Anda selalu aman.</p>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label required">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="••••••••" required>
                            @error('current_password')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            <small class="text-slate-500 mt-2 d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                Minimal 8 karakter.
                            </small>
                            @error('password')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="••••••••" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-dark rounded-pill fw-bold shadow-sm px-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                                Perbarui Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk Live Preview Gambar yang diunggah
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Tangkap elemen preview form dan card
                const previewForm = document.getElementById('preview-photo-form');
                const previewCard = document.getElementById('preview-photo-card');

                // Buat tag image baru untuk form
                const newImgForm = document.createElement('img');
                newImgForm.src = e.target.result;
                newImgForm.id = 'preview-photo-form';
                newImgForm.className = 'avatar rounded-circle shadow-sm';
                newImgForm.style.cssText = 'width: 85px; height: 85px; object-fit: cover;';

                // Buat tag image baru untuk card
                const newImgCard = document.createElement('img');
                newImgCard.src = e.target.result;
                newImgCard.id = 'preview-photo-card';
                newImgCard.className = 'avatar avatar-xl rounded-circle shadow-sm bg-white';
                newImgCard.style.cssText = 'width:110px; height:110px; object-fit:cover; border: 4px solid #ffffff;';

                // Ganti elemen lama dengan gambar baru
                previewForm.parentNode.replaceChild(newImgForm, previewForm);
                previewCard.parentNode.replaceChild(newImgCard, previewCard);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
