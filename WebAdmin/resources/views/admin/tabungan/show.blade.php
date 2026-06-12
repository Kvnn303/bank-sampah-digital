@extends('layouts.admin')

@section('title', 'Detail Setoran')
@section('page-title', 'Detail Setoran')

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
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .badge-modern {
        padding: 0.4em 0.8em;
        font-weight: 600;
        letter-spacing: 0.3px;
        font-size: 0.75rem;
    }

    /* Info Box */
    .info-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.2s ease;
    }
    .info-box:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }

    /* Dashed Border untuk Kalkulasi */
    .border-dashed {
        border-bottom: 2px dashed #e2e8f0 !important;
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
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-11">

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark fs-2">Detail Setoran Sampah</h2>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-slate-500">Transaksi ID:</span>
                    <span class="badge bg-slate-100 text-slate-700 border px-2 py-1 fw-bold fs-6">#{{ $tabungan->id }}</span>
                </div>
            </div>
            <a href="{{ route('admin.tabungan.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
        </div>

        <div class="row g-4">

            <div class="col-lg-7 col-xl-8">

                <div class="card card-modern mb-4">
                    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                        <div class="icon-shape bg-blue-lt me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-4">Informasi Nasabah</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar avatar-xl rounded-circle bg-blue-lt text-blue-modern fw-bold shadow-sm border border-2 border-white" style="width: 70px; height: 70px; font-size: 1.8rem;">
                                {{ strtoupper(substr($tabungan->nasabah->nama_lengkap ?? 'A', 0, 1)) }}
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h3 class="mb-1 fw-bold text-dark fs-3">{{ $tabungan->nasabah->nama_lengkap ?? '-' }}</h3>
                                <div class="d-flex align-items-center flex-wrap gap-2 mt-1">
                                    <span class="text-slate-500 small">No. Rekening: <strong class="text-dark">{{ $tabungan->nasabah->no_rekening ?? '-' }}</strong></span>
                                    <span class="text-slate-300 d-none d-sm-inline">•</span>
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="text-slate-500 small">Status:</span>
                                        @if(($tabungan->nasabah->status_akun ?? '') == 'active')
                                            <span class="badge bg-emerald-lt badge-modern rounded-pill px-2 py-1">Aktif</span>
                                        @else
                                            <span class="badge bg-rose-lt badge-modern rounded-pill px-2 py-1">{{ ucfirst($tabungan->nasabah->status_akun ?? '-') }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if(($tabungan->nasabah->alamat ?? false) || ($tabungan->nasabah->no_telepon ?? false))
                        <div class="row g-3 pt-3 border-top">
                            @if($tabungan->nasabah->no_telepon)
                            <div class="col-sm-6">
                                <div class="info-box d-flex align-items-center h-100">
                                    <div class="bg-white p-2 rounded-circle shadow-sm border me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-500 m-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-slate-400 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Telepon</div>
                                        <div class="fw-bold text-dark">{{ $tabungan->nasabah->no_telepon }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($tabungan->nasabah->alamat)
                            <div class="col-sm-6">
                                <div class="info-box d-flex align-items-center h-100">
                                    <div class="bg-white p-2 rounded-circle shadow-sm border me-3 flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-slate-500 m-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"/><path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 7 8 11.7z"/></svg>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="text-slate-400 small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Alamat</div>
                                        <div class="fw-medium text-dark small text-truncate" title="{{ $tabungan->nasabah->alamat }}">
                                            {{ $tabungan->nasabah->alamat }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card card-modern">
                    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                        <div class="icon-shape bg-emerald-lt me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                        </div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-4">Rincian Sampah & Waktu</h3>
                    </div>
                    <div class="card-body p-4">

                        <div class="row g-4 mb-4">
                            <div class="col-sm-6">
                                <div class="text-slate-400 fw-semibold text-uppercase small mb-2" style="letter-spacing: 0.5px;">Jenis Sampah Disetor</div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-blue-lt badge-modern rounded-pill px-3 py-2 fs-6">
                                        {{ $tabungan->jenisSampah->nama ?? 'Tidak Diketahui' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-slate-400 fw-semibold text-uppercase small mb-2" style="letter-spacing: 0.5px;">Kategori Sampah</div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-dark fs-5">{{ ucfirst($tabungan->jenisSampah->kategori ?? '-') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 border rounded-4 p-4">
                            <div class="row align-items-center g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded-circle shadow-sm border me-3 flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple m-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="5" width="16" height="16" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M4 11h16"/><path d="M11 15h1"/><path d="M12 15v3"/></svg>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $tabungan->tanggal_setor->format('l, d F Y') }}</div>
                                            <div class="text-slate-500 small mt-1">Pukul {{ $tabungan->tanggal_setor->format('H:i:s') }} WIB</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-end border-sm-start ps-sm-4 border-slate-200">
                                    <div class="text-slate-400 small fw-medium mb-1">Diproses Oleh Petugas:</div>
                                    <div class="d-flex align-items-center justify-content-sm-end gap-2">
                                        <div class="avatar avatar-sm rounded-circle bg-slate-200 text-slate-600 fw-bold border shadow-sm" style="font-size: 0.7rem; width: 24px; height: 24px;">
                                            {{ strtoupper(substr($tabungan->admin->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <span class="fw-bold text-dark">{{ $tabungan->admin->name ?? 'Admin' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($tabungan->catatan)
                        <div class="mt-4 p-3 bg-amber-lt border border-amber rounded-3 d-flex align-items-start" style="background-color: #fffbeb; border-color: #fde68a !important;">
                            <div class="text-amber me-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Catatan Tambahan</h6>
                                <p class="text-slate-600 small mb-0">{{ $tabungan->catatan }}</p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

            </div>

            <div class="col-lg-5 col-xl-4">

                <div class="card card-modern border-0 mb-4" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                    <div class="card-body p-4 text-center position-relative overflow-hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="position-absolute top-0 end-0 opacity-10 mt-n3 me-n3" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/></svg>

                        <div class="text-white text-uppercase fw-bold small mb-2" style="letter-spacing: 1px; opacity: 0.9;">Total Nilai Setoran</div>
                        <div class="display-5 fw-bold text-white mb-0" style="text-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            Rp {{ number_format($tabungan->nilai_rupiah, 0, ',', '.') }}
                        </div>
                        <div class="mt-3">
                            <span class="badge bg-white text-emerald px-3 py-1 rounded-pill shadow-sm fw-bold">Saldo Bertambah</span>
                        </div>
                    </div>
                </div>

                <div class="card card-modern mb-4 position-relative">
                    <div class="card-header bg-white border-bottom p-4">
                        <h3 class="card-title fw-bold text-dark m-0 fs-5 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><line x1="8" x2="16" y1="6" y2="6"/><line x1="16" x2="16" y1="14" y2="18"/><path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/><path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/></svg>
                            Rincian Kalkulasi
                        </h3>
                    </div>
                    <div class="card-body p-4 bg-slate-50">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-slate-500 fw-medium">Berat Disetor</div>
                            <div class="fw-bold text-dark fs-5">{{ number_format($tabungan->berat_kg, 2) }} kg</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-4 border-dashed">
                            <div class="text-slate-500 fw-medium">Harga Per Kg <span class="small text-slate-400 fw-normal">(Saat transaksi)</span></div>
                            <div class="fw-bold text-blue-modern fs-5">Rp {{ number_format($tabungan->harga_per_kg_saat_itu) }}</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <div class="fw-bold text-dark fs-5 text-uppercase" style="letter-spacing: 0.5px;">Total Bayar</div>
                            <div class="fw-bold text-emerald fs-3">Rp {{ number_format($tabungan->nilai_rupiah, 0, ',', '.') }}</div>
                        </div>

                    </div>
                </div>

                <div class="d-grid gap-3">
                    <a href="{{ route('admin.tabungan.pdf', $tabungan->id) }}" target="_blank" class="btn btn-outline-primary border-2 shadow-sm rounded-pill fw-bold py-2 d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Unduh Struk Setoran (PDF)
                    </a>

                    <form action="{{ route('admin.tabungan.destroy', $tabungan->id) }}" method="POST" onsubmit="return confirmDelete(event, '{{ $tabungan->tanggal_setor->format('d M Y') }}', '{{ addslashes($tabungan->nasabah->nama_lengkap ?? '') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light text-rose border border-slate-200 shadow-sm rounded-pill fw-bold py-2 w-100 d-flex align-items-center justify-content-center hover-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-rose" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            Batalkan & Hapus Data
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(event, tanggal, nama) {
        event.preventDefault();

        Swal.fire({
            title: 'Batalkan Setoran?',
            html: `Apakah Anda yakin ingin menghapus data setoran atas nama <strong class="text-dark">${nama}</strong> tanggal <strong class="text-dark">${tanggal}</strong>?<br><small class="text-muted mt-2 d-block">Perhatian: Saldo nasabah akan otomatis berkurang sesuai nominal transaksi ini.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus Data!',
            cancelButtonText: 'Kembali',
            reverseButtons: true,
            customClass: {
                confirmButton: 'rounded-pill fw-bold px-4',
                cancelButton: 'rounded-pill fw-bold px-4',
                popup: 'rounded-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = event.target.closest('form');

                // Tampilkan loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(form.action, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    // Kalau response redirect (bukan JSON), handle redirect
                    if (response.redirected || response.url !== form.action) {
                        window.location.href = response.url || "{{ route('admin.tabungan.index') }}";
                        return null;
                    }

                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Gagal menghapus data');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return; // Abort kalau redirect

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#10b981',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'rounded-pill fw-bold px-4',
                                popup: 'rounded-4'
                            }
                        }).then(() => {
                            window.location.href = "{{ route('admin.tabungan.index') }}";
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menghapus data',
                        confirmButtonColor: '#f43f5e',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'rounded-pill fw-bold px-4',
                            popup: 'rounded-4'
                        }
                    });
                });
            }
        });

        return false;
    }
</script>
<style>
    .hover-danger:hover {
        background-color: #fff1f2 !important;
        border-color: #fecdd3 !important;
    }
</style>
@endpush