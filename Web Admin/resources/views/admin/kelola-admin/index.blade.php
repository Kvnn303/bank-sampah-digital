@extends('layouts.admin')

@section('title', 'Kelola Admin')
@section('page-title', 'Kelola Akun Admin')

@push('styles')
<style>
    /* Modern Card & Hover Effects */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        border-color: var(--bs-primary);
    }

    /* Icon Wrappers for Stats */
    .stat-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Table Styling */
    .table-modern th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        background-color: #f8fafc !important;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
    }
    .table-modern td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tbody tr {
        transition: background-color 0.2s;
    }
    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Badges */
    .badge-modern {
        padding: 0.4rem 0.8rem;
        border-radius: 50rem;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Custom Inputs */
    .filter-input {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .filter-input:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.1);
    }
</style>
@endpush

@section('content')

{{-- STATISTIK --}}
<div class="row row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-modern card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader fw-bold text-uppercase">Total Admin</div>
                    <div class="ms-auto stat-icon-box bg-blue-lt text-blue">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M12 10a3 3 0 1 0 0 -6a3 3 0 0 0 0 6"/><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"/></svg>
                    </div>
                </div>
                <div class="h1 mb-0 fw-black text-dark fs-1">{{ number_format($stats['total'] ?? 0) }}</div>
                <div class="text-muted small mt-2">Seluruh akun terdaftar</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-modern card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader fw-bold text-uppercase">Aktif</div>
                    <div class="ms-auto stat-icon-box bg-green-lt text-green">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                    </div>
                </div>
                <div class="h1 mb-0 fw-black text-green fs-1">{{ number_format($stats['aktif'] ?? 0) }}</div>
                <div class="text-muted small mt-2">Dapat mengakses sistem</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-modern card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader fw-bold text-uppercase">Nonaktif</div>
                    <div class="ms-auto stat-icon-box bg-red-lt text-red">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                    </div>
                </div>
                <div class="h1 mb-0 fw-black text-red fs-1">{{ number_format($stats['nonaktif'] ?? 0) }}</div>
                <div class="text-muted small mt-2">Akses ditangguhkan</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-modern card-hover h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="subheader fw-bold text-uppercase">Pass Default</div>
                    <div class="ms-auto stat-icon-box bg-warning-lt text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4h.01"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/></svg>
                    </div>
                </div>
                <div class="h1 mb-0 fw-black text-warning fs-1">{{ number_format($stats['default_pw'] ?? 0) }}</div>
                <div class="text-muted small mt-2">Belum mengganti sandi</div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER & SEARCH --}}
<div class="card card-modern mb-4">
    <div class="card-body p-3">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-lg-auto order-lg-last ms-auto">
                <a href="{{ route('admin.kelola-admin.create') }}" class="btn btn-primary rounded-pill fw-bold shadow-sm px-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                    Tambah Admin
                </a>
            </div>
            <div class="col-12 col-lg">
                <form method="GET" class="row g-3" id="filterForm">
                    <div class="col-12 col-md-5 col-lg-4">
                        <div class="input-icon">
                            <span class="input-icon-addon text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7"/><path d="M21 21l-6 -6"/></svg>
                            </span>
                            <input type="text" name="search" id="searchInput" class="form-control filter-input" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-3 col-lg-3">
                        <select name="status" id="statusFilter" class="form-select filter-input text-secondary">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-3">
                        <select name="password_changed" id="pwFilter" class="form-select filter-input text-secondary">
                            <option value="">Semua Password</option>
                            <option value="0" {{ request('password_changed') === '0' ? 'selected' : '' }}>Masih Default</option>
                            <option value="1" {{ request('password_changed') === '1' ? 'selected' : '' }}>Sudah Diganti</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-1 col-lg-2 d-flex justify-content-end d-md-none d-lg-flex">
                        <a href="{{ route('admin.kelola-admin.index') }}" class="btn btn-outline-secondary rounded-pill w-100" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mx-0" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            <span class="d-lg-none ms-2">Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div class="card card-modern overflow-hidden">
    <div class="card-header bg-white border-bottom p-4">
        <h3 class="card-title fw-bold text-dark m-0 d-flex align-items-center">
            <span class="bg-primary-lt text-primary p-2 rounded-3 me-3 d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M12 10a3 3 0 1 0 0 -6a3 3 0 0 0 0 6"/><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"/></svg>
            </span>
            Daftar Administrator
        </h3>
        <div class="card-options">
            <span class="badge bg-slate-100 text-slate-600 px-3 py-2 rounded-pill fw-medium border">
                Menampilkan <span id="totalCount">{{ $admins->total() }}</span> Data
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern table-vcenter card-table m-0" id="adminTable">
            <thead>
                <tr>
                    <th width="50" class="text-center">No</th>
                    <th>Informasi Admin</th>
                    <th class="text-center">Status Akses</th>
                    <th class="text-center">Keamanan Sandi</th>
                    <th>Terdaftar Pada</th>
                    <th width="100" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($admins as $index => $a)
                <tr data-status="{{ $a->is_active ? 'aktif' : 'nonaktif' }}" data-pw="{{ $a->password_changed ? '1' : '0' }}" data-nama="{{ strtolower($a->name) }}" data-email="{{ strtolower($a->email) }}">
                    <td class="text-center text-muted fw-medium">{{ $admins->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($a->foto)
                                <img src="{{ asset('storage/' . $a->foto) }}" alt="Foto {{ $a->name }}" class="avatar avatar-md rounded-circle shadow-sm" style="object-fit: cover;">
                            @else
                                <div class="avatar avatar-md rounded-circle bg-blue-lt text-blue fw-bold shadow-sm d-flex align-items-center justify-content-center">
                                    {{ strtoupper(substr($a->name, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <div class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    {{ $a->name }}
                                    @if(auth()->id() === $a->id)
                                        <span class="badge bg-indigo-lt text-indigo px-2 py-1 rounded-pill" style="font-size:10px;">It's You</span>
                                    @endif
                                </div>
                                <div class="text-muted small d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    {{ $a->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($a->is_active)
                            <span class="badge badge-modern bg-green-lt text-green border border-green-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                Aktif
                            </span>
                        @else
                            <span class="badge badge-modern bg-red-lt text-red border border-red-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($a->password_changed)
                            <span class="badge badge-modern bg-blue-lt text-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                Diamankan
                            </span>
                        @else
                            <span class="badge badge-modern bg-warning-lt text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                                Rentan (Default)
                            </span>
                        @endif
                    </td>
                    <td class="text-muted fw-medium">
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-400"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            {{ $a->created_at->format('d M Y') }}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button type="button" class="btn btn-light btn-sm rounded-pill px-3 fw-medium dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Opsi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px;">
                                <li>
                                    <a href="{{ route('admin.kelola-admin.view', $a->id) }}" class="dropdown-item py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0 -20"/></svg>
                                        Lihat Detail
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.kelola-admin.edit', $a->id) }}" class="dropdown-item py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.196 2.196 0 0 0 -1.606 -3.175a2.196 2.196 0 0 0 -2.606 1.297l-7.173 14.293l-2.4.4l1.6 -2.4l7.173 -14.293z"/></svg>
                                        Edit Data
                                    </a>
                                </li>
                                @if(auth()->id() !== $a->id)
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('admin.kelola-admin.reset-password', $a->id) }}" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2" onclick="return confirm('Reset password {{ $a->name }} ke admin123?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-secondary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1 -15.357 -2m15.357 2H15"/></svg>
                                            Reset Sandi
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('admin.kelola-admin.toggle-status', $a->id) }}" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 {{ $a->is_active ? 'text-danger' : 'text-success' }}" onclick="return confirm('{{ $a->is_active ? 'Nonaktifkan' : 'Aktifkan' }} jenis admin ini?')">
                                            @if($a->is_active)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                                                Nonaktifkan Akses
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                                                Aktifkan Akses
                                            @endif
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('admin.kelola-admin.destroy', $a->id) }}" class="d-inline w-100">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item py-2 text-danger" onclick="return confirm('Yakin hapus permanen admin {{ $a->name }}?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                            Hapus Permanen
                                        </button>
                                    </form>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state d-flex flex-column align-items-center justify-content-center">
                            <div class="bg-slate-100 p-4 rounded-circle mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-400" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><line x1="3" y1="3" x2="21" y2="21"/></svg>
                            </div>
                            <h3 class="text-dark fw-bold mb-1">Tidak Ada Data Admin</h3>
                            <p class="text-muted small mb-3">Sistem tidak menemukan admin yang sesuai dengan pencarian atau filter Anda.</p>
                            <button class="btn btn-outline-primary rounded-pill btn-sm px-3" onclick="document.getElementById('searchInput').value=''; document.getElementById('statusFilter').value=''; document.getElementById('pwFilter').value=''; filterTable();">
                                Reset Pencarian
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($admins->hasPages())
    <div class="card-footer bg-white border-top d-flex align-items-center justify-content-between p-3">
        <p class="m-0 text-muted small d-none d-md-block fw-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Menampilkan data {{ $admins->firstItem() }} sampai {{ $admins->lastItem() }} dari {{ $admins->total() }} admin
        </p>
        <div class="ms-auto pagination-modern">
            {{ $admins->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    }
});

const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const pwFilter = document.getElementById('pwFilter');
const tableBody = document.getElementById('tableBody');
const totalCountSpan = document.getElementById('totalCount');

function filterTable() {
    if (!tableBody) return;
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const statusVal = statusFilter ? statusFilter.value : '';
    const pwVal = pwFilter ? pwFilter.value : '';

    let visibleCount = 0;
    const rows = tableBody.querySelectorAll('tr');

    rows.forEach(row => {
        if (row.id === 'emptyRow') return;
        const nama = row.getAttribute('data-nama') || '';
        const email = row.getAttribute('data-email') || '';
        const status = row.getAttribute('data-status') || '';
        const pw = row.getAttribute('data-pw') || '';

        let show = true;
        if (searchTerm && !nama.includes(searchTerm) && !email.includes(searchTerm)) show = false;
        if (show && statusVal === 'aktif' && status !== 'aktif') show = false;
        if (show && statusVal === 'nonaktif' && status !== 'nonaktif') show = false;
        if (show && pwVal && pw !== pwVal) show = false;

        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });

    if (totalCountSpan) totalCountSpan.textContent = visibleCount;
    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.style.display = visibleCount === 0 ? '' : 'none';
}

if (searchInput) searchInput.addEventListener('keyup', filterTable);
if (statusFilter) statusFilter.addEventListener('change', filterTable);
if (pwFilter) pwFilter.addEventListener('change', filterTable);

// Initial call to ensure empty state displays correctly
filterTable();
</script>
@endpush
