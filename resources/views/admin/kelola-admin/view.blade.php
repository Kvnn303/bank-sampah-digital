@extends('layouts.admin')

@section('title', 'Detail Admin - ' . $admin->name)
@section('page-title', 'Detail Akun Admin')

@push('styles')
<style>
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .profile-cover {
        height: 140px;
        border-radius: 16px 16px 0 0;
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        position: relative;
    }

    .avatar-profile-wrapper {
        margin-top: -60px;
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .avatar-profile {
        width: 120px;
        height: 120px;
        border: 4px solid white;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        background-color: white;
    }

    .badge-soft {
        padding: 8px 16px;
        border-radius: 50rem;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid transparent;
    }

    .info-list .info-item {
        display: flex;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
    }
    .info-list .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        width: 140px;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-value {
        font-weight: 700;
        color: #0f172a;
    }

    .danger-zone {
        border: 1px solid #fecaca;
        background: #fef2f2;
        border-radius: 16px;
    }
</style>
@endpush

@section('content')

<div class="container-narrow">

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.kelola-admin.index') }}" class="text-slate-500 small fw-semibold text-decoration-none d-inline-flex align-items-center gap-1 transition-all hover-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 19l-7 -7m0 0l7 -7m-7 7h18"/></svg>
            Kembali ke Daftar Admin
        </a>
    </div>

    {{-- PROFILE HEADER --}}
    <div class="card card-modern mb-4">
        <div class="profile-cover"></div>
        <div class="card-body pt-0 text-center pb-5">
            <div class="avatar-profile-wrapper">
                @if($admin->foto)
                    <img src="{{ asset('storage/' . $admin->foto) }}" class="avatar avatar-profile rounded-circle" alt="{{ $admin->name }}" style="object-fit: cover;">
                @else
                    <div class="avatar avatar-profile rounded-circle bg-emerald-lt text-emerald fw-bold d-inline-flex align-items-center justify-content-center" style="font-size: 2.5rem;">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <h2 class="mt-3 mb-1 fw-bold fs-2 text-dark">{{ $admin->name }}</h2>
            <div class="text-muted mb-4">{{ $admin->email }}</div>

            <div class="d-flex justify-content-center gap-2 flex-wrap mb-4">
                @if($admin->is_active)
                    <span class="badge-soft bg-green-lt text-green border-green-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Akun Aktif
                    </span>
                @else
                    <span class="badge-soft bg-red-lt text-red border-red-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        Akun Ditangguhkan
                    </span>
                @endif

                @if($admin->password_changed)
                    <span class="badge-soft bg-blue-lt text-blue border-blue-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Sandi Aman
                    </span>
                @else
                    <span class="badge-soft bg-warning-lt text-warning border-warning-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                        Sandi Default
                    </span>
                @endif

                <span class="badge-soft bg-purple-lt text-purple border-purple-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/><path d="M20.59 22c-.63-4.88-3.76-8.5-8.59-8.5s-7.96 3.62-8.59 8.5"/></svg>
                    Role: {{ ucfirst($admin->role) }}
                </span>
            </div>

            @if(auth()->id() !== $admin->id)
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('admin.kelola-admin.edit', $admin->id) }}" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        Edit Profil
                    </a>
                    <form method="POST" action="{{ route('admin.kelola-admin.reset-password', $admin->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" onclick="return confirm('Reset password {{ $admin->name }} ke default (admin123)?')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            Reset Sandi
                        </button>
                    </form>
                </div>
            @else
                <div class="alert alert-info bg-blue-lt border-0 rounded-4 d-inline-flex align-items-center mx-auto mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Ini adalah akun Anda. Silakan ke menu Profil Saya untuk mengubah data.
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- INFORMASI AKUN --}}
        <div class="col-md-6">
            <div class="card card-modern h-100">
                <div class="card-header bg-white border-bottom p-4">
                    <h3 class="card-title fw-bold text-dark m-0 d-flex align-items-center">
                        <span class="bg-primary-lt text-primary p-2 rounded-3 me-3 d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </span>
                        Detail Informasi
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg>
                                ID Admin
                            </div>
                            <div class="info-value font-monospace">#{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
                                Nama Lengkap
                            </div>
                            <div class="info-value">{{ $admin->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                Email
                            </div>
                            <div class="info-value">{{ $admin->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                Email Verified
                            </div>
                            <div class="info-value">
                                @if($admin->email_verified_at)
                                    <span class="text-success d-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        {{ $admin->email_verified_at->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-danger">Belum Verifikasi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TIMELINE --}}
        <div class="col-md-6">
            <div class="card card-modern h-100">
                <div class="card-header bg-white border-bottom p-4">
                    <h3 class="card-title fw-bold text-dark m-0 d-flex align-items-center">
                        <span class="bg-indigo-lt text-indigo p-2 rounded-3 me-3 d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </span>
                        Aktivitas Terakhir
                    </h3>
                </div>
                <div class="card-body p-4">
                    <ul class="list-timeline mb-0">
                        <li class="list-timeline-item">
                            <div class="list-timeline-icon bg-primary-lt text-primary border-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                            </div>
                            <div class="list-timeline-content">
                                <div class="fw-bold text-dark">Akun Dibuat</div>
                                <div class="text-muted small mt-1">{{ $admin->created_at->format('d F Y, H:i') }} WIB ({{ $admin->created_at->diffForHumans() }})</div>
                            </div>
                        </li>
                        @if($admin->email_verified_at)
                        <li class="list-timeline-item">
                            <div class="list-timeline-icon bg-success-lt text-success border-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                            </div>
                            <div class="list-timeline-content">
                                <div class="fw-bold text-dark">Email Terverifikasi</div>
                                <div class="text-muted small mt-1">{{ $admin->email_verified_at->format('d F Y, H:i') }} WIB</div>
                            </div>
                        </li>
                        @endif
                        <li class="list-timeline-item">
                            <div class="list-timeline-icon bg-warning-lt text-warning border-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.196 2.196 0 0 0 -1.606 -3.175a2.196 2.196 0 0 0 -2.606 1.297l-7.173 14.293l-2.4.4l1.6 -2.4l7.173 -14.293z"/></svg>
                            </div>
                            <div class="list-timeline-content">
                                <div class="fw-bold text-dark">Terakhir Diperbarui</div>
                                <div class="text-muted small mt-1">{{ $admin->updated_at->format('d F Y, H:i') }} WIB ({{ $admin->updated_at->diffForHumans() }})</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ZONA BAHAYA (AKSI CEPAT) --}}
        @if(auth()->id() !== $admin->id)
        <div class="col-12 mt-4">
            <div class="danger-zone p-4">
                <h4 class="text-danger fw-bold mb-3 d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Zona Bahaya (Danger Zone)
                </h4>
                <p class="text-muted small mb-4">Aksi di bawah ini bersifat krusial dan beberapa di antaranya <strong>permanen</strong>. Pastikan Anda benar-benar memahami tindakan yang akan dilakukan.</p>

                <div class="d-flex flex-wrap gap-3">
                    {{-- Toggle Status --}}
                    <form method="POST" action="{{ route('admin.kelola-admin.toggle-status', $admin->id) }}" class="flex-grow-1 flex-md-grow-0">
                        @csrf
                        <button type="submit" class="btn {{ $admin->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} w-100" onclick="return confirm('{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $admin->name }}?')">
                            @if($admin->is_active)
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                                Suspend Akun (Nonaktif)
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12l5 5l10 -10"/></svg>
                                Pulihkan Akun (Aktif)
                            @endif
                        </button>
                    </form>

                    {{-- Hapus --}}
                    <form method="POST" action="{{ route('admin.kelola-admin.destroy', $admin->id) }}" class="flex-grow-1 flex-md-grow-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('YAKIN hapus permanen admin {{ $admin->name }}?\n\nSemua data terkait akun ini akan dihapus dari sistem.')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
