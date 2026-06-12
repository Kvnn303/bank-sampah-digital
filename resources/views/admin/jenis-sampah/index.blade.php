@extends('layouts.admin')

@section('title', 'Kelola Jenis Sampah')
@section('page-title', 'Kelola Jenis Sampah & Harga')

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

    /* Modal Modern */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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

{{-- STATISTIK JENIS SAMPAH --}}
<div class="row row-cards mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Jenis</div>
                    <div class="ms-auto icon-shape bg-blue-lt">
                        <!-- Ikon Package/Box (Menggantikan ikon dokumen aneh sebelumnya) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon trash-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-dark fw-bold">{{ number_format(($totalAktif ?? 0) + ($totalNonaktif ?? 0)) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Jenis Aktif</div>
                    <div class="ms-auto icon-shape bg-emerald-lt">
                        <!-- Ikon Check-circle standar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-emerald fw-bold">{{ number_format($totalAktif ?? 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Jenis Nonaktif</div>
                    <div class="ms-auto icon-shape bg-rose-lt">
                        <!-- Ikon X-circle standar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-rose" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-rose fw-bold">{{ number_format($totalNonaktif ?? 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Kategori</div>
                    <div class="ms-auto icon-shape bg-purple-lt">
                        <!-- Ikon Grid/Kategori standar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
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
                        <label class="form-label text-muted fw-semibold small">Pencarian Data</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-400" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="10" r="7" /><path d="M21 21l-6 -6" /></svg>
                            </span>
                            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Nama sampah, kategori..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Filter Status</label>
                        <select name="status" id="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Filter Kategori</label>
                        <select name="kategori" id="kategoriFilter" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="Plastik" {{ request('kategori') == 'Plastik' ? 'selected' : '' }}>Plastik</option>
                            <option value="Kertas" {{ request('kategori') == 'Kertas' ? 'selected' : '' }}>Kertas</option>
                            <option value="Logam" {{ request('kategori') == 'Logam' ? 'selected' : '' }}>Logam</option>
                            <option value="Kaca" {{ request('kategori') == 'Kaca' ? 'selected' : '' }}>Kaca</option>
                            <option value="Elektronik" {{ request('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Organik" {{ request('kategori') == 'Organik' ? 'selected' : '' }}>Organik</option>
                            <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1 text-white shadow-sm fw-semibold">Cari</button>
                        <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light border shadow-sm" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted m-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-12 col-xl-3 ms-auto text-xl-end mt-3 mt-xl-0 border-top pt-3 border-xl-0 pt-xl-0 border-slate-100">
                <a href="{{ route('admin.jenis-sampah.create') }}" class="btn btn-primary shadow-sm fw-bold w-100 w-xl-auto d-inline-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Jenis
                </a>
            </div>
        </div>
    </div>
</div>

{{-- TABEL JENIS SAMPAH --}}
<div class="card card-modern">
    <div class="card-header d-flex justify-content-between align-items-center border-bottom bg-white p-4">
        <h3 class="card-title fw-bold text-dark m-0 fs-4">Daftar Jenis Sampah</h3>
        <span class="badge bg-slate-100 text-slate-700 border px-3 py-1 rounded-pill fw-semibold">
            Total: <span id="totalCount">{{ $jenisSampah->total() }}</span> Data
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-modern mb-0 align-middle" id="jenisTable">
            <thead>
                <tr>
                    <th class="ps-4 text-center" width="60">No</th>
                    <th>Nama Sampah & Keterangan</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga /kg</th>
                    <th class="text-center">Statistik</th>
                    <th class="text-center">Status</th>
                    <th class="pe-4 text-center" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($jenisSampah as $index => $j)
                <tr data-status="{{ $j->is_active ? 'aktif' : 'nonaktif' }}" data-kategori="{{ strtolower($j->kategori ?? 'umum') }}" data-nama="{{ strtolower($j->nama) }}" class="{{ !$j->is_active ? 'opacity-75' : '' }}">
                    <td class="ps-4 text-center text-muted fw-medium">{{ $jenisSampah->firstItem() + $index }}</td>

                    <td>
                        <div class="fw-bold text-dark fs-6">{{ $j->nama }}</div>
                        @if($j->keterangan)
                            <div class="text-slate-500 small mt-1 d-flex align-items-start">
                                <!-- Ikon Align-left (keterangan) standar -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 mt-1 flex-shrink-0"><line x1="21" y1="6" x2="3" y2="6"/><line x1="15" y1="12" x2="3" y2="12"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                                {{ Str::limit($j->keterangan, 50) }}
                            </div>
                        @endif
                    </td>

                    <td>
                        @php
                            $badgeColor = match(strtolower($j->kategori)) {
                                'organik' => 'bg-emerald-lt',
                                'anorganik' => 'bg-blue-lt',
                                'b3' => 'bg-rose-lt',
                                default => 'bg-slate-100 text-slate-700 border'
                            };
                        @endphp
                        <span class="badge {{ $badgeColor }} badge-modern rounded-pill">
                            {{ $j->kategori ?? 'Umum' }}
                        </span>
                    </td>

                    <td class="text-end">
                        <div class="fw-bold text-emerald fs-5">
                            Rp {{ number_format($j->harga_per_kg, 0, ',', '.') }}
                        </div>
                    </td>

                    <td class="text-center">
                        <div class="text-dark fw-semibold">{{ $j->tabungan_count ?? 0 }}</div>
                        <div class="text-slate-500 small">Transaksi</div>
                    </td>

                    <td class="text-center">
                        @if($j->is_active)
                            <span class="badge bg-emerald-lt badge-modern rounded-pill px-3">Aktif</span>
                        @else
                            <span class="badge bg-rose-lt badge-modern rounded-pill px-3">Nonaktif</span>
                        @endif
                    </td>

                    <td class="pe-4 text-center">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm rounded-pill fw-semibold border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px; padding: 8px;">
                                <!-- Detail -->
                                <li>
                                    <a href="{{ route('admin.jenis-sampah.show', $j->id) }}" class="dropdown-item d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Lihat Detail
                                    </a>
                                </li>
                                <!-- Edit -->
                                <li>
                                    <a href="{{ route('admin.jenis-sampah.edit', $j->id) }}" class="dropdown-item d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit Data
                                    </a>
                                </li>
                                <!-- Ubah Harga (Ikon Price Tag) -->
                                <li>
                                    <button type="button"
                                            class="dropdown-item d-flex align-items-center text-emerald fw-semibold"
                                            data-id="{{ $j->id }}"
                                            data-nama="{{ $j->nama }}"
                                            data-harga="{{ $j->harga_per_kg }}"
                                            onclick="showHargaModal(this.dataset.id, this.dataset.nama, this.dataset.harga)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                        Ubah Harga
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider my-1 border-slate-100"></li>

                                <!-- Toggle Aktif/Nonaktif -->
                                <li>
                                    <form method="POST" action="{{ route('admin.jenis-sampah.toggle', $j->id) }}" class="d-inline w-100">
                                        @csrf @method('PUT')
                                        <button type="submit" class="dropdown-item d-flex align-items-center {{ $j->is_active ? 'text-rose' : 'text-emerald' }}" onclick="return confirm('{{ $j->is_active ? 'Nonaktifkan' : 'Aktifkan' }} jenis sampah ini?')">
                                            @if($j->is_active)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                                                Nonaktifkan
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64A9 9 0 0 1 20.77 15"/><path d="M6.16 6.16a9 9 0 1 0 12.68 12.68"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                                                Aktifkan
                                            @endif
                                        </button>
                                    </form>
                                </li>

                                <!-- Hapus (hanya jika belum ada transaksi) -->
                                @if(($j->tabungan_count ?? 0) == 0)
                                <li>
                                    <form method="POST" action="{{ route('admin.jenis-sampah.destroy', $j->id) }}" class="d-inline w-100">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-rose fw-semibold" onclick="return confirm('Yakin hapus jenis sampah {{ $j->nama }}?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
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
                    <td colspan="7" class="text-center py-5">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                        </div>
                        <h3 class="text-dark fw-bold fs-5">Tidak Ada Data Jenis Sampah</h3>
                        <p class="text-slate-500 small mb-3">Belum ada jenis sampah yang terdaftar atau pencarian tidak sesuai.</p>
                        <a href="{{ route('admin.jenis-sampah.create') }}" class="btn btn-primary rounded-pill fw-semibold px-4">
                            Tambah Sekarang
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($jenisSampah->hasPages())
    <div class="card-footer bg-white border-top p-4 d-flex align-items-center justify-content-between">
        <p class="m-0 text-slate-500 fw-medium small d-none d-md-block">
            Menampilkan {{ $jenisSampah->firstItem() }} sampai {{ $jenisSampah->lastItem() }} dari {{ $jenisSampah->total() }} Data
        </p>
        <div class="m-0">
            {{ $jenisSampah->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

{{-- MODAL UBAH HARGA --}}
<div class="modal fade" id="modalHarga" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-emerald-lt" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <h5 class="modal-title fw-bold text-dark fs-4 mb-0">Ubah Harga Sampah</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.jenis-sampah.harga', '__ID__') }}" id="formHarga" class="form-harga">
                @csrf
                @method('PUT')
                <input type="hidden" name="jenis_sampah_id" id="jenisSampahId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold small">Jenis Sampah</label>
                        <input type="text" id="namaHarga" class="form-control bg-light border-0 fw-bold text-dark" readonly>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted fw-semibold small">Harga Saat Ini</label>
                            <input type="text" id="hargaLama" class="form-control bg-light border-0 fw-bold text-dark" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-dark fw-bold small required">Harga Baru (Rp/kg)</label>
                            <div class="input-group input-group-flat shadow-sm">
                                <span class="input-group-text bg-white fw-bold">Rp</span>
                                <input type="number" name="harga_per_kg" class="form-control fw-bold text-emerald" placeholder="0" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-dark fw-bold small required">Alasan Perubahan</label>
                        <textarea name="alasan" class="form-control shadow-sm" rows="3" placeholder="Contoh: Menyesuaikan harga pasar pengepul terbaru" required></textarea>
                        <div class="text-muted small mt-2 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Wajib diisi untuk keperluan log riwayat harga
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-top p-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-light border fw-semibold shadow-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpanHarga" class="btn btn-primary fw-bold shadow-sm px-4">
                        <span id="textSimpanHarga">Simpan Harga</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    }
});

function showHargaModal(id, nama, harga) {
    const form = document.getElementById('formHarga');
    const modalEl = document.getElementById('modalHarga');

    if (!form || !modalEl) {
        alert('Gagal membuka modal. Silakan refresh halaman.');
        return;
    }

    // Set form action — replaces __ID__ placeholder with actual ID
    const actionUrl = '{{ route('admin.jenis-sampah.harga', '__ID__') }}'.replace('__ID__', id);
    form.action = actionUrl;

    // Fill form fields
    document.getElementById('namaHarga').value = nama;
    document.getElementById('hargaLama').value = 'Rp ' + Number(harga).toLocaleString('id-ID');
    form.querySelector('input[name="harga_per_kg"]').value = Number(harga);
    form.querySelector('textarea[name="alasan"]').value = '';

    // Show modal
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
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

        const nama = row.getAttribute('data-nama') || '';
        const status = row.getAttribute('data-status') || '';
        const kategori = row.getAttribute('data-kategori') || '';

        let show = true;

        if (searchTerm && !nama.includes(searchTerm)) show = false;
        if (show && statusVal) {
            if (statusVal === '1' && status !== 'aktif') show = false;
            if (statusVal === '0' && status !== 'nonaktif') show = false;
        }
        if (show && kategoriVal && kategori !== kategoriVal.toLowerCase()) show = false;

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

// Visual feedback saat submit form modal harga
const formHarga = document.getElementById('formHarga');
if (formHarga) {
    formHarga.addEventListener('submit', function(e) {
        const btn = document.getElementById('btnSimpanHarga');
        const text = document.getElementById('textSimpanHarga');
        if (btn && text) {
            // Nonaktifkan tombol agar tidak double-submit
            btn.disabled = true;
            text.textContent = 'Menyimpan...';
            // Biarkan form submit ke server secara normal — jangan intercept/hide modal
        }
    });
}

filterTable();
</script>
@endpush
