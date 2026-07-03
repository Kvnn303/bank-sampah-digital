@extends('layouts.admin')

@section('title', 'Tambah Admin Baru')
@section('page-title', 'Registrasi Administrator Baru')

@push('styles')
<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
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
    .guide-box {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        color: #ffffff;
        padding: 2rem;
    }
    .guide-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 1.25rem;
    }
    .guide-item:last-child {
        margin-bottom: 0;
    }
    .guide-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #34d399;
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.kelola-admin.index') }}" class="text-slate-500 small fw-bold text-decoration-none d-inline-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Admin
        </a>
    </div>
    <span class="badge bg-emerald-lt text-emerald px-3 py-2 rounded-pill fw-bold border border-emerald border-opacity-25">
        ⚡ Hak Akses Penuh (Super User)
    </span>
</div>

<div class="row g-4">
    {{-- Kolom Kiri: Form Input --}}
    <div class="col-lg-7">
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-emerald-lt text-emerald me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-4">Formulir Akun Administrator</h3>
                        <p class="text-slate-500 small m-0 mt-1">Lengkapi kredensial pengelola baru untuk sistem Bank Sampah.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <div class="small fw-semibold">Mohon periksa kembali isian formulir. Terdapat beberapa data yang belum sesuai kriteria.</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.kelola-admin.store') }}" id="formTambahAdmin">
                    @csrf

                    <div class="row g-4">
                        {{-- Nama Lengkap --}}
                        <div class="col-12">
                            <label for="nameInput" class="form-label required">Nama Lengkap Pengurus</label>
                            <input type="text" id="nameInput" name="name" class="form-control fw-bold text-dark @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Ahmad Fauzi, S.Kom" autocomplete="name" maxlength="100" required autofocus>
                            @error('name')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-12">
                            <label for="emailInput" class="form-label required">Alamat Email Resmi (Username Login)</label>
                            <input type="email" id="emailInput" name="email" class="form-control fw-bold text-dark @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="admin.subang@banksampah.id" autocomplete="email" required>
                            <span class="text-slate-400 small mt-1 d-block">💡 Pastikan email aktif & valid karena akan digunakan sebagai identitas utama saat masuk ke dalam dasbor.</span>
                            @error('email')
                                <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded-4 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-shape bg-warning bg-opacity-10 text-warning" style="width: 40px; height: 40px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">Password Awal (Default Sistem)</div>
                                        <div class="font-monospace text-success fw-bold">admin123</div>
                                    </div>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill small px-3 py-1">
                                    Wajib diganti saat login pertama
                                </span>
                            </div>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.kelola-admin.index') }}" class="btn btn-light border rounded-pill px-4 fw-bold">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm d-inline-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                                Daftarkan Administrator Baru
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Panduan & Informasi Eksekutif --}}
    <div class="col-lg-5">
        <div class="guide-box h-100 shadow-lg position-relative overflow-hidden">
            <div style="position: absolute; right: -30px; top: -30px; width: 150px; height: 150px; background: rgba(16, 185, 129, 0.15); border-radius: 50%; filter: blur(40px);"></div>

            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-50">
                <div class="icon-shape bg-emerald text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M8 11h8"/><path d="M12 7v8"/></svg>
                </div>
                <div>
                    <h4 class="m-0 fw-bold fs-5 text-white">Protokol Keamanan Akun</h4>
                    <p class="m-0 text-slate-400 small">Standar operasional pengelolaan pengguna BSU</p>
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <div class="fw-bold fs-6 text-white mb-1">Status Langsung Aktif</div>
                    <p class="m-0 text-slate-300 small">Begitu didaftarkan, sistem memberikan status <span class="text-emerald fw-bold">Aktif</span> sehingga pengurus dapat langsung login tanpa persetujuan tambahan.</p>
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div>
                    <div class="fw-bold fs-6 text-white mb-1">Enkripsi Sandi Sementara</div>
                    <p class="m-0 text-slate-300 small">Sandi awal yang di-generate adalah <code class="text-warning">admin123</code>. Demi standar ISO/keamanan siber perbankan sampah, pemilik akun diwajibkan mengubah sandi pada saat sesi pertama.</p>
                </div>
            </div>

            <div class="guide-item">
                <div class="guide-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div>
                    <div class="fw-bold fs-6 text-white mb-1">Jejak Audit Otomatis</div>
                    <p class="m-0 text-slate-300 small">Tanggal pendaftaran (<code class="text-emerald">created_at</code>) dan riwayat aktivitas akun akan dipantau secara langsung oleh sistem pusat.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
