@extends('layouts.admin')

@section('title', 'Detail Admin - ' . $admin->name)
@section('page-title', 'Profil & Metadata Administrator')

@push('styles')
<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }
    .profile-cover {
        height: 150px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        position: relative;
    }
    .avatar-profile-wrapper {
        margin-top: -65px;
        position: relative;
        z-index: 2;
        text-align: center;
    }
    .avatar-profile {
        width: 124px;
        height: 124px;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        background-color: #ffffff;
    }
    .info-list .info-item {
        display: flex;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
        justify-content: space-between;
    }
    .info-list .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #64748b;
        font-size: 0.88rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-value {
        font-weight: 700;
        color: #0f172a;
        text-align: right;
    }
    .danger-zone {
        border: 1.5px solid #fecaca;
        background: #fef2f2;
        border-radius: 20px;
        padding: 1.75rem;
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <a href="{{ route('admin.kelola-admin.index') }}" class="text-slate-500 small fw-bold text-decoration-none d-inline-flex align-items-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Admin
    </a>
    <span class="badge bg-slate-100 text-slate-700 border px-3 py-2 rounded-pill font-monospace">
        ID SYSTEM: #{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}
    </span>
</div>

{{-- PROFILE HEADER CARD --}}
<div class="card card-modern mb-4">
    <div class="profile-cover"></div>
    <div class="card-body pt-0 text-center pb-5 px-4">
        <div class="avatar-profile-wrapper">
            @if($admin->foto)
                <img src="{{ asset('storage/' . $admin->foto) }}" class="avatar avatar-profile rounded-circle" alt="{{ $admin->name }}" style="object-fit: cover;">
            @else
                <div class="avatar avatar-profile rounded-circle bg-emerald text-white fw-bold d-inline-flex align-items-center justify-content-center" style="font-size: 2.8rem;">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
            @endif
        </div>

        <h2 class="mt-3 mb-1 fw-bold fs-3 text-dark">{{ $admin->name }}</h2>
        <div class="text-slate-500 fw-medium mb-4">{{ $admin->email }}</div>

        <div class="d-flex justify-content-center gap-2 flex-wrap mb-4">
            @if($admin->is_active)
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Akses Sistem Aktif
                </span>
            @else
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    Akses Ditangguhkan
                </span>
            @endif

            @if($admin->password_changed)
                <span class="badge bg-blue bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Sandi Terproteksi
                </span>
            @else
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                    Sandi Default (admin123)
                </span>
            @endif

            <span class="badge bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                👑 Role: Administrator
            </span>
        </div>

        @if(auth()->id() !== $admin->id)
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('admin.kelola-admin.edit', $admin->id) }}" class="btn btn-dark rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                    Edit Informasi Akun
                </a>
                <form method="POST" action="{{ route('admin.kelola-admin.reset-password', $admin->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light border rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center" onclick="return confirm('Reset password {{ $admin->name }} ke default (admin123)?')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        Reset Password ke Default
                    </button>
                </form>
            </div>
        @else
            <div class="alert alert-info bg-primary bg-opacity-10 border border-primary border-opacity-25 text-primary rounded-4 d-inline-flex align-items-center mx-auto mb-0 px-4 py-3 fw-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Ini adalah profil akun yang sedang Anda gunakan saat ini.
            </div>
        @endif
    </div>
</div>

<div class="row g-4">
    {{-- INFORMASI AKUN --}}
    <div class="col-md-6">
        <div class="card card-modern h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h3 class="card-title fw-bold text-dark m-0 fs-5 d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </span>
                    Spesifikasi Data Akun
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg>
                            ID Administrator
                        </div>
                        <div class="info-value font-monospace">#{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
                            Nama Lengkap
                        </div>
                        <div class="info-value">{{ $admin->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            Alamat Email
                        </div>
                        <div class="info-value">{{ $admin->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            Status Verifikasi Email
                        </div>
                        <div class="info-value">
                            @if($admin->email_verified_at)
                                <span class="text-success fw-bold d-inline-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Terverifikasi ({{ $admin->email_verified_at->format('d/m/Y') }})
                                </span>
                            @else
                                <span class="text-warning fw-bold">Otomatis Sistem BSU</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JEJAK TIMELINE AUDIT --}}
    <div class="col-md-6">
        <div class="card card-modern h-100">
            <div class="card-header bg-white border-bottom p-4">
                <h3 class="card-title fw-bold text-dark m-0 fs-5 d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center bg-emerald bg-opacity-10 text-emerald p-2 rounded-3 me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    Jejak Audit & Waktu Sistem
                </h3>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle flex-shrink-0" style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6">Registrasi Akun Dibuat</div>
                            <div class="text-slate-500 small mt-1">{{ $admin->created_at->format('d F Y • H:i:s') }} WIB</div>
                            <div class="text-success small fw-semibold">({{ $admin->created_at->diffForHumans() }})</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle flex-shrink-0" style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6">Terakhir Kali Diperbarui</div>
                            <div class="text-slate-500 small mt-1">{{ $admin->updated_at->format('d F Y • H:i:s') }} WIB</div>
                            <div class="text-success small fw-semibold">({{ $admin->updated_at->diffForHumans() }})</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ZONA BAHAYA (AKSI CEPAT) --}}
    @if(auth()->id() !== $admin->id)
    <div class="col-12 mt-4">
        <div class="danger-zone">
            <h4 class="text-danger fw-bold mb-2 d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Zona Bahaya Pengelolaan (Danger Zone)
            </h4>
            <p class="text-slate-600 small mb-4">Tindakan di bawah ini berdampak langsung pada hak akses administrator di dalam sistem Bank Sampah Subang.</p>

            <div class="d-flex flex-wrap gap-3">
                {{-- Toggle Status --}}
                <form method="POST" action="{{ route('admin.kelola-admin.toggle-status', $admin->id) }}">
                    @csrf
                    <button type="submit" class="btn {{ $admin->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center" onclick="return confirm('{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akses untuk {{ $admin->name }}?')">
                        @if($admin->is_active)
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                            Suspend Akun (Nonaktifkan)
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12l5 5l10 -10"/></svg>
                            Pulihkan Akun (Aktifkan Kembali)
                        @endif
                    </button>
                </form>

                {{-- Hapus --}}
                <form method="POST" action="{{ route('admin.kelola-admin.destroy', $admin->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center shadow-sm" onclick="return confirm('YAKIN hapus permanen admin {{ $admin->name }}?\n\nAkun ini tidak dapat dipulihkan kembali.')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        Hapus Permanen Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
