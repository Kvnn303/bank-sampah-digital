@extends('layouts.admin')

@section('title', 'Kelola Penarikan Dana')
@section('page-title', 'Kelola Penarikan')

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
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
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

    /* Modal Custom */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')

<div class="row row-cards mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Menunggu</div>
                    <div class="ms-auto icon-shape bg-amber-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                    </div>
                </div>
                <div class="h1 mb-1 fs-1 text-dark fw-bold">{{ $totalPending }}</div>
                <div class="text-slate-500 small fw-medium">Pengajuan Baru</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Diproses</div>
                    <div class="ms-auto icon-shape bg-blue-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                    </div>
                </div>
                <div class="h1 mb-1 fs-1 text-blue-modern fw-bold">{{ $totalDiproses }}</div>
                <div class="text-slate-500 small fw-medium">Sedang Berjalan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Selesai</div>
                    <div class="ms-auto icon-shape bg-emerald-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M9 12l2 2l4 -4m6 2a9 9 0 1 1 -18 0a9 9 0 0 1 18 0"/></svg>
                    </div>
                </div>
                <div class="h1 mb-1 fs-1 text-emerald fw-bold">{{ $totalSelesai }}</div>
                <div class="text-slate-500 small fw-medium">Transaksi Sukses</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Dicairkan</div>
                    <div class="ms-auto icon-shape bg-purple-lt">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12h-4a2 2 0 0 0 0 4h4"/></svg>
                    </div>
                </div>
                <div class="h3 mb-1 fs-3 text-purple fw-bold">Rp {{ number_format($totalNominal, 0, ',', '.') }}</div>
                <div class="text-slate-500 small fw-medium">Total Nilai Keluar</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-modern mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label text-muted fw-semibold small">Cari Nasabah</label>
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-400" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="10" cy="10" r="7"/><path d="M21 21l-6 -6"/></svg>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Nama nasabah..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label text-muted fw-semibold small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending"   {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses"  {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai"   {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak"   {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
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
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary flex-grow-1 text-white shadow-sm fw-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M5.5 5h13a1 1 0 0 1 .5 1.5l-5 5.5v4l-4 2v-6l-5 -5.5a1 1 0 0 1 .5 -1.5" /></svg>
                    Filter
                </button>
                <a href="{{ route('admin.penarikan.index') }}" class="btn btn-light border shadow-sm" title="Reset Filter">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted m-0" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card card-modern">
    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
        <h3 class="card-title fw-bold text-dark m-0 fs-4">Daftar Pengajuan Penarikan</h3>
        <span class="badge bg-slate-100 text-slate-700 border px-3 py-1 rounded-pill fw-semibold">
            Total: {{ $penarikan->total() }} Data
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-modern mb-0 align-middle table-nowrap">
            <thead class="bg-slate-50">
                <tr>
                    <th class="ps-4 text-center d-none d-md-table-cell" width="60">No</th>
                    <th>Nasabah</th>
                    <th class="text-end">Nominal</th>
                    <th class="text-end d-none d-sm-table-cell">Sisa Saldo</th>
                    <th>Tanggal Ajuan</th>
                    <th class="d-none d-lg-table-cell">Catatan</th>
                    <th class="text-center">Status</th>
                    <th class="d-none d-md-table-cell">Admin</th>
                    <th class="pe-4 text-center" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penarikan as $index => $p)
                <tr>
                    <td class="ps-4 text-center text-muted fw-medium d-none d-md-table-cell">{{ $penarikan->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-blue-lt text-blue-modern rounded-circle fw-bold shadow-sm border me-2" style="font-size: 0.75rem;">
                                {{ strtoupper(substr($p->nasabah->nama_lengkap ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $p->nasabah->nama_lengkap ?? '-' }}</div>
                                <div class="text-slate-500 small">{{ $p->nasabah->no_telepon ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="fw-bold text-rose fs-6">
                            Rp {{ number_format($p->nominal, 0, ',', '.') }}
                        </div>
                    </td>

                    <td class="text-end d-none d-sm-table-cell">
                        @php
                            $sisaSaldo = $p->nasabah->saldo_realtime ?? 0;
                            $saldoAwal = in_array($p->status, ['pending', 'diproses', 'selesai'])
                                         ? $sisaSaldo + $p->nominal
                                         : $sisaSaldo;
                        @endphp

                        @if(in_array($p->status, ['pending', 'diproses', 'selesai']))
                            <div class="text-muted small text-decoration-line-through mb-1" style="opacity: 0.6;">
                                Rp {{ number_format($saldoAwal, 0, ',', '.') }}
                            </div>
                        @endif

                        <div class="text-emerald fw-bold" style="font-size: 1.05rem;">
                            Rp {{ number_format($sisaSaldo, 0, ',', '.') }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-medium text-dark">{{ $p->created_at->format('d M Y') }}</div>
                        <div class="text-slate-400 small">{{ $p->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="text-slate-500 small text-truncate d-block" style="max-width: 180px;" title="{{ $p->catatan_nasabah }}">
                            {{ $p->catatan_nasabah ?? '-' }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($p->status == 'pending')
                            <span class="badge bg-amber-lt badge-modern rounded-pill px-3">Pending</span>
                        @elseif($p->status == 'diproses')
                            <span class="badge bg-blue-lt badge-modern rounded-pill px-3">Diproses</span>
                        @elseif($p->status == 'selesai')
                            <span class="badge bg-emerald-lt badge-modern rounded-pill px-3">Selesai</span>
                        @elseif($p->status == 'ditolak')
                            <span class="badge bg-rose-lt badge-modern rounded-pill px-3">Ditolak</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell text-slate-500 small fw-medium">
                        {{ $p->diprosesoleh->name ?? '-' }}
                    </td>
                    <td class="pe-4 text-center">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm rounded-pill fw-semibold border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="border-radius: 12px; padding: 8px;">
                                <li>
                                    <a href="{{ route('admin.penarikan.show', $p->id) }}" class="dropdown-item d-flex align-items-center text-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="3" /><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/></svg>
                                        Lihat Detail
                                    </a>
                                </li>

                                @if($p->status == 'pending')
                                    <li><hr class="dropdown-divider my-1 border-slate-100"></li>
                                    <li>
                                        <form method="POST" action="{{ route('admin.penarikan.setujui', $p->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui penarikan ini?')">
                                            @csrf @method('PUT')
                                            <button type="submit" class="dropdown-item d-flex align-items-center text-emerald fw-semibold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                Setujui Penarikan
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item d-flex align-items-center text-rose fw-semibold border-0 bg-transparent w-100 text-start" data-bs-toggle="modal" data-bs-target="#modalTolak" onclick="setTolakAction({{ $p->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                            Tolak Pengajuan
                                        </button>
                                    </li>
                                @endif

                                @if($p->status == 'diproses')
                                    <li><hr class="dropdown-divider my-1 border-slate-100"></li>
                                    <li>
                                        <button type="button" class="dropdown-item d-flex align-items-center text-blue-modern fw-semibold border-0 bg-transparent w-100 text-start" data-bs-toggle="modal" data-bs-target="#modalSelesai" onclick="setSelesaiAction({{ $p->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4m6 2a9 9 0 1 1 -18 0a9 9 0 0 1 18 0" /></svg>
                                            Tandai Selesai
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-slate-300" width="48" height="48" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M4 10h16"/><path d="M10 4v16"/></svg>
                        </div>
                        <h4 class="text-dark fw-bold fs-5">Tidak Ada Data Penarikan</h4>
                        <p class="text-slate-500 small mb-0">Belum ada riwayat pengajuan penarikan dana atau pencarian tidak sesuai.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($penarikan->hasPages())
    <div class="card-footer bg-white border-top p-4 d-flex align-items-center justify-content-between">
        <p class="m-0 text-slate-500 fw-medium small d-none d-md-block">
            Menampilkan {{ $penarikan->firstItem() }} sampai {{ $penarikan->lastItem() }} dari {{ $penarikan->total() }} Data
        </p>
        <div class="m-0">
            {{ $penarikan->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-rose-lt" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-rose" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </div>
                    <h5 class="modal-title fw-bold text-dark fs-4 mb-0">Tolak Penarikan</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formTolak">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-2">
                        <label class="form-label text-dark fw-bold small required">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan"
                                  class="form-control shadow-sm"
                                  rows="3"
                                  style="resize: none;"
                                  placeholder="Contoh: Saldo tidak mencukupi / Rekening tidak valid..."
                                  required></textarea>
                        <div class="text-muted small mt-2 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Alasan ini akan diinformasikan kepada nasabah yang bersangkutan.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-top p-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-light border fw-semibold shadow-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold shadow-sm rounded-pill px-4 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                        Konfirmasi Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSelesai" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-emerald-lt" style="width: 40px; height: 40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4m6 2a9 9 0 1 1 -18 0a9 9 0 0 1 18 0" /></svg>
                    </div>
                    <h5 class="modal-title fw-bold text-dark fs-4 mb-0">Selesaikan Penarikan</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formSelesai" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="alert alert-info bg-blue-lt border-0 text-blue-modern mb-4">
                        <div class="d-flex align-items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 flex-shrink-0 mt-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                            <span class="small fw-medium">Pastikan dana telah diserahkan atau ditransfer kepada nasabah sebelum menekan tombol Selesai.</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small">Bukti Transfer/Penyerahan <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="file" name="bukti_transfer" class="form-control shadow-sm" id="inputBuktiTransfer" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                        <div class="text-muted small mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>

                        <div class="mt-3 text-center d-none" id="previewContainer">
                            <img id="imagePreview" src="#" alt="Preview Bukti" class="img-fluid rounded border shadow-sm" style="max-height: 200px; object-fit: contain;">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-bold small">Catatan Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="catatan_admin"
                                  class="form-control shadow-sm"
                                  rows="2"
                                  style="resize: none;"
                                  placeholder="Contoh: Dana telah ditransfer ke DANA 08xxx / Uang diambil langsung..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-top p-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-light border fw-semibold shadow-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success bg-emerald fw-bold text-white border-0 shadow-sm rounded-pill px-4 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-white" width="18" height="18" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M5 12l5 5l10 -10" /></svg>
                        Konfirmasi Selesai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // JS Untuk Form Tolak
    function setTolakAction(id) {
        const form = document.getElementById('formTolak');
        form.action = '/admin/penarikan/' + id + '/tolak';
    }

    // JS Untuk Form Selesai
    function setSelesaiAction(id) {
        const form = document.getElementById('formSelesai');
        form.action = '/admin/penarikan/' + id + '/selesai';

        // Reset form preview setiap kali modal dibuka untuk transaksi baru
        document.getElementById('inputBuktiTransfer').value = '';
        document.getElementById('previewContainer').classList.add('d-none');
    }

    // JS Untuk Live Image Preview
    function previewImage(event) {
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('d-none');
        }
    }
</script>
@endpush
