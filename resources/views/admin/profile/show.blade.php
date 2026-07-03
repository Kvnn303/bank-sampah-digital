@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan & Keamanan Profil')

@push('styles')
<style>
    /* Styling Modern UI/UX Pro Max */
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        transition: all 0.25s ease;
        background: #ffffff;
    }

    .form-label {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.82rem;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 0.75rem 1.1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        color: #0f172a;
    }

    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
        outline: none;
    }

    .icon-shape {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Warna Kustom Modern */
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.12) !important; color: #059669 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.12) !important; color: #2563eb !important; }

    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background-color: rgba(245, 158, 11, 0.12) !important; color: #d97706 !important; }

    .text-purple { color: #8b5cf6 !important; }
    .bg-purple-lt { background-color: rgba(139, 92, 246, 0.12) !important; color: #7c3aed !important; }

    /* Custom Photo Upload */
    .photo-upload-wrapper {
        position: relative;
        display: inline-block;
    }
    .photo-upload-overlay {
        position: absolute;
        bottom: 4px;
        right: 4px;
        background: #10b981;
        color: white;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
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
        margin-bottom: 12px;
        font-size: 0.88rem;
        color: #334155;
        font-weight: 600;
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

    .audit-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
    }
</style>
@endpush

@section('content')

<!-- BANNER STATUS MONITORING PERUBAHAN (AUDIT TRAIL) -->
<div class="card card-modern mb-4 border-start border-4 border-success" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);">
    <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-shape bg-emerald-lt text-emerald" style="width: 50px; height: 50px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    Log Pemantauan Aktivitas Akun
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill small fw-bold px-3">Keamanan Aktif</span>
                </h5>
                <p class="mb-0 text-slate-600 small">Sistem mendata setiap perubahan profil & kata sandi secara real-time demi integritas data Bank Sampah.</p>
            </div>
        </div>
        <div class="bg-white p-3 rounded-4 border shadow-sm text-md-end flex-shrink-0">
            <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Perubahan Terakhir Terpantau</div>
            <div class="fw-bold text-dark fs-6 d-flex align-items-center justify-content-md-end gap-1 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ $admin->updated_at ? \Carbon\Carbon::parse($admin->updated_at)->locale('id')->translatedFormat('d F Y • H:i') . ' WIB' : 'Belum Ada Perubahan' }}
            </div>
            <div class="text-success small fw-semibold mt-1">
                ({{ $admin->updated_at ? \Carbon\Carbon::parse($admin->updated_at)->locale('id')->diffForHumans() : '-' }})
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    <!-- Kolom Kiri: Kartu Identitas Profil & Audit Keamanan -->
    <div class="col-lg-4 order-first order-lg-last">

        <!-- Kartu Identitas Profil -->
        <div class="card card-modern text-center position-relative overflow-hidden">
            <!-- Dekorasi Latar -->
            <div style="height: 120px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);"></div>

            <div class="card-body pt-0">
                <div class="photo-upload-wrapper mt-n5 mb-3">
                    @if($admin->foto)
                        <img src="{{ asset('storage/' . $admin->foto) }}" alt="Profil" id="preview-photo-card" class="avatar avatar-xl rounded-circle shadow-lg bg-white" style="width:120px; height:120px; object-fit:cover; border: 4px solid #ffffff;">
                    @else
                        <div id="preview-photo-card" class="avatar avatar-xl rounded-circle shadow-lg bg-white text-slate-800 fw-bold d-flex align-items-center justify-content-center" style="width:120px; height:120px; font-size:2.8rem; border: 4px solid #ffffff;">
                            {{ Str::upper(Str::substr($admin->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h3 class="m-0 mb-1 fw-bold text-dark fs-4">{{ $admin->name }}</h3>
                <div class="text-slate-500 mb-3 fw-medium">{{ $admin->email }}</div>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span class="badge bg-emerald-lt text-emerald px-3 py-2 rounded-pill fw-bold border border-emerald border-opacity-25 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        Administrator BSU
                    </span>
                    <span class="badge bg-blue-lt text-blue-modern px-3 py-2 rounded-pill fw-bold border border-blue border-opacity-25 d-flex align-items-center">
                        Terverifikasi
                    </span>
                </div>

                <!-- Box Detail Audit Waktu -->
                <div class="audit-box text-start mb-3">
                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                        <span class="text-slate-500 small fw-medium">Waktu Registrasi Akun</span>
                        <span class="fw-bold text-dark small">{{ $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-slate-500 small fw-medium">Terakhir Diperbarui</span>
                        <span class="fw-bold text-success small">{{ $admin->updated_at ? $admin->updated_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Sesi Perangkat Aktif -->
        <div class="card card-modern mt-4">
            <div class="card-header bg-white border-bottom p-3 px-4">
                <h4 class="card-title fw-bold text-dark m-0 fs-6 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    Sesi Perangkat Saat Ini
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-emerald-lt me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    </div>
                    <div>
                        <div class="fw-bold text-dark fs-6">IP: {{ request()->ip() }}</div>
                        <div class="text-success small fw-semibold d-flex align-items-center mt-1">
                            <span class="d-inline-block bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                            Sedang Aktif Mengakses Sistem
                        </div>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-slate-100 text-slate-500 font-monospace" style="font-size: 0.72rem; line-height: 1.5; word-break: break-all;">
                    {{ request()->userAgent() }}
                </div>
            </div>
        </div>

        <!-- Kartu Hak Akses -->
        <div class="card card-modern mt-4 border border-slate-200">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape bg-purple-lt me-3" style="width: 36px; height: 36px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M8 11h8"/><path d="M12 7v8"/></svg>
                    </div>
                    <span class="fw-bold text-dark fs-6">Kewenangan Sistem</span>
                </div>
                <ul class="privilege-list">
                    <li>Mengelola Data Nasabah & Rekening</li>
                    <li>Verifikasi Penarikan & Setoran Saldo</li>
                    <li>Validasi Transaksi Tabungan Masuk</li>
                    <li>Update Harga & Jenis Master Sampah</li>
                    <li>Mencetak Laporan Keuangan & Audit</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Form Profil & Password -->
    <div class="col-lg-8">

        <!-- Form Ubah Profil -->
        <div class="card card-modern mb-4">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-blue-lt me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-4">Informasi Akun & Identitas</h3>
                        <p class="text-slate-500 small m-0 mt-1">Setiap kali disimpan, sistem otomatis mencatat tanggal perbaruan terbaru.</p>
                    </div>
                </div>
                <span class="badge bg-light text-muted border rounded-pill px-3 py-2 d-none d-md-block">ID: #{{ $admin->id }}</span>
            </div>

            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Upload Foto Custom -->
                    <div class="d-flex align-items-center mb-5 pb-4 border-bottom border-slate-100">
                        <div class="photo-upload-wrapper me-4">
                            @if($admin->foto)
                                <img src="{{ asset('storage/' . $admin->foto) }}" alt="Profil" id="preview-photo-form" class="avatar rounded-circle shadow-sm border border-2" style="width: 90px; height: 90px; object-fit: cover;">
                            @else
                                <div id="preview-photo-form" class="avatar rounded-circle shadow-sm bg-slate-100 text-slate-700 fw-bold d-flex align-items-center justify-content-center border border-2" style="width: 90px; height: 90px; font-size: 2.2rem;">
                                    {{ Str::upper(Str::substr($admin->name, 0, 1)) }}
                                </div>
                            @endif
                            <label for="fotoInput" class="photo-upload-overlay" title="Pilih Foto Baru">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                            </label>
                            <input type="file" name="foto" id="fotoInput" class="file-input-hidden @error('foto') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(this)">
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Foto Profil Administrator</h5>
                            <p class="text-slate-500 small mb-1">Format yang disarankan: JPG, JPEG, atau PNG dengan rasio 1:1. Maksimal 2MB.</p>
                            <span id="photoBadgeNotice" class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill small d-none">
                                ⚠️ Foto baru dipilih (klik Simpan untuk menerapkan)
                            </span>
                            @error('foto')
                                <div class="text-danger small fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label required">Nama Lengkap Pengurus</label>
                            <input type="text" name="name" class="form-control fw-bold text-dark @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Alamat Email Resmi</label>
                            <input type="email" name="email" class="form-control fw-bold text-dark @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span class="small text-muted">💡 Sistem akan merekam jejak audit (*audit timestamp*) setelah tombol simpan ditekan.</span>
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm px-5 py-2 d-inline-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Simpan Perubahan Profil
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <h3 class="card-title fw-bold text-dark m-0 fs-4">Ganti Password Keamanan</h3>
                    <p class="text-slate-500 small m-0 mt-1">Perbarui kata sandi secara berkala untuk menjaga kredensial sistem Bank Sampah.</p>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label required">Password Saat Ini (Verifikasi)</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan kata sandi aktif saat ini..." required>
                            @error('current_password')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kata sandi baru..." required>
                            <small class="text-slate-500 mt-2 d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                Minimal 8 karakter kombinasi huruf & angka.
                            </small>
                            @error('password')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Ulangi kata sandi baru..." required>
                            @error('password_confirmation')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-dark rounded-pill fw-bold shadow-sm px-5 py-2 d-inline-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                Perbarui Keamanan Password
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
                newImgForm.className = 'avatar rounded-circle shadow-sm border border-2';
                newImgForm.style.cssText = 'width: 90px; height: 90px; object-fit: cover;';

                // Buat tag image baru untuk card
                const newImgCard = document.createElement('img');
                newImgCard.src = e.target.result;
                newImgCard.id = 'preview-photo-card';
                newImgCard.className = 'avatar avatar-xl rounded-circle shadow-lg bg-white';
                newImgCard.style.cssText = 'width:120px; height:120px; object-fit:cover; border: 4px solid #ffffff;';

                // Ganti elemen lama dengan gambar baru
                previewForm.parentNode.replaceChild(newImgForm, previewForm);
                previewCard.parentNode.replaceChild(newImgCard, previewCard);

                // Tampilkan notifikasi badge bahwa foto belum disimpan
                const notice = document.getElementById('photoBadgeNotice');
                if(notice) notice.classList.remove('d-none');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
