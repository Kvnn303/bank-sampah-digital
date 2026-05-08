@extends('layouts.admin')

@section('title', 'Detail Penarikan')
@section('page-title', 'Detail Penarikan')

@push('styles')
<style>
    /* Styling Modern yang Seragam */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .badge-modern {
        padding: 0.5em 1em;
        font-weight: 600;
        letter-spacing: 0.3px;
        font-size: 0.8rem;
    }

    .info-row {
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
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

    /* Modal Custom */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')

<div class="row g-4 justify-content-center">
    <div class="col-lg-10 col-xl-9">

        <!-- Header: Status & Nominal Utama -->
        <div class="card card-modern mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-rose-lt me-4" style="width: 64px; height: 64px; border-radius: 20px;">
                            <!-- Ikon Uang Keluar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-rose" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12h-7m3 -3l-3 3l3 3"/></svg>
                        </div>
                        <div>
                            <div class="text-slate-500 fw-semibold text-uppercase small mb-1" style="letter-spacing: 0.5px;">Nominal Penarikan</div>
                            <div class="display-6 mb-0 text-dark fw-bold">
                                Rp {{ number_format($penarikan->nominal) }}
                            </div>
                        </div>
                    </div>

                    <div class="text-md-end border-top border-md-top-0 pt-3 pt-md-0 border-slate-100">
                        <div class="text-slate-400 small fw-medium mb-2">Status Saat Ini:</div>
                        @if($penarikan->status == 'pending')
                            <span class="badge bg-amber-lt badge-modern rounded-pill border border-amber">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Menunggu Persetujuan
                            </span>
                        @elseif($penarikan->status == 'diproses')
                            <span class="badge bg-blue-lt badge-modern rounded-pill border border-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                                Sedang Diproses
                            </span>
                        @elseif($penarikan->status == 'selesai')
                            <span class="badge bg-emerald-lt badge-modern rounded-pill border border-emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                Penarikan Selesai
                            </span>
                        @elseif($penarikan->status == 'ditolak')
                            <span class="badge bg-rose-lt badge-modern rounded-pill border border-rose">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                Pengajuan Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Grid -->
        <div class="row g-4 mb-4">

            <!-- Kolom Kiri: Info Nasabah -->
            <div class="col-md-6">
                <div class="card card-modern h-100">
                    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                        <div class="icon-shape bg-blue-lt me-3" style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-5">Informasi Nasabah</h3>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="info-row pt-0">
                            <span class="text-slate-500 small fw-medium">Nama Nasabah</span>
                            <span class="fw-bold text-dark">{{ $penarikan->nasabah->nama_lengkap ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-slate-500 small fw-medium">Nomor Telepon</span>
                            <span class="fw-bold text-dark">{{ $penarikan->nasabah->no_telepon ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-slate-500 small fw-medium">Alamat</span>
                            <span class="text-dark small text-end" style="max-width: 60%;">{{ $penarikan->nasabah->alamat ?? '-' }}</span>
                        </div>

                        <div class="mt-4 p-3 bg-slate-50 border rounded-4">
                            <div class="text-slate-500 small fw-medium mb-1">Sisa Saldo Saat Ini</div>
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald me-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M8.5 11.5 11 14l4-4"/></svg>
                                <span class="fw-bold text-emerald fs-4">Rp {{ number_format($penarikan->nasabah->saldo ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Detail Transaksi -->
            <div class="col-md-6">
                <div class="card card-modern h-100">
                    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                        <div class="icon-shape bg-purple-lt me-3" style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
                        </div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-5">Rincian Pengajuan</h3>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="info-row pt-0">
                            <span class="text-slate-500 small fw-medium">Tanggal Pengajuan</span>
                            <span class="fw-semibold text-dark">{{ $penarikan->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="text-slate-500 small fw-medium">Tanggal Diambil</span>
                            <span class="fw-semibold text-dark">
                                @if($penarikan->tanggal_ambil)
                                    {{ $penarikan->tanggal_ambil->format('d M Y') }}
                                @else
                                    <span class="text-slate-400 fst-italic">Belum diambil</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="text-slate-500 small fw-medium">Diproses Oleh</span>
                            <span class="fw-medium text-dark d-flex align-items-center gap-2">
                                @if($penarikan->diprosesoleh)
                                    <div class="avatar avatar-sm rounded-circle bg-slate-200 text-slate-600 fw-bold border shadow-sm" style="font-size: 0.7rem; width: 24px; height: 24px;">
                                        {{ strtoupper(substr($penarikan->diprosesoleh->name ?? 'A', 0, 1)) }}
                                    </div>
                                    {{ $penarikan->diprosesoleh->name }}
                                @else
                                    <span class="text-slate-400 fst-italic">-</span>
                                @endif
                            </span>
                        </div>

                        <!-- Catatan & Alasan Penolakan -->
                        @if($penarikan->catatan_nasabah)
                            <div class="mt-4 p-3 bg-slate-50 border rounded-3 text-start">
                                <div class="text-slate-500 fw-semibold small mb-1">Catatan Nasabah:</div>
                                <div class="fst-italic text-dark small">"{{ $penarikan->catatan_nasabah }}"</div>
                            </div>
                        @endif

                        @if($penarikan->alasan_penolakan)
                            <div class="mt-3 p-3 bg-rose-lt border border-rose rounded-3 text-start" style="background-color: #fff1f2; border-color: #fecdd3 !important;">
                                <div class="text-rose fw-bold small mb-1 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    Alasan Penolakan:
                                </div>
                                <div class="text-danger small">{{ $penarikan->alasan_penolakan }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksi Bawah -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top border-slate-200">
            <a href="{{ route('admin.penarikan.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 w-100 w-sm-auto d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>

            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-center">
                @if($penarikan->status == 'pending')
                    <!-- Tolak -->
                    <button type="button" class="btn btn-outline-danger bg-white border-2 rounded-pill fw-bold px-4" onclick="showTolakModal({{ $penarikan->id }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        Tolak Pengajuan
                    </button>
                    <!-- Setujui -->
                    <form method="POST" action="{{ route('admin.penarikan.setujui', $penarikan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui penarikan dana ini?')">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Setujui & Proses
                        </button>
                    </form>
                @endif

                @if($penarikan->status == 'diproses')
                    <!-- Selesai -->
                    <form method="POST" action="{{ route('admin.penarikan.selesai', $penarikan->id) }}" onsubmit="return confirm('Tandai sebagai selesai? Pastikan uang tunai telah diterima oleh nasabah!')">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-success rounded-pill fw-bold shadow-sm px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            Tandai Penarikan Selesai
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Modal Tolak -->
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
                                  placeholder="Contoh: Saldo tidak mencukupi, Data tidak valid..."
                                  required></textarea>
                        <div class="text-muted small mt-2 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            Alasan ini akan ditampilkan kepada nasabah.
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-top p-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-light border fw-semibold shadow-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold shadow-sm rounded-pill px-4 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Konfirmasi Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showTolakModal(id) {
    const form = document.getElementById('formTolak');
    form.action = '/admin/penarikan/' + id + '/tolak';
    const modal = new bootstrap.Modal(document.getElementById('modalTolak'));
    modal.show();
}
</script>
@endpush
