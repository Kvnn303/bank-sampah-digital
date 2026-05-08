@extends('layouts.admin')

@section('title', 'Detail Nasabah')
@section('page-title', 'Detail Nasabah')

@push('styles')
<style>
    /* Styling Modern yang Seragam */
    .stat-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }

    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-modern th {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem;
    }

    .table-modern td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
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

    /* Custom Nav Tabs */
    .nav-modern .nav-link {
        color: #64748b;
        font-weight: 600;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
    }
    .nav-modern .nav-link:hover {
        color: #1e293b;
        border-color: #cbd5e1;
    }
    .nav-modern .nav-link.active {
        color: #10b981;
        border-color: #10b981;
        background: transparent;
    }

    .info-row {
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-row:last-child {
        border-bottom: none;
    }

    .img-zoom {
        transition: transform 0.3s ease;
    }
    .img-zoom-container:hover .img-zoom {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')

<div class="row g-4">

    <!-- Kolom Kiri: Profil & Info Dasar -->
    <div class="col-lg-4">

        <!-- Kartu Profil Utama -->
        <div class="card card-modern">
            <div class="card-body text-center p-4 p-xl-5">
                <!-- Foto Profil -->
                <div class="position-relative d-inline-block mb-3">
                    <div class="avatar avatar-xxl rounded-circle shadow-sm"
                         style="border: 4px solid #ffffff; width: 110px; height: 110px; background-image: url({{ $nasabah->foto ? asset('storage/'.$nasabah->foto) : 'https://ui-avatars.com/api/?name='.urlencode($nasabah->nama_lengkap).'&background=10b981&color=fff&size=128' }})">
                    </div>
                    @if($nasabah->status_akun == 'active')
                        <span class="position-absolute bottom-0 end-0 bg-emerald border border-2 border-white rounded-circle" style="width: 20px; height: 20px; transform: translate(-10px, -10px);" title="Aktif"></span>
                    @endif
                </div>

                <h2 class="mb-1 fs-3 fw-bold text-dark">{{ $nasabah->nama_lengkap }}</h2>
                <p class="text-slate-500 small mb-3 d-flex justify-content-center align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    {{ $nasabah->user->email ?? '-' }}
                </p>

                <!-- Status Badge -->
                <div class="mb-4">
                    @if($nasabah->status_akun == 'pending')
                        <span class="badge bg-amber-lt badge-modern rounded-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Menunggu Verifikasi
                        </span>
                    @elseif($nasabah->status_akun == 'verified')
                        <span class="badge bg-blue-lt badge-modern rounded-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Terverifikasi
                        </span>
                    @elseif($nasabah->status_akun == 'active')
                        <span class="badge bg-emerald-lt badge-modern rounded-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            Akun Aktif
                        </span>
                    @elseif($nasabah->status_akun == 'nonaktif')
                        <span class="badge bg-rose-lt badge-modern rounded-pill">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Tidak Aktif
                        </span>
                    @endif
                </div>

                <!-- Aksi Cepat -->
                <div class="d-grid">
                     <a href="{{ route('admin.nasabah.edit', $nasabah->id) }}" class="btn btn-primary rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        Edit Profil Nasabah
                    </a>
                </div>
            </div>
        </div>

        <!-- Detail Informasi & Dokumen -->
        <div class="card card-modern mt-4">
            <div class="card-header bg-white border-bottom p-4">
                <h3 class="card-title fw-bold text-dark m-0">Informasi Pribadi</h3>
            </div>
            <div class="card-body p-4 pt-2">
                <!-- Info List -->
                <div class="info-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="14" x="3" y="5" rx="2" ry="2"/><path d="M7 15h4M15 15h2M7 11h2M15 11h2"/></svg>
                        <span class="small fw-semibold">No. KTP</span>
                    </div>
                    <div class="fw-bold text-dark">{{ $nasabah->no_ktp ?? '-' }}</div>
                </div>

                <div class="info-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span class="small fw-semibold">Telepon</span>
                    </div>
                    <div class="fw-bold text-dark">{{ $nasabah->no_telepon ?? '-' }}</div>
                </div>

                <div class="info-row">
                    <div class="d-flex align-items-center text-slate-500 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span class="small fw-semibold">Alamat Lengkap</span>
                    </div>
                    <div class="fw-medium text-dark small ps-4 ms-1">{{ $nasabah->alamat ?? '-' }}</div>
                </div>

                <div class="info-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span class="small fw-semibold">Bergabung</span>
                    </div>
                    <div class="fw-bold text-dark">{{ $nasabah->tanggal_bergabung ? $nasabah->tanggal_bergabung->format('d M Y') : '-' }}</div>
                </div>

                <div class="info-row d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span class="small fw-semibold">Password</span>
                    </div>
                    <div>
                        @if($nasabah->user && $nasabah->user->password_changed)
                            <span class="badge bg-emerald-lt badge-modern rounded-pill">Sudah Diubah</span>
                        @else
                            <span class="badge bg-amber-lt badge-modern rounded-pill">Default</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dokumen KTP -->
            <div class="card-header bg-slate-50 border-top border-bottom p-3 px-4">
                <h4 class="card-title fw-bold text-slate-600 m-0 fs-5 text-uppercase" style="letter-spacing: 0.5px;">Dokumen KTP</h4>
            </div>
            <div class="card-body p-4 text-center">
                @if($nasabah->foto_ktp)
                    <div class="img-zoom-container overflow-hidden rounded-3 shadow-sm border" style="max-height: 200px; display: inline-block;">
                        <a href="{{ asset('storage/'.$nasabah->foto_ktp) }}" target="_blank" class="d-block" title="Klik untuk memperbesar">
                            <img src="{{ asset('storage/'.$nasabah->foto_ktp) }}" class="img-fluid img-zoom w-100 h-100 object-fit-cover" alt="Foto KTP">
                        </a>
                    </div>
                    <div class="text-slate-400 small mt-2 d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><line x1="11" x2="11" y1="8" y2="14"/><line x1="8" x2="14" y1="11" y2="11"/></svg>
                        Klik gambar untuk memperbesar
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-3 border border-dashed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 text-slate-300" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        <div class="text-slate-500 fw-medium small">Belum ada foto KTP</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Catatan Admin -->
        @if($nasabah->catatan_admin)
        <div class="card card-modern mt-4 border border-amber" style="background-color: #fffbeb;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/><line x1="9" x2="9.01" y1="9" y2="9"/><line x1="9" x2="15" y1="13" y2="13"/><line x1="9" x2="15" y1="17" y2="17"/></svg>
                    <h4 class="card-title fw-bold text-dark m-0 fs-5">Catatan Internal</h4>
                </div>
                <p class="mb-0 text-slate-600 fst-italic">"{{ $nasabah->catatan_admin }}"</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Kolom Kanan: Statistik & Riwayat -->
    <div class="col-lg-8">

        <!-- Ringkasan Keuangan -->
        <div class="row row-cards mb-4">
            <div class="col-sm-4">
                <div class="card stat-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Tabungan</div>
                            <div class="ms-auto icon-shape bg-emerald-lt" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 17l-2 2l2 2m-2 -2h9a2 2 0 0 0 1.75 -2.75l-.55 -1" /><path d="M8.536 11l-.732 -2.732l-2.732 .732" /><path d="M7.804 8.268l-4.5 7.794a2 2 0 0 0 1.504 2.97l1.141 .011" /><path d="M15.464 11l.732 -2.732l2.732 .732" /><path d="M16.196 8.268l4.5 7.794a2 2 0 0 1 -1.504 2.97l-1.141 .011" /></svg>
                            </div>
                        </div>
                        <div class="h2 text-dark fw-bold mb-1 fs-2">
                            Rp {{ number_format($nasabah->tabungan->sum('nilai_rupiah')) }}
                        </div>
                        <div class="text-emerald fw-semibold small d-flex align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            {{ number_format($nasabah->tabungan->sum('berat_kg'), 1) }} kg Sampah
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card stat-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Penarikan</div>
                            <div class="ms-auto icon-shape bg-rose-lt" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-rose" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/></svg>
                            </div>
                        </div>
                        <div class="h2 text-dark fw-bold mb-1 fs-2">
                            Rp {{ number_format($nasabah->penarikan->whereIn('status', ['selesai','diproses'])->sum('nominal')) }}
                        </div>
                        <div class="text-rose fw-semibold small d-flex align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                            {{ $nasabah->penarikan->where('status', 'selesai')->count() }}x Penarikan Selesai
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card stat-card h-100 border-0" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; opacity: 0.9;">Saldo Aktif</div>
                            <div class="ms-auto icon-shape bg-white text-emerald" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M8.5 11.5 11 14l4-4"/></svg>
                            </div>
                        </div>
                        <div class="h2 fw-bold mb-1 fs-2 text-white">
                            Rp {{ number_format($nasabah->saldo) }}
                        </div>
                        <div class="small fw-medium mt-2" style="opacity: 0.9;">
                            Saldo yang dapat ditarik saat ini
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigasi & Tabel -->
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom-0 p-0">
                <ul class="nav nav-tabs nav-modern w-100 d-flex" role="tablist">
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <a href="#tab-stats" class="nav-link active d-flex justify-content-center align-items-center" data-bs-toggle="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 d-none d-sm-block" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"/><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"/><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"/></svg>
                            Statistik Sampah
                        </a>
                    </li>
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <a href="#tab-savings" class="nav-link d-flex justify-content-center align-items-center" data-bs-toggle="tab">
                             <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 d-none d-sm-block" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 17l-2 2l2 2m-2 -2h9a2 2 0 0 0 1.75 -2.75l-.55 -1"/><path d="M8.536 11l-.732 -2.732l-2.732 .732"/></svg>
                             Riwayat Setoran
                        </a>
                    </li>
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <a href="#tab-withdrawals" class="nav-link d-flex justify-content-center align-items-center" data-bs-toggle="tab">
                             <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 d-none d-sm-block" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/></svg>
                             Riwayat Penarikan
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                <div class="tab-content">

                    <!-- Tab 1: Statistik Sampah -->
                    <div id="tab-stats" class="tab-pane active show">
                        <div class="table-responsive">
                            <table class="table table-hover table-modern mb-0 align-middle table-nowrap">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="ps-4">Jenis Sampah</th>
                                        <th class="d-none d-md-table-cell">Kategori</th>
                                        <th class="text-end">Total Berat</th>
                                        <th class="text-end">Total Nilai</th>
                                        <th class="pe-4 text-center d-none d-sm-table-cell">Terakhir Setor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $perJenis = $nasabah->tabungan->groupBy('jenis_sampah_id');
                                    @endphp
                                    @forelse($perJenis as $jenisId => $items)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">{{ $items->first()->jenisSampah->nama ?? '-' }}</td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-emerald-lt badge-modern rounded-pill">
                                                {{ $items->first()->jenisSampah->kategori ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-semibold text-dark">{{ number_format($items->sum('berat_kg'), 1) }} kg</td>
                                        <td class="text-end text-emerald fw-bold">Rp {{ number_format($items->sum('nilai_rupiah')) }}</td>
                                        <td class="pe-4 text-center d-none d-sm-table-cell text-muted small">{{ $items->max('tanggal_setor') ? \Carbon\Carbon::parse($items->max('tanggal_setor'))->format('d M Y') : '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-slate-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                            </div>
                                            <h4 class="text-dark fw-bold fs-5">Belum ada data</h4>
                                            <p class="text-slate-500 small mb-0">Nasabah ini belum pernah melakukan setoran sampah.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($perJenis->count() > 0)
                                <tfoot class="bg-slate-50 border-top">
                                    <tr>
                                        <td colspan="2" class="ps-4 fw-bold text-dark">Total Keseluruhan</td>
                                        <td class="text-end fw-bold text-dark">{{ number_format($nasabah->tabungan->sum('berat_kg'), 1) }} kg</td>
                                        <td class="text-end fw-bold text-emerald fs-6">Rp {{ number_format($nasabah->tabungan->sum('nilai_rupiah')) }}</td>
                                        <td class="pe-4 d-none d-sm-table-cell"></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Tab 2: Riwayat Tabungan -->
                    <div id="tab-savings" class="tab-pane">
                        <div class="table-responsive">
                            <table class="table table-hover table-modern mb-0 align-middle table-nowrap">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="ps-4">Tanggal Setor</th>
                                        <th>Jenis Sampah</th>
                                        <th class="text-end">Berat</th>
                                        <th class="text-end">Nilai (Rp)</th>
                                        <th class="pe-4 d-none d-md-table-cell">Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nasabah->tabungan->sortByDesc('tanggal_setor') as $t)
                                    <tr>
                                        <td class="ps-4 fw-medium text-dark">{{ $t->tanggal_setor->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-emerald-lt badge-modern rounded-pill">{{ $t->jenisSampah->nama ?? '-' }}</span>
                                        </td>
                                        <td class="text-end fw-semibold text-dark">{{ $t->berat_kg }} kg</td>
                                        <td class="text-end text-emerald fw-bold">Rp {{ number_format($t->nilai_rupiah) }}</td>
                                        <td class="pe-4 d-none d-md-table-cell text-slate-500 small">{{ $t->admin->name ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-slate-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                            </div>
                                            <h4 class="text-dark fw-bold fs-5">Belum ada riwayat</h4>
                                            <p class="text-slate-500 small mb-0">Belum ada transaksi setoran tabungan untuk nasabah ini.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Riwayat Penarikan -->
                    <div id="tab-withdrawals" class="tab-pane">
                        <div class="table-responsive">
                            <table class="table table-hover table-modern mb-0 align-middle table-nowrap">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="ps-4">Tgl Ajuan</th>
                                        <th class="text-end">Nominal Tarik</th>
                                        <th class="text-center">Status</th>
                                        <th class="d-none d-md-table-cell">Catatan</th>
                                        <th class="pe-4 d-none d-sm-table-cell">Diproses Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nasabah->penarikan->sortByDesc('created_at') as $p)
                                    <tr>
                                        <td class="ps-4 fw-medium text-dark">{{ $p->created_at->format('d M Y') }}</td>
                                        <td class="text-end fw-bold text-rose fs-6">Rp {{ number_format($p->nominal) }}</td>
                                        <td class="text-center">
                                            @if($p->status == 'pending')
                                                <span class="badge bg-amber-lt badge-modern rounded-pill">Pending</span>
                                            @elseif($p->status == 'diproses')
                                                <span class="badge bg-blue-lt badge-modern rounded-pill">Diproses</span>
                                            @elseif($p->status == 'selesai')
                                                <span class="badge bg-emerald-lt badge-modern rounded-pill">Selesai</span>
                                            @elseif($p->status == 'ditolak')
                                                <span class="badge bg-rose-lt badge-modern rounded-pill">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="d-none d-md-table-cell text-slate-500 small">
                                            {{ Str::limit($p->catatan_nasabah ?? '-', 20) }}
                                            @if($p->alasan_penolakan)
                                                <br><span class="text-rose fw-medium">Tolak: {{ Str::limit($p->alasan_penolakan, 15) }}</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 d-none d-sm-table-cell text-slate-500 small">{{ $p->diprosesoleh->name ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-slate-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                            </div>
                                            <h4 class="text-dark fw-bold fs-5">Belum ada penarikan</h4>
                                            <p class="text-slate-500 small mb-0">Nasabah belum pernah melakukan permintaan pencairan dana.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($nasabah->penarikan->count() > 0)
                                <tfoot class="bg-slate-50 border-top">
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">Total Penarikan (Selesai)</td>
                                        <td class="text-end fw-bold text-rose fs-6">
                                            Rp {{ number_format($nasabah->penarikan->where('status', 'selesai')->sum('nominal')) }}
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-4 text-end">
            <a href="{{ route('admin.nasabah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar Nasabah
            </a>
        </div>

    </div>
</div>

@endsection
