@extends('layouts.admin')

@section('title', 'Kelola Artikel')
@section('page-title', 'Kelola Artikel')

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

    .dropdown-item {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
    }
    .dropdown-item:hover {
        background-color: #f1f5f9;
    }

    /* Thumbnail Image Hover */
    .thumbnail-img {
        transition: transform 0.3s ease;
    }
    .thumbnail-img:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')

{{-- STATISTIK ARTIKEL --}}
<div class="row row-cards mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Artikel</div>
                    <div class="ms-auto icon-shape bg-blue-lt">
                        <!-- Ikon News/Artikel Standar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" />
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-dark fw-bold">{{ number_format($artikels->total()) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Published</div>
                    <div class="ms-auto icon-shape bg-emerald-lt">
                        <!-- Ikon Check/Published Standar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-emerald fw-bold">{{ number_format($artikels->where('is_published', true)->count()) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Draft</div>
                    <div class="ms-auto icon-shape bg-amber-lt">
                        <!-- Ikon Edit/Draft Standar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9l5 5v5" /><path d="M13.5 6.5l4 4" /><path d="M16 19h6" /><path d="M19 16v6" />
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-amber fw-bold">{{ number_format($artikels->where('is_published', false)->count()) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kategori</div>
                    <div class="ms-auto icon-shape bg-purple-lt">
                        <!-- Ikon Folder/Kategori Standar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-purple fw-bold">4</div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER & SEARCH --}}
<div class="card card-modern mb-4">
    <div class="card-body p-4">
        <div class="row align-items-end g-3">
            <div class="col-12 col-xl-9">
                <form method="GET" class="row g-3 align-items-end" id="filterForm">
                    <div class="col-12 col-md-4">
                        <label class="form-label text-muted fw-semibold small">Pencarian Artikel</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-400" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="10" r="7" /><path d="M21 21l-6 -6" /></svg>
                            </span>
                            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari judul artikel..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Filter Status</label>
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Published</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Filter Kategori</label>
                        <select name="kategori" id="kategoriFilter" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="edukasi" {{ request('kategori') == 'edukasi' ? 'selected' : '' }}>Edukasi</option>
                            <option value="panduan" {{ request('kategori') == 'panduan' ? 'selected' : '' }}>Panduan</option>
                            <option value="berita" {{ request('kategori') == 'berita' ? 'selected' : '' }}>Berita</option>
                            <option value="harga_sampah" {{ request('kategori') == 'harga_sampah' ? 'selected' : '' }}>Harga Sampah</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1 text-white shadow-sm fw-semibold">Cari</button>
                        <a href="{{ route('admin.artikels.index') }}" class="btn btn-light border shadow-sm" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted m-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-12 col-xl-3 ms-auto text-xl-end mt-3 mt-xl-0 border-top pt-3 border-xl-0 pt-xl-0 border-slate-100">
                <a href="{{ route('admin.artikels.create') }}" class="btn btn-primary shadow-sm fw-bold w-100 w-xl-auto d-inline-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Artikel
                </a>
            </div>
        </div>
    </div>
</div>

{{-- TABEL ARTIKEL --}}
<div class="card card-modern">
    <div class="card-header d-flex justify-content-between align-items-center border-bottom bg-white p-4">
        <h3 class="card-title fw-bold text-dark m-0 fs-4">Daftar Artikel</h3>
        <span class="badge bg-slate-100 text-slate-700 border px-3 py-1 rounded-pill fw-semibold">
            Total: <span id="totalCount">{{ $artikels->total() }}</span> Data
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-modern mb-0 align-middle" id="artikelTable">
            <thead>
                <tr>
                    <th class="ps-4 text-center" width="60">No</th>
                    <th width="80" class="text-center">Gambar</th>
                    <th>Judul & Kategori</th>
                    <th width="180">Penulis</th>
                    <th width="120" class="text-center">Status</th>
                    <th width="140" class="text-center">Tanggal</th>
                    <th class="pe-4 text-center" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($artikels as $index => $artikel)
                <tr data-judul="{{ strtolower($artikel->judul) }}" data-status="{{ $artikel->is_published ? 'published' : 'draft' }}" data-kategori="{{ strtolower($artikel->kategori) }}" class="{{ !$artikel->is_published ? 'bg-slate-50' : '' }}">
                    <td class="ps-4 text-center text-muted fw-medium">{{ $artikels->firstItem() + $index }}</td>
                    <td class="text-center p-2">
                        <div class="overflow-hidden rounded-3 shadow-sm border mx-auto" style="width: 56px; height: 56px;">
                            @if($artikel->gambar)
                                <img src="{{ Storage::url($artikel->gambar) }}" alt="{{ $artikel->judul }}" class="thumbnail-img" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="bg-slate-100 d-flex align-items-center justify-content-center w-100 h-100 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark fs-6">{{ Str::limit($artikel->judul, 60) }}</div>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <span class="badge bg-blue-lt badge-modern rounded-pill">
                                {{ ucfirst(str_replace('_', ' ', $artikel->kategori)) }}
                            </span>
                            @if($artikel->galeri && $artikel->galeri->count() > 0)
                                <span class="badge bg-purple-lt badge-modern rounded-pill d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    {{ $artikel->galeri->count() }} Foto
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm rounded-circle bg-slate-200 text-slate-600 fw-bold shadow-sm border" style="font-size: 0.75rem;">
                                {{ strtoupper(substr($artikel->author?->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="fw-semibold text-dark small">{{ $artikel->author?->name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($artikel->is_published)
                            <span class="badge bg-emerald-lt badge-modern rounded-pill px-3">
                                Published
                            </span>
                        @else
                            <span class="badge bg-amber-lt badge-modern rounded-pill px-3">
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="fw-medium text-dark small">{{ $artikel->created_at?->format('d M Y') ?? '-' }}</div>
                        <div class="text-slate-400 small mt-1">{{ $artikel->created_at?->diffForHumans() ?? '' }}</div>
                    </td>
                    <td class="pe-4 text-center">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm rounded-pill fw-semibold border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px; padding: 8px;">
                                <!-- Ikon Eye untuk Detail -->
                                <li>
                                    <a href="{{ route('admin.artikels.show', $artikel->id) }}" class="dropdown-item d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Lihat Detail
                                    </a>
                                </li>
                                <!-- Ikon Edit -->
                                <li>
                                    <a href="{{ route('admin.artikels.edit', $artikel->id) }}" class="dropdown-item d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        Edit Artikel
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1 border-slate-100"></li>
                                <!-- Ikon Trash untuk Hapus -->
                                <li>
                                    <form method="POST" action="{{ route('admin.artikels.destroy', $artikel->id) }}" class="d-inline w-100" onsubmit="return confirmDelete(event, '{{ addslashes($artikel->judul) }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-rose fw-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                            Hapus Artikel
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" class="text-center py-5">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                        </div>
                        <h3 class="text-dark fw-bold fs-5">Tidak Ada Data Artikel</h3>
                        <p class="text-slate-500 small mb-3">Belum ada artikel yang ditambahkan atau pencarian tidak sesuai.</p>
                        <a href="{{ route('admin.artikels.create') }}" class="btn btn-primary rounded-pill fw-semibold px-4">
                            Tambah Sekarang
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($artikels->hasPages())
    <div class="card-footer bg-white border-top p-4 d-flex align-items-center justify-content-between">
        <p class="m-0 text-slate-500 fw-medium small d-none d-md-block">
            Menampilkan {{ $artikels->firstItem() }} sampai {{ $artikels->lastItem() }} dari {{ $artikels->total() }} Data
        </p>
        <div class="m-0">
            {{ $artikels->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    }
});

function confirmDelete(event, title) {
    event.preventDefault();

    Swal.fire({
        title: 'Hapus Artikel?',
        html: `Apakah Anda yakin ingin menghapus artikel "<strong class="text-dark">${title}</strong>"?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan!</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f43f5e',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'rounded-pill fw-bold px-4',
            cancelButton: 'rounded-pill fw-bold px-4',
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.closest('form').submit();
        }
    });

    return false;
}

// LIVE SEARCH & FILTER
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const kategoriFilter = document.getElementById('kategoriFilter');
const tableBody = document.getElementById('tableBody');
const totalCountSpan = document.getElementById('totalCount');

function filterTable() {
    if (!tableBody) return;

    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const statusVal = statusFilter ? statusFilter.value : '';
    const kategoriVal = kategoriFilter ? kategoriFilter.value : '';

    let visibleCount = 0;
    const rows = tableBody.querySelectorAll('tr');

    rows.forEach(row => {
        if (row.id === 'emptyRow') return;

        const judul = row.getAttribute('data-judul') || '';
        const status = row.getAttribute('data-status') || '';
        const kategori = row.getAttribute('data-kategori') || '';

        let show = true;

        if (searchTerm && !judul.includes(searchTerm)) show = false;
        if (show && statusVal) {
            if (statusVal === '1' && status !== 'published') show = false;
            if (statusVal === '0' && status !== 'draft') show = false;
        }
        if (show && kategoriVal && kategori !== kategoriVal) show = false;

        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });

    if (totalCountSpan) totalCountSpan.textContent = visibleCount;

    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.style.display = visibleCount === 0 ? '' : 'none';
}

if (searchInput) searchInput.addEventListener('keyup', filterTable);
if (statusFilter) statusFilter.addEventListener('change', filterTable);
if (kategoriFilter) kategoriFilter.addEventListener('change', filterTable);

filterTable();
</script>
@endpush
