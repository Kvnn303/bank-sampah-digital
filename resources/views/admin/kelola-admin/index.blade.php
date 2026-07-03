@extends('layouts.admin')

@section('title', 'Kelola Admin')
@section('page-title', 'Manajemen Akun & Pengurus Sistem')

@push('styles')
<style>
    /* Modern Card & Hover Effects */
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
        transition: all 0.25s ease;
    }
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
    }

    .icon-shape {
        width: 50px;
        height: 50px;
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

    .text-rose { color: #f43f5e !important; }
    .bg-rose-lt { background-color: rgba(244, 63, 94, 0.12) !important; color: #e11d48 !important; }

    /* Table Styling */
    .table-modern th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        background-color: #f8fafc !important;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem 1.25rem;
    }
    .table-modern td {
        vertical-align: middle;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.92rem;
    }
    .table-modern tbody tr {
        transition: background-color 0.2s;
    }
    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Custom Inputs */
    .filter-input {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        color: #0f172a;
    }
    .filter-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        outline: none;
    }
</style>
@endpush

@section('content')

{{-- STATISTIK EXECUTIVE --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-modern card-hover h-100 p-3">
            <div class="card-body p-2 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-slate-500 small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Total Administrator</div>
                    <div class="h2 mb-0 fw-black text-dark fs-2">{{ number_format($stats['total'] ?? 0) }}</div>
                    <div class="text-muted small mt-1">Seluruh akun terdaftar</div>
                </div>
                <div class="icon-shape bg-blue-lt text-blue-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-modern card-hover h-100 p-3">
            <div class="card-body p-2 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-slate-500 small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Admin Aktif</div>
                    <div class="h2 mb-0 fw-black text-emerald fs-2">{{ number_format($stats['aktif'] ?? 0) }}</div>
                    <div class="text-muted small mt-1">Hak akses penuh</div>
                </div>
                <div class="icon-shape bg-emerald-lt text-emerald">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-modern card-hover h-100 p-3">
            <div class="card-body p-2 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-slate-500 small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Admin Ditangguhkan</div>
                    <div class="h2 mb-0 fw-black text-rose fs-2">{{ number_format($stats['nonaktif'] ?? 0) }}</div>
                    <div class="text-muted small mt-1">Akses dinonaktifkan</div>
                </div>
                <div class="icon-shape bg-rose-lt text-rose">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-modern card-hover h-100 p-3 border-start border-4 border-warning">
            <div class="card-body p-2 d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-slate-500 small fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Sandi Default</div>
                    <div class="h2 mb-0 fw-black text-amber fs-2">{{ number_format($stats['default_pw'] ?? 0) }}</div>
                    <div class="text-muted small mt-1">Belum ganti kata sandi</div>
                </div>
                <div class="icon-shape bg-amber-lt text-amber">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER & ACTION CARD --}}
<div class="card card-modern mb-4 p-4">
    <div class="row g-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-auto order-lg-last">
            <a href="{{ route('admin.kelola-admin.create') }}" class="btn btn-primary rounded-pill fw-bold shadow-sm px-4 py-2 d-inline-flex align-items-center w-100 justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Tambah Admin Baru
            </a>
        </div>
        <div class="col-12 col-lg">
            <form method="GET" class="row g-3" id="filterForm">
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="input-icon">
                        <span class="input-icon-addon text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <input type="text" name="search" id="searchInput" class="form-control filter-input" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3">
                    <select name="status" id="statusFilter" class="form-select filter-input fw-medium">
                        <option value="">⚡ Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-3">
                    <select name="password_changed" id="pwFilter" class="form-select filter-input fw-medium">
                        <option value="">🛡️ Semua Sandi</option>
                        <option value="0" {{ request('password_changed') === '0' ? 'selected' : '' }}>Masih Default</option>
                        <option value="1" {{ request('password_changed') === '1' ? 'selected' : '' }}>Sudah Diganti</option>
                    </select>
                </div>
                <div class="col-12 col-md-1 col-lg-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-light border rounded-pill px-3 py-2 fw-semibold w-100" title="Reset Filter" onclick="document.getElementById('searchInput').value=''; document.getElementById('statusFilter').value=''; document.getElementById('pwFilter').value=''; filterTable();">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- TABEL ADMINISTRATOR --}}
<div class="card card-modern overflow-hidden">
    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
        <h3 class="card-title fw-bold text-dark m-0 fs-5 d-flex align-items-center">
            <span class="bg-blue-lt text-blue-modern p-2 rounded-3 me-3 d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            Daftar Administrator Sistem
        </h3>
        <div>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">
                Menampilkan <span id="totalCount">{{ $admins->total() }}</span> Akun
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern table-vcenter card-table m-0" id="adminTable">
            <thead>
                <tr>
                    <th width="60" class="text-center">No</th>
                    <th>Identitas Administrator</th>
                    <th class="text-center">Status Akses</th>
                    <th class="text-center">Keamanan Sandi</th>
                    <th>Waktu Registrasi</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($admins as $index => $a)
                <tr data-status="{{ $a->is_active ? 'aktif' : 'nonaktif' }}" data-pw="{{ $a->password_changed ? '1' : '0' }}" data-nama="{{ strtolower($a->name) }}" data-email="{{ strtolower($a->email) }}">
                    <td class="text-center text-muted fw-bold">{{ $admins->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($a->foto)
                                <img src="{{ asset('storage/' . $a->foto) }}" alt="Foto {{ $a->name }}" class="avatar avatar-md rounded-circle shadow-sm border" style="object-fit: cover; width: 44px; height: 44px;">
                            @else
                                <div class="avatar avatar-md rounded-circle bg-blue-lt text-blue-modern fw-bold shadow-sm d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($a->name, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <div class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    {{ $a->name }}
                                    @if(auth()->id() === $a->id)
                                        <span class="badge bg-emerald-lt text-emerald px-2 py-1 rounded-pill" style="font-size: 10px;">Anda Saat Ini</span>
                                    @endif
                                </div>
                                <div class="text-slate-500 small d-flex align-items-center gap-1">
                                    <span>📧 {{ $a->email }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($a->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill fw-bold">
                                Aktif
                            </span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill fw-bold">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($a->password_changed)
                            <span class="badge bg-blue bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill fw-bold">
                                Sandi Aman
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1 rounded-pill fw-bold">
                                Default (Rentan)
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold text-dark small">{{ $a->created_at->format('d M Y') }}</div>
                        <div class="text-slate-400 small" style="font-size: 0.78rem;">Pukul {{ $a->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button type="button" class="btn btn-light btn-sm rounded-pill px-3 fw-semibold border dropdown-toggle d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-500"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                                <span>Aksi</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; padding: 8px; min-width: 190px;">
                                <li>
                                    <a href="{{ route('admin.kelola-admin.view', $a->id) }}" class="dropdown-item py-2 d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Lihat Detail Profil
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.kelola-admin.edit', $a->id) }}" class="dropdown-item py-2 d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-amber" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        Edit Data Akun
                                    </a>
                                </li>
                                @if(auth()->id() !== $a->id)
                                <li><hr class="dropdown-divider my-1 border-slate-100"></li>
                                <li>
                                    <form method="POST" action="{{ route('admin.kelola-admin.reset-password', $a->id) }}" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 d-flex align-items-center text-dark" onclick="return confirm('Reset password {{ $a->name }} ke admin123?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                            Reset Sandi ke Default
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('admin.kelola-admin.toggle-status', $a->id) }}" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 d-flex align-items-center {{ $a->is_active ? 'text-rose' : 'text-emerald' }}" onclick="return confirm('{{ $a->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akses admin ini?')">
                                            @if($a->is_active)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                                                Suspend Akses
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><polyline points="20 6 9 17 4 12"/></svg>
                                                Aktifkan Akses
                                            @endif
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('admin.kelola-admin.destroy', $a->id) }}" class="d-inline w-100">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item py-2 d-flex align-items-center text-rose fw-semibold" onclick="return confirm('Yakin hapus permanen admin {{ $a->name }}?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
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
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <div class="bg-slate-100 p-4 rounded-circle mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-400"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                            </div>
                            <h4 class="text-dark fw-bold mb-1">Tidak Ada Data Administrator</h4>
                            <p class="text-muted small mb-3">Belum ada akun admin yang ditemukan dengan filter atau pencarian Anda.</p>
                            <button class="btn btn-outline-primary rounded-pill btn-sm px-4 fw-bold" onclick="document.getElementById('searchInput').value=''; document.getElementById('statusFilter').value=''; document.getElementById('pwFilter').value=''; filterTable();">
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
    <div class="card-footer bg-white border-top d-flex align-items-center justify-content-between p-4 flex-wrap gap-2">
        <p class="m-0 text-muted small fw-medium">
            Menampilkan data <strong>{{ $admins->firstItem() }}</strong> hingga <strong>{{ $admins->lastItem() }}</strong> dari <strong>{{ $admins->total() }}</strong> administrator
        </p>
        <div>
            {{ $admins->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
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

filterTable();
</script>
@endpush
