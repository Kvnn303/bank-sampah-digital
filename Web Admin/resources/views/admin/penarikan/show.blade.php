@extends('layouts.admin')

@section('title', 'Detail Penarikan')
@section('page-title', 'Detail Penarikan')

@push('styles')
<style>
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

    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }

    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }

    .text-rose { color: #f43f5e !important; }
    .bg-rose-lt { background-color: rgba(244, 63, 94, 0.1) !important; color: #f43f5e !important; }

    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .modal-backdrop.show {
        opacity: 0.5;
    }

    /* Character counter */
    .char-count {
        font-size: 0.75rem;
        transition: color 0.2s;
    }
    .char-count.warning { color: #f59e0b; }
    .char-count.danger { color: #f43f5e; }
    .char-count.success { color: #10b981; }
</style>
@endpush

@section('content')

<div class="row g-4 justify-content-center">
    <div class="col-lg-10 col-xl-9">

        <!-- FLASH MESSAGES - PINDAH KE ATAS -->
        @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center rounded-3 mb-4 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center rounded-3 mb-4 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        <!-- VALIDATION ERRORS -->
        @if($errors->any())
        <div class="alert alert-warning d-flex align-items-start rounded-3 mb-4 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 mt-0 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <div>
                <strong class="d-block mb-1">Periksa kembali:</strong>
                @foreach($errors->all() as $error)
                <div class="small">• {{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="card card-modern mb-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-rose-lt me-4" style="width: 64px; height: 64px; border-radius: 20px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-rose" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12h-7m3 -3l-3 3l3 3"/></svg>
                        </div>
                        <div>
                            <div class="text-slate-500 fw-semibold text-uppercase small mb-1" style="letter-spacing: 0.5px;">Nominal Penarikan</div>
                            <div class="display-6 mb-0 text-dark fw-bold">
                                Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}
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

        <div class="row g-4 mb-4">

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
                                <span class="fw-bold text-emerald fs-4">
                                    Rp {{ number_format($saldoRealtime, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    {{ \Carbon\Carbon::parse($penarikan->tanggal_ambil)->format('d M Y') }}
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

                        @if($penarikan->catatan_nasabah)
                            <div class="mt-4 p-3 bg-slate-50 border rounded-3 text-start">
                                <div class="text-slate-500 fw-semibold small mb-1">Catatan Nasabah:</div>
                                <div class="fst-italic text-dark small">"{{ $penarikan->catatan_nasabah }}"</div>
                            </div>
                        @endif

                        @if($penarikan->alasan_penolakan)
                            <div class="mt-3 p-3 rounded-3 text-start" style="background-color: #fff1f2; border: 1px solid #fecdd3;">
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

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top border-slate-200">
            <a href="{{ route('admin.penarikan.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 w-100 w-sm-auto d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>

            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-center">
                @if($penarikan->status == 'pending')
                    <button type="button" class="btn btn-outline-danger bg-white border-2 rounded-pill fw-bold px-4" data-bs-toggle="modal" data-bs-target="#modalTolak">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        Tolak Pengajuan
                    </button>
                    <form method="POST" action="{{ route('admin.penarikan.setujui', $penarikan->id) }}" id="formSetujui">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm px-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" fill="none"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Setujui & Proses
                        </button>
                    </form>
                @endif

                @if($penarikan->status == 'diproses')
                    <form method="POST" action="{{ route('admin.penarikan.selesai', $penarikan->id) }}" id="formSelesai">
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

<!-- MODAL TOLAK - SUDAH DIPERBAIKI -->
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
            <form method="POST" id="formTolak" action="{{ route('admin.penarikan.tolak', $penarikan->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="alert alert-warning d-flex align-items-start rounded-3 mb-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 mt-0 flex-shrink-0"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <div class="small">Anda akan menolak penarikan sebesar <strong>Rp {{ number_format($penarikan->nominal, 0, ',', '.') }}</strong></div>
                    </div>
                    <div class="mb-0">
                        <label for="alasan_penolakan" class="form-label text-dark fw-bold small">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" 
                                  id="alasan_penolakan"
                                  class="form-control shadow-sm" 
                                  rows="3"
                                  style="resize: none;"
                                  placeholder="Tuliskan alasan penolakan minimal 10 karakter..."
                                  required
                                  minlength="10"
                                  maxlength="500"></textarea>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="text-muted small d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                Alasan ini akan ditampilkan kepada nasabah.
                            </div>
                            <span id="charCount" class="char-count text-muted">0/500</span>
                        </div>
                        <div id="validationMsg" class="text-danger small mt-1 d-none">
                            Alasan penolakan minimal 10 karakter
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-top p-3 d-flex justify-content-end">
                    <button type="button" class="btn btn-light border fw-semibold shadow-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmitTolak" class="btn btn-danger fw-bold shadow-sm rounded-pill px-4 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        <span class="btn-text">Konfirmasi Penolakan</span>
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
    const formTolak = document.getElementById('formTolak');
    const btnSubmitTolak = document.getElementById('btnSubmitTolak');
    const modalTolak = document.getElementById('modalTolak');
    const textarea = document.getElementById('alasan_penolakan');
    const charCount = document.getElementById('charCount');
    const validationMsg = document.getElementById('validationMsg');
    let isSubmitting = false;

    // Character counter real-time
    textarea.addEventListener('input', function() {
        const len = this.value.length;
        charCount.textContent = len + '/500';
        
        // Update warna berdasarkan panjang
        charCount.className = 'char-count';
        if (len < 10) {
            charCount.classList.add('danger');
            validationMsg.classList.remove('d-none');
            textarea.classList.add('is-invalid');
        } else if (len > 450) {
            charCount.classList.add('warning');
            validationMsg.classList.add('d-none');
            textarea.classList.remove('is-invalid');
        } else {
            charCount.classList.add('success');
            validationMsg.classList.add('d-none');
            textarea.classList.remove('is-invalid');
        }
    });

    // Reset form saat modal dibuka
    modalTolak.addEventListener('show.bs.modal', function() {
        formTolak.reset();
        isSubmitting = false;
        btnSubmitTolak.disabled = false;
        charCount.textContent = '0/500';
        charCount.className = 'char-count text-muted';
        validationMsg.classList.add('d-none');
        textarea.classList.remove('is-invalid');
        btnSubmitTolak.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            <span class="btn-text">Konfirmasi Penolakan</span>
        `;
    });

    // Handle submit
    formTolak.addEventListener('submit', function(e) {
        // Cegah double submit
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        const alasan = textarea.value.trim();

        // Validasi: minimal 10 karakter (SINKRON DENGAN CONTROLLER)
        if (alasan.length < 10) {
            e.preventDefault();
            textarea.focus();
            textarea.classList.add('is-invalid');
            validationMsg.classList.remove('d-none');
            return false;
        }

        // Set loading state - TANPA pointer-events: none
        isSubmitting = true;
        btnSubmitTolak.disabled = true;
        btnSubmitTolak.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Memproses...
        `;

        // Form akan submit secara normal
    });

    // Form setujui
    const formSetujui = document.getElementById('formSetujui');
    if (formSetujui) {
        formSetujui.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin menyetujui penarikan dana ini?')) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Form selesai
    const formSelesai = document.getElementById('formSelesai');
    if (formSelesai) {
        formSelesai.addEventListener('submit', function(e) {
            if (!confirm('Tandai sebagai selesai? Pastikan uang tunai telah diterima oleh nasabah!')) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endpush