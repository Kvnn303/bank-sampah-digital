@extends('layouts.admin')

@section('title', 'Edit Jenis Sampah')
@section('page-title', 'Edit Jenis Sampah')

@push('styles')
<style>
    /* Styling Modern yang Seragam */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.85rem;
        letter-spacing: 0.3px;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select, .input-group-text {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        z-index: 1;
    }

    .input-icon-addon {
        color: #94a3b8;
    }

    .icon-shape {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
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

    /* Switch Modern */
    .form-switch .form-check-input {
        height: 1.5rem;
        width: 3rem;
        cursor: pointer;
    }
    .form-switch .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-xl-11">

        <!-- Header -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark fs-2">Edit Jenis Sampah</h2>
                <p class="text-slate-500 mb-0">Perbarui detail kategori atau kelola harga untuk item <strong class="text-dark">{{ $sampah->nama }}</strong>.</p>
            </div>
            <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
        </div>

        <div class="row g-4">

            <!-- Kolom Kiri: Info Dasar -->
            <div class="col-lg-6">
                <div class="card card-modern h-100">
                    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                        <div class="icon-shape bg-blue-lt me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-4">Informasi Dasar</h3>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('admin.jenis-sampah.update', $sampah->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- Nama -->
                            <div class="mb-4">
                                <label class="form-label required">Nama Sampah</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                    </span>
                                    <input type="text"
                                           name="nama"
                                           class="form-control fw-semibold text-dark @error('nama') is-invalid @enderror"
                                           value="{{ old('nama', $sampah->nama) }}" required>
                                </div>
                                @error('nama')
                                    <div class="invalid-feedback d-block fw-medium mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="mb-4">
                                <label class="form-label">Kategori</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                                    </span>
                                    <select name="kategori" class="form-select ps-5">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach(['Plastik', 'Kertas', 'Logam', 'Kaca', 'Elektronik', 'Organik', 'Lainnya'] as $kat)
                                            <option value="{{ $kat }}" {{ old('kategori', $sampah->kategori) == $kat ? 'selected' : '' }}>
                                                {{ $kat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div class="mb-4">
                                <label class="form-label">Keterangan <span class="text-slate-400 fw-normal">(Opsional)</span></label>
                                <textarea name="keterangan"
                                          class="form-control"
                                          rows="3"
                                          style="resize: none;"
                                          placeholder="Deskripsi singkat mengenai jenis sampah ini...">{{ old('keterangan', $sampah->keterangan) }}</textarea>
                            </div>

                            <!-- Status -->
                            <div class="mb-5 p-3 border rounded-3 bg-slate-50 d-flex align-items-center justify-content-between">
                                <div>
                                    <label class="form-label mb-1">Status Ketersediaan</label>
                                    <div class="text-slate-500 small" id="statusLabelText">
                                        {{ $sampah->is_active ? 'Diterima di Bank Sampah' : 'Saat ini tidak diterima' }}
                                    </div>
                                </div>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox"
                                           name="is_active"
                                           id="switchStatus"
                                           {{ $sampah->is_active ? 'checked' : '' }}>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-pill fw-bold d-flex justify-content-center align-items-center py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Simpan Informasi Dasar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Manajemen Harga -->
            <div class="col-lg-6">
                <div class="card card-modern h-100">
                    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                        <div class="icon-shape bg-emerald-lt me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-4">Manajemen Harga</h3>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <!-- Harga Saat Ini -->
                        <div class="p-4 bg-emerald-lt border border-emerald rounded-4 mb-4 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-emerald fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Harga Aktif Saat Ini</div>
                                <div class="h1 fw-bold text-emerald mb-0 fs-1">
                                    Rp {{ number_format($sampah->harga_per_kg) }} <span class="fs-5 fw-medium text-emerald" style="opacity: 0.8;">/ kg</span>
                                </div>
                            </div>
                            <div class="d-none d-sm-block bg-white p-2 rounded-circle shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald m-0" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.jenis-sampah.harga', $sampah->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label required">Perbarui Harga Baru (Rp/kg)</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white fw-bold">Rp</span>
                                    <input type="number"
                                           name="harga_per_kg"
                                           class="form-control fw-bold text-emerald @error('harga_per_kg') is-invalid @enderror"
                                           placeholder="0"
                                           min="0"
                                           step="100"
                                           required>
                                    <span class="input-group-text bg-slate-50 text-slate-500">/kg</span>
                                </div>
                                @error('harga_per_kg')
                                    <div class="invalid-feedback d-block fw-medium mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">Alasan Perubahan</label>
                                <textarea name="alasan"
                                          class="form-control @error('alasan') is-invalid @enderror"
                                          rows="2"
                                          style="resize: none;"
                                          placeholder="Contoh: Menyesuaikan harga pasar pengepul terbaru" required></textarea>
                                <small class="text-slate-500 mt-2 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                    Wajib diisi untuk log audit sistem.
                                </small>
                                @error('alasan')
                                    <div class="invalid-feedback d-block fw-medium mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-dark w-100 shadow-sm rounded-pill fw-bold py-2">
                                Update Harga Sekarang
                            </button>
                        </form>

                        <!-- Riwayat Singkat -->
                        <div class="mt-5 pt-4 border-top">
                            <h4 class="fw-bold text-dark fs-6 mb-3 d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Riwayat Perubahan Terakhir
                            </h4>

                            @if($riwayat->count() > 0)
                                <div class="list-group list-group-flush border-0">
                                    @foreach($riwayat->take(3) as $r)
                                    <div class="list-group-item px-0 py-2 border-0 mb-2 bg-transparent">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="text-slate-400 text-decoration-line-through small fw-medium">Rp {{ number_format($r->harga_lama) }}</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                                    <span class="text-emerald fw-bold">Rp {{ number_format($r->harga_baru) }}</span>
                                                </div>
                                                <span class="text-slate-500 small d-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                    {{ $r->diubahOleh->name ?? '-' }}
                                                </span>
                                            </div>
                                            <div class="text-slate-400 small fw-medium text-end">
                                                {{ $r->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-2 text-end">
                                    <a href="#riwayat-lengkap" class="text-blue-modern fw-semibold small text-decoration-none">
                                        Lihat tabel lengkap &rarr;
                                    </a>
                                </div>
                            @else
                                <div class="p-3 bg-slate-50 border rounded-3 text-center text-slate-500 small">
                                    Belum ada riwayat perubahan harga untuk jenis sampah ini.
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- Riwayat Lengkap (Tabel) -->
        <div class="card card-modern mt-4" id="riwayat-lengkap">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center">
                <div class="icon-shape bg-purple-lt me-3" style="width: 42px; height: 42px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-purple" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l3 3"/></svg>
                </div>
                <div>
                    <h3 class="card-title fw-bold text-dark m-0 fs-4">Log Riwayat Harga</h3>
                    <p class="text-slate-500 small m-0 mt-1">Catatan lengkap historis perubahan harga jenis sampah ini.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-modern mb-0 align-middle table-nowrap">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="ps-4">Tanggal & Waktu</th>
                            <th>Harga Lama</th>
                            <th>Harga Baru</th>
                            <th>Selisih</th>
                            <th>Alasan Perubahan</th>
                            <th class="pe-4">Diubah Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $r)
                        <tr>
                            <td class="ps-4 fw-medium text-dark">{{ $r->created_at->format('d M Y, H:i') }}</td>
                            <td class="text-slate-500">Rp {{ number_format($r->harga_lama) }}</td>
                            <td class="text-emerald fw-bold">Rp {{ number_format($r->harga_baru) }}</td>
                            <td>
                                @php $selisih = $r->harga_baru - $r->harga_lama; @endphp
                                @if($selisih > 0)
                                    <span class="badge bg-emerald-lt badge-modern rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m18 15-6-6-6 6"/></svg>
                                        Rp {{ number_format($selisih) }}
                                    </span>
                                @elseif($selisih < 0)
                                    <span class="badge bg-rose-lt badge-modern rounded-pill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="m6 9 6 6 6-6"/></svg>
                                        Rp {{ number_format(abs($selisih)) }}
                                    </span>
                                @else
                                    <span class="badge bg-slate-100 text-slate-600 badge-modern rounded-pill border">Tetap</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-slate-600 small d-block text-truncate" style="max-width: 250px;" title="{{ $r->alasan }}">
                                    {{ $r->alasan ?? '-' }}
                                </span>
                            </td>
                            <td class="pe-4 text-slate-600 small fw-medium">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm rounded-circle bg-slate-200 text-slate-600 fw-bold border" style="font-size: 0.75rem; width: 24px; height: 24px;">
                                        {{ strtoupper(substr($r->diubahOleh->name ?? 'A', 0, 1)) }}
                                    </div>
                                    {{ $r->diubahOleh->name ?? '-' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-slate-300" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" x2="21" y1="9" y2="9"/><path d="M9 14v4"/><path d="M15 14v4"/></svg>
                                </div>
                                <h4 class="text-dark fw-bold fs-5">Belum ada riwayat harga</h4>
                                <p class="text-slate-500 small mb-0">Perubahan harga akan otomatis tercatat di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Interaktif Teks Status Toggle
const checkbox = document.getElementById('switchStatus');
const statusLabelText = document.getElementById('statusLabelText');

if(checkbox && statusLabelText) {
    checkbox.addEventListener('change', function() {
        if(this.checked) {
            statusLabelText.innerHTML = '<span class="text-emerald fw-semibold">Diterima di Bank Sampah</span>';
        } else {
            statusLabelText.innerHTML = '<span class="text-rose fw-semibold">Saat ini tidak diterima</span>';
        }
    });
}
</script>
@endpush
