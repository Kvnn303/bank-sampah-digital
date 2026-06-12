@extends('layouts.admin')

@section('title', 'Kelola Nasabah')
@section('page-title', 'Kelola Nasabah')

@push('styles')
<style>
    /* Styling Modern untuk Kelola Nasabah */
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

    .text-purple { color: #8b5cf6 !important; }
    .bg-purple-lt { background-color: rgba(139, 92, 246, 0.1) !important; color: #8b5cf6 !important; }

    /* Custom Toast Animation */
    .toast-modern {
        animation: slideInUp 0.4s ease forwards, fadeOut 0.4s ease 2.5s forwards;
    }
    @keyframes slideInUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; visibility: hidden; }
    }

    .dropdown-item {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
    }
    .dropdown-item:hover {
        background-color: #f1f5f9;
    }
</style>
@endpush

@section('content')

<!-- Statistik Nasabah -->
<div class="row row-cards mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Nasabah</div>
                    <div class="ms-auto icon-shape bg-blue-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-dark fw-bold">{{ $totalNasabah }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Pending Verifikasi</div>
                    <div class="ms-auto icon-shape bg-amber-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M12 9v2m0 4h.01" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /></svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-amber fw-bold">{{ $totalPending }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nasabah Aktif</div>
                    <div class="ms-auto icon-shape bg-emerald-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M9 12l2 2l4 -4" /><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /></svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-emerald fw-bold">{{ $totalActive }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Tidak Aktif</div>
                    <div class="ms-auto icon-shape bg-rose-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-rose" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M10 10l4 4m0 -4l-4 4" /></svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-rose fw-bold">{{ $totalNonaktif }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Form Filter & Tombol Tambah -->
<div class="card card-modern mb-4">
    <div class="card-body p-4">
        <div class="row align-items-end g-3">
            <div class="col-12 col-xl-9">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label text-muted fw-semibold small">Pencarian Nasabah</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-400" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="10" cy="10" r="7"/><path d="M21 21l-6 -6"/></svg>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Cari Nama, KTP, Telepon..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending"   {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified"  {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="active"    {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif"  {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Sumber Data</label>
                        <select name="sumber" class="form-select">
                            <option value="">Semua Sumber</option>
                            <option value="mandiri" {{ request('sumber') == 'mandiri' ? 'selected' : '' }}>Daftar Sendiri</option>
                            <option value="admin"   {{ request('sumber') == 'admin' ? 'selected' : '' }}>Input Admin</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1 text-white shadow-sm fw-semibold">Cari</button>
                        <a href="{{ route('admin.nasabah.index') }}" class="btn btn-light border shadow-sm" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-12 col-xl-3 ms-auto text-xl-end mt-3 mt-xl-0 border-top pt-3 border-xl-0 pt-xl-0 border-slate-100">
                <a href="{{ route('admin.nasabah.create') }}" class="btn btn-primary shadow-sm fw-bold w-100 w-xl-auto d-inline-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                    Tambah Nasabah
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data Nasabah -->
<div class="card card-modern">
    <div class="card-header d-flex justify-content-between align-items-center border-bottom bg-white p-4">
        <h3 class="card-title fw-bold text-dark m-0 fs-4">Daftar Nasabah</h3>
        <span class="badge bg-slate-100 text-slate-700 border px-3 py-1 rounded-pill fw-semibold">
            Total: {{ $nasabah->total() }} Data
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-modern mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4 text-center" width="60">No</th>
                    <th>Profil Nasabah</th>
                    <th>Kontak & Registrasi</th>
                    <th>Informasi Keuangan</th>
                    <th>Status</th>
                    <th class="pe-4 text-center" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nasabah as $index => $n)
                <tr class="{{ $n->status_akun == 'nonaktif' ? 'opacity-50' : '' }}">
                    <td class="ps-4 text-center text-muted fw-medium">{{ $nasabah->firstItem() + $index }}</td>

                    <!-- Kolom Info Nasabah -->
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                @if($n->foto)
                                    <img src="{{ asset('storage/'.$n->foto) }}" alt="{{ $n->nama_lengkap }}"
                                         class="rounded-circle border shadow-sm object-fit-cover"
                                         style="width: 45px; height: 45px;">
                                @else
                                    <div class="rounded-circle border shadow-sm d-flex align-items-center justify-content-center fw-bold text-white"
                                         style="width: 45px; height: 45px; background: linear-gradient(135deg, #10b981, #059669); font-size: 16px;">
                                        {{ strtoupper(substr($n->nama_lengkap, 0, 1)) }}
                                    </div>
                                @endif
                                @if($n->status_akun == 'active')
                                    <span class="position-absolute rounded-circle bg-emerald border border-2 border-white"
                                          style="width: 12px; height: 12px; bottom: 0; right: 0; transform: translate(25%, 25%);"></span>
                                @endif
                            </div>
                            <div>
                                <div class="fw-bold text-dark fs-5">{{ $n->nama_lengkap }}</div>
                                <div class="text-slate-500 small text-truncate mt-1" style="max-width: 200px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    {{ $n->user->email ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Kolom Kontak & Sumber -->
                    <td>
                        <div class="fw-semibold text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 text-slate-400"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            {{ $n->no_telepon ?? '-' }}
                        </div>
                        <div class="text-slate-500 small mt-1">NIK: {{ $n->no_ktp ?? '-' }}</div>
                        <div class="mt-2">
                            @if($n->sumber_daftar == 'admin')
                                <span class="badge bg-purple-lt badge-modern rounded-pill">Input Admin</span>
                            @else
                                <span class="badge bg-blue-lt badge-modern rounded-pill">Daftar Mandiri</span>
                            @endif
                        </div>
                    </td>

                    <!-- Kolom Saldo & Sampah -->
                    <td>
                        <div class="fw-bold text-emerald fs-5">Rp {{ number_format($n->saldo_aktif) }}</div>
                        <div class="text-slate-500 small mt-1 fw-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                            {{ number_format($n->total_sampah, 1) }} kg Sampah
                        </div>
                        <div class="mt-2 d-none d-md-block">
                            @if($n->user && $n->user->password_changed)
                                <span class="badge bg-emerald-lt badge-modern rounded-pill">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                    Password Aman
                                </span>
                            @else
                                <span class="badge bg-amber-lt badge-modern rounded-pill">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                    Password Default
                                </span>
                            @endif
                        </div>
                    </td>

                    <!-- Kolom Status -->
                    <td>
                        @if($n->status_akun == 'pending')
                            <span class="badge bg-amber-lt badge-modern rounded-pill px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Pending
                            </span>
                        @elseif($n->status_akun == 'verified')
                            <span class="badge bg-blue-lt badge-modern rounded-pill px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Verified
                            </span>
                        @elseif($n->status_akun == 'active')
                            <span class="badge bg-emerald-lt badge-modern rounded-pill px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                Aktif
                            </span>
                        @elseif($n->status_akun == 'nonaktif')
                            <span class="badge bg-rose-lt badge-modern rounded-pill px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                Nonaktif
                            </span>
                        @endif
                        <div class="text-slate-400 fw-medium small mt-2 d-none d-md-block">
                            Bergabung: {{ $n->tanggal_bergabung ? $n->tanggal_bergabung->format('d M Y') : '-' }}
                        </div>
                    </td>

                    <!-- Kolom Aksi (Dropdown Diperbaiki) -->
                    <td class="pe-4 text-center">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm rounded-pill fw-semibold border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px; padding: 8px;">
                                <!-- Icon Mata -->
                                <li>
                                    <a href="{{ route('admin.nasabah.show', $n->id) }}" class="dropdown-item d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-slate-400"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detail Profil
                                    </a>
                                </li>
                                <!-- Icon Pensil -->
                                <li>
                                    <a href="{{ route('admin.nasabah.edit', $n->id) }}" class="dropdown-item d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-slate-400"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        Edit Data
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1 border-slate-100"></li>

                                <!-- Verifikasi (Shield Check) -->
                                @if($n->status_akun == 'pending')
                                <li>
                                    <form method="POST" action="{{ route('admin.nasabah.verifikasi', $n->id) }}">
                                        @csrf @method('PUT')
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-emerald" onclick="return confirm('Verifikasi nasabah ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                            Verifikasi Akun
                                        </button>
                                    </form>
                                </li>
                                @endif

                                <!-- Reset Password (Key/Lock) -->
                                <li>
                                    <form method="POST" action="{{ route('admin.nasabah.reset-password', $n->id) }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-dark" onclick="return confirm('Reset password nasabah ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-amber"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                                            Reset Password
                                        </button>
                                    </form>
                                </li>

                                <!-- Salin Password Default (Copy) -->
                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center text-dark" onclick="copyPw('pw-{{ $n->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-blue-modern"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                        Salin Pass Default
                                    </button>
                                    <input type="hidden" id="pw-{{ $n->id }}" value="{{ $n->no_telepon ?? $n->no_ktp ?? 'banksampah123' }}">
                                </li>

                                <li><hr class="dropdown-divider my-1 border-slate-100"></li>

                                <!-- Status Aktif/Nonaktif -->
                                @if($n->status_akun != 'nonaktif')
                                <li>
                                    <form method="POST" action="{{ route('admin.nasabah.nonaktifkan', $n->id) }}">
                                        @csrf @method('PUT')
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-rose fw-semibold" onclick="return confirm('Nonaktifkan nasabah ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="17" y1="8" x2="23" y2="14"/><line x1="23" y1="8" x2="17" y2="14"/></svg>
                                            Nonaktifkan
                                        </button>
                                    </form>
                                </li>
                                @else
                                <li>
                                    <form method="POST" action="{{ route('admin.nasabah.aktifkan', $n->id) }}">
                                        @csrf @method('PUT')
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-emerald fw-semibold" onclick="return confirm('Aktifkan kembali nasabah ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                                            Aktifkan Kembali
                                        </button>
                                    </form>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                        </div>
                        <h3 class="text-dark fw-bold fs-5">Tidak Ada Data Nasabah</h3>
                        <p class="text-slate-500 small mb-0">Belum ada nasabah yang terdaftar atau pencarian Anda tidak menemukan hasil.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($nasabah->hasPages())
    <div class="card-footer bg-white border-top p-4 d-flex align-items-center justify-content-between">
        <p class="m-0 text-slate-500 fw-medium small d-none d-md-block">
            Menampilkan {{ $nasabah->firstItem() }} sampai {{ $nasabah->lastItem() }} dari {{ $nasabah->total() }} Nasabah
        </p>
        <div class="m-0">
            {{ $nasabah->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function copyPw(id) {
    // Ambil value dari input hidden
    const text = document.getElementById(id).value;

    navigator.clipboard.writeText(text).then(() => {
        // Buat custom toast notification yang lebih modern
        const toastHtml = `
            <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1055;">
                <div class="toast toast-modern bg-white border-0 shadow-lg" role="alert" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
                    <div class="d-flex align-items-center p-3">
                        <div class="me-3 text-emerald" style="color: #10b981;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.95rem;">Berhasil Disalin!</h6>
                            <p class="mb-0 text-slate-500" style="font-size: 0.85rem;">Password default telah disalin ke clipboard.</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', toastHtml);

        // Hapus elemen toast setelah animasi fadeOut selesai (3.5 detik)
        setTimeout(() => {
            const toasts = document.querySelectorAll('.toast-modern');
            toasts.forEach(toast => toast.parentElement.remove());
        }, 3500);
    }).catch(err => {
        alert('Gagal menyalin text: ' + err);
    });
}
</script>
@endpush
