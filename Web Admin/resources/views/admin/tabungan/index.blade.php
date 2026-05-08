@extends('layouts.admin')

@section('title', 'Data Tabungan Sampah')
@section('page-title', 'Tabungan Sampah')

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
</style>
@endpush

@section('content')

<!-- Statistik Utama -->
<div class="row row-cards mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Sampah</div>
                    <div class="ms-auto icon-shape bg-emerald-lt">
                        <!-- Ikon Recycle/Daun -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 17l-2 2l2 2m-2 -2h9a2 2 0 0 0 1.75 -2.75l-.55 -1" /><path d="M8.536 11l-.732 -2.732l-2.732 .732" /><path d="M7.804 8.268l-4.5 7.794a2 2 0 0 0 1.504 2.97l1.141 .011" /><path d="M15.464 11l.732 -2.732l2.732 .732" /><path d="M16.196 8.268l4.5 7.794a2 2 0 0 1 -1.504 2.97l-1.141 .011" />
                        </svg>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-1">
                    <div class="h1 mb-0 fs-1 text-dark fw-bold">{{ number_format($totalKg, 1) }}</div>
                    <div class="text-muted fw-semibold">kg</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Nilai</div>
                    <div class="ms-auto icon-shape bg-purple-lt">
                        <!-- Ikon Dompet/Wallet -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-purple fw-bold">Rp {{ number_format($totalNilai) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Sampah Bulan Ini</div>
                    <div class="ms-auto icon-shape bg-blue-lt">
                        <!-- Ikon Calendar/Box -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="5" width="16" height="16" rx="2" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M10 16h4" />
                        </svg>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-1">
                    <div class="h1 mb-0 fs-1 text-blue-modern fw-bold">{{ number_format($bulanIniKg, 1) }}</div>
                    <div class="text-muted fw-semibold">kg</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nilai Bulan Ini</div>
                    <div class="ms-auto icon-shape bg-amber-lt">
                        <!-- Ikon Chart Trending Up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" />
                        </svg>
                    </div>
                </div>
                <div class="h1 mb-0 fs-1 text-amber fw-bold">Rp {{ number_format($bulanIniNilai) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Aksi -->
<div class="card card-modern mb-4">
    <div class="card-body p-4">
        <div class="row align-items-end g-3">
            <div class="col-12 col-xl-10">
                <form method="GET" action="{{ route('admin.tabungan.index') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Nasabah</label>
                        <select name="nasabah_id" class="form-select">
                            <option value="">Semua Nasabah</option>
                            @foreach($nasabahList as $n)
                                <option value="{{ $n->id }}" {{ request('nasabah_id') == $n->id ? 'selected' : '' }}>
                                    {{ $n->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label text-muted fw-semibold small">Jenis Sampah</label>
                        <select name="jenis_sampah_id" class="form-select">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisSampahList as $j)
                                <option value="{{ $j->id }}" {{ request('jenis_sampah_id') == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted fw-semibold small">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted fw-semibold small">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary flex-grow-1 text-white shadow-sm fw-semibold">
                            Cari
                        </button>
                        <a href="{{ route('admin.tabungan.index') }}" class="btn btn-light border shadow-sm" title="Reset Filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted m-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                        </a>
                    </div>
                </form>
            </div>

            <div class="col-12 col-xl-2 ms-auto text-xl-end mt-3 mt-xl-0 border-top pt-3 border-xl-0 pt-xl-0 border-slate-100">
                <a href="{{ route('admin.tabungan.create') }}" class="btn btn-primary shadow-sm fw-bold w-100 w-xl-auto d-inline-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Input Setoran
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Tabungan -->
<div class="card card-modern">
    <div class="card-header d-flex justify-content-between align-items-center border-bottom bg-white p-4">
        <h3 class="card-title fw-bold text-dark m-0 fs-4">Riwayat Transaksi</h3>
        <span class="badge bg-slate-100 text-slate-700 border px-3 py-1 rounded-pill fw-semibold">
            Total: {{ $tabungan->total() }} Data
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-modern mb-0 align-middle table-nowrap">
            <thead>
                <tr>
                    <th class="ps-4 text-center d-none d-md-table-cell" width="60">No</th>
                    <th>Tanggal</th>
                    <th>Nasabah</th>
                    <th>Jenis Sampah</th>
                    <th class="text-end">Berat</th>
                    <th class="text-end d-none d-sm-table-cell">Harga/kg</th>
                    <th class="text-end">Total Nilai</th>
                    <th class="d-none d-lg-table-cell">Admin</th>
                    <th class="d-none d-xl-table-cell">Catatan</th>
                    <th class="pe-4 text-center" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tabungan as $index => $t)
                <tr>
                    <td class="ps-4 text-center text-muted fw-medium d-none d-md-table-cell">{{ $tabungan->firstItem() + $index }}</td>
                    <td>
                        <div class="fw-bold text-dark">{{ $t->tanggal_setor->format('d M Y') }}</div>
                        <div class="text-slate-500 small mt-1">{{ $t->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-blue-lt text-blue-modern rounded-circle fw-bold shadow-sm border me-2" style="font-size: 0.75rem;">
                                {{ strtoupper(substr($t->nasabah->nama_lengkap ?? 'A', 0, 1)) }}
                            </div>
                            <div class="fw-semibold text-dark">{{ $t->nasabah->nama_lengkap ?? '-' }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-emerald-lt badge-modern rounded-pill px-3">{{ $t->jenisSampah->nama ?? '-' }}</span>
                    </td>
                    <td class="text-end fw-bold text-dark">
                        {{ number_format($t->berat_kg, 1) }} kg
                    </td>
                    <td class="text-end text-slate-500 d-none d-sm-table-cell">
                        Rp {{ number_format($t->harga_per_kg_saat_itu) }}
                    </td>
                    <td class="text-end fw-bold text-emerald fs-6">
                        Rp {{ number_format($t->nilai_rupiah) }}
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <div class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span class="text-slate-600 fw-medium small">{{ $t->admin->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="d-none d-xl-table-cell">
                        <span class="text-slate-500 small text-truncate d-block" style="max-width: 180px;" title="{{ $t->catatan }}">
                            {{ $t->catatan ?? '-' }}
                        </span>
                    </td>
                    <td class="pe-4 text-center">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm rounded-pill fw-semibold border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px; padding: 8px;">
                                <li>
                                    <!-- Tombol LIHAT DETAIL -->
                                    <a href="{{ route('admin.tabungan.show', $t->id) }}" class="dropdown-item d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detail Setoran
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1 border-slate-100"></li>
                                <li>
                                    <!-- Tombol HAPUS -->
                                    <form method="POST" action="{{ route('admin.tabungan.destroy', $t->id) }}" onsubmit="return confirmDelete(event, '{{ $t->tanggal_setor->format('d M Y') }}', '{{ addslashes($t->nasabah->nama_lengkap ?? '') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item d-flex align-items-center text-rose fw-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                            Batalkan Setoran
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-slate-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"/></svg>
                        </div>
                        <h3 class="text-dark fw-bold fs-5">Tidak Ada Data Setoran</h3>
                        <p class="text-slate-500 small mb-3">Belum ada riwayat transaksi tabungan sampah atau pencarian tidak sesuai filter.</p>
                        <a href="{{ route('admin.tabungan.create') }}" class="btn btn-primary rounded-pill fw-semibold px-4">
                            Input Setoran Sekarang
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tabungan->hasPages())
    <div class="card-footer bg-white border-top p-4 d-flex align-items-center justify-content-between">
        <p class="m-0 text-slate-500 fw-medium small d-none d-md-block">
            Menampilkan {{ $tabungan->firstItem() }} sampai {{ $tabungan->lastItem() }} dari {{ $tabungan->total() }} Data
        </p>
        <div class="m-0">
            {{ $tabungan->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(event, tanggal, nama) {
        event.preventDefault();

        Swal.fire({
            title: 'Batalkan Setoran?',
            html: `Apakah Anda yakin ingin menghapus data setoran atas nama <strong class="text-dark">${nama}</strong> tanggal <strong class="text-dark">${tanggal}</strong>?<br><small class="text-muted mt-2 d-block">Saldo nasabah akan otomatis disesuaikan kembali.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus Data!',
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
</script>
@endpush
