@extends('layouts.admin')

@section('title', 'Edit Data Nasabah')
@section('page-title', 'Edit Nasabah')

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

    .badge-modern {
        padding: 0.4em 0.8em;
        font-weight: 600;
        letter-spacing: 0.3px;
        font-size: 0.75rem;
    }

    /* Warna Kustom Modern */
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }

    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }

    .text-rose { color: #f43f5e !important; }
    .bg-rose-lt { background-color: rgba(244, 63, 94, 0.1) !important; color: #f43f5e !important; }

    .text-purple { color: #8b5cf6 !important; }
    .bg-purple-lt { background-color: rgba(139, 92, 246, 0.1) !important; color: #8b5cf6 !important; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">

        <!-- Header Informasi Singkat (Profil Card) -->
        <div class="card card-modern mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="position-relative me-4">
                        <div class="avatar avatar-xl rounded-circle shadow-sm border border-2 border-white"
                             style="width: 80px; height: 80px; background-image: url({{ $nasabah->foto ? asset('storage/'.$nasabah->foto) : 'https://ui-avatars.com/api/?name='.urlencode($nasabah->nama_lengkap).'&background=10b981&color=fff' }})">
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-1 fw-bold text-dark fs-4">{{ $nasabah->nama_lengkap }}</h3>
                        <div class="text-slate-500 small d-flex align-items-center gap-2 flex-wrap">
                            <span class="d-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                {{ $nasabah->user->email ?? '-' }}
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="d-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                {{ $nasabah->no_telepon ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="d-none d-md-block">
                        @if($nasabah->status_akun == 'active')
                            <span class="badge bg-emerald-lt badge-modern rounded-pill px-3 py-2 border border-emerald">Akun Aktif</span>
                        @elseif($nasabah->status_akun == 'pending')
                            <span class="badge bg-amber-lt badge-modern rounded-pill px-3 py-2 border border-amber">Pending</span>
                        @elseif($nasabah->status_akun == 'verified')
                            <span class="badge bg-blue-lt badge-modern rounded-pill px-3 py-2 border border-blue">Terverifikasi</span>
                        @else
                            <span class="badge bg-rose-lt badge-modern rounded-pill px-3 py-2 border border-rose">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Utama -->
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-blue-lt rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-4">Formulir Perubahan Data</h3>
                        <p class="text-slate-500 small m-0 mt-1">Perbarui data profil dan status administrasi nasabah.</p>
                    </div>
                </div>
                <div class="card-options">
                    <a href="{{ route('admin.nasabah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 d-none d-sm-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('admin.nasabah.update', $nasabah->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Section: Data Pribadi -->
                    <div class="mb-5">
                        <h4 class="section-title">
                            <div class="icon-wrapper bg-emerald-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            Informasi Pribadi
                        </h4>
                        <div class="row g-4">
                            <!-- Nama Lengkap -->
                            <div class="col-md-6">
                                <label class="form-label required">Nama Lengkap</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </span>
                                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $nasabah->nama_lengkap) }}" required>
                                </div>
                                @error('nama_lengkap') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>

                            <!-- No Telepon -->
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon (WhatsApp)</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    </span>
                                    <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon', $nasabah->no_telepon) }}">
                                </div>
                                @error('no_telepon') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>

                            <!-- No KTP / NIK -->
                            <div class="col-md-6">
                                <label class="form-label">No. KTP (NIK)</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="14" x="3" y="5" rx="2" ry="2"/><path d="M7 15h4M15 15h2M7 11h2M15 11h2"/></svg>
                                    </span>
                                    <input type="text" name="no_ktp" class="form-control @error('no_ktp') is-invalid @enderror" value="{{ old('no_ktp', $nasabah->no_ktp) }}" maxlength="16" placeholder="16 digit NIK">
                                </div>
                                @error('no_ktp') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-7">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" style="resize: none;">{{ old('alamat', $nasabah->alamat) }}</textarea>
                                @error('alamat') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                            </div>

                            <!-- Foto Profil -->
                            <div class="col-md-5">
                                <label class="form-label">Foto Profil</label>
                                <div class="text-center">
                                    @if($nasabah->foto)
                                        <img src="{{ asset('storage/'.$nasabah->foto) }}" alt="Foto Profil"
                                             class="rounded-circle border shadow-sm object-fit-cover mb-3"
                                             style="width: 100px; height: 100px;">
                                    @else
                                        <div class="rounded-circle border shadow-sm d-flex align-items-center justify-content-center mx-auto mb-3 text-white fw-bold"
                                             style="width: 100px; height: 100px; background: linear-gradient(135deg, #10b981, #059669); font-size: 36px;">
                                            {{ strtoupper(substr($nasabah->nama_lengkap, 0, 1)) }}
                                        </div>
                                    @endif
                                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg, image/png, image/jpg" id="input-foto-profil" onchange="previewFotoProfil(this)">
                                    @error('foto') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror
                                    <small class="text-muted d-block mt-2">JPG/PNG maks 2MB</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Administrasi -->
                    <div class="mb-2">
                        <h4 class="section-title">
                            <div class="icon-wrapper bg-purple-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                            Pengaturan Administrasi
                        </h4>

                        <div class="row g-4">
                            <!-- Status Akun -->
                            <div class="col-12">
                                <label class="form-label required">Status Akun Nasabah</label>
                                <select name="status_akun" class="form-select @error('status_akun') is-invalid @enderror">
                                    <option value="pending" {{ $nasabah->status_akun == 'pending' ? 'selected' : '' }}>
                                        🟡 Pending (Menunggu Verifikasi)
                                    </option>
                                    <option value="verified" {{ $nasabah->status_akun == 'verified' ? 'selected' : '' }}>
                                        🔵 Verified (Terverifikasi)
                                    </option>
                                    <option value="active" {{ $nasabah->status_akun == 'active' ? 'selected' : '' }}>
                                        🟢 Aktif (Dapat Melakukan Transaksi)
                                    </option>
                                    <option value="nonaktif" {{ $nasabah->status_akun == 'nonaktif' ? 'selected' : '' }}>
                                        🔴 Tidak Aktif (Akun Diblokir)
                                    </option>
                                </select>
                                @error('status_akun') <div class="invalid-feedback d-block fw-medium">{{ $message }}</div> @enderror

                                <!-- Info Alert -->
                                <div class="mt-3 p-3 bg-rose-lt border border-rose rounded-3 d-flex align-items-start" style="background-color: #fff1f2; border-color: #fecdd3 !important;">
                                    <div class="text-rose me-3 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-rose mb-1">Perhatian!</h6>
                                        <p class="text-slate-600 small mb-0">Mengubah status ke <strong>Tidak Aktif</strong> akan mencegah nasabah ini untuk melakukan login ke dalam aplikasi.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan Admin -->
                            <div class="col-12">
                                <label class="form-label">Catatan Admin (Internal)</label>
                                <textarea name="catatan_admin" class="form-control shadow-sm" rows="3" placeholder="Tambahkan catatan khusus untuk nasabah ini (hanya dapat dilihat oleh Admin)" style="resize: none;">{{ old('catatan_admin', $nasabah->catatan_admin) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-3 flex-wrap">
                        <a href="{{ route('admin.nasabah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary shadow-sm rounded-pill fw-bold px-4 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
