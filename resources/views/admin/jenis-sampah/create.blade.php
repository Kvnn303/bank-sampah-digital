@extends('layouts.admin')

@section('title', 'Tambah Jenis Sampah')
@section('page-title', 'Tambah Jenis Sampah')

@push('styles')
<style>
    /* Styling Modern untuk Form */
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

    .section-title {
        color: #0f172a;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 20px;
    }

    .icon-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    /* Warna Kustom Modern */
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-9 col-xl-8">

        <!-- Card Form Modern -->
        <div class="card card-modern">

            <!-- Header -->
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-blue-lt rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-3">Tambah Jenis Sampah</h3>
                        <p class="text-slate-500 small m-0 mt-1">Lengkapi data untuk menambah kategori sampah baru.</p>
                    </div>
                </div>
                <div class="card-options">
                    <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 d-none d-sm-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Body Form -->
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="{{ route('admin.jenis-sampah.store') }}">
                    @csrf

                    <div class="mb-2">
                        <h4 class="section-title">
                            <div class="icon-wrapper bg-emerald-lt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-emerald" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            </div>
                            Detail Jenis Sampah
                        </h4>

                        <div class="row g-4">
                            <!-- Nama Sampah -->
                            <div class="col-md-6">
                                <label class="form-label required">Nama Jenis Sampah</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v16"/><path d="M4 12h16"/></svg>
                                    </span>
                                    <input type="text"
                                           name="nama"
                                           class="form-control @error('nama') is-invalid @enderror"
                                           placeholder="Contoh: Botol Plastik Bening"
                                           value="{{ old('nama') }}"
                                           autofocus required>
                                </div>
                                @error('nama')
                                    <div class="invalid-feedback d-block fw-medium mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-6">
                                <label class="form-label required">Kategori Sampah</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                                    </span>
                                    <select name="kategori" class="form-select ps-5 @error('kategori') is-invalid @enderror" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach(['Plastik', 'Kertas', 'Logam', 'Kaca', 'Elektronik', 'Organik', 'Lainnya'] as $kat)
                                            <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>
                                                {{ $kat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('kategori')
                                    <div class="invalid-feedback d-block fw-medium mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Harga Per Kg -->
                            <div class="col-md-12">
                                <label class="form-label required">Harga Beli Per Kilogram</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-slate-50 text-slate-600 fw-bold border-end-0">Rp</span>
                                    <input type="number"
                                           name="harga_per_kg"
                                           class="form-control text-emerald fw-bold border-start-0 border-end-0 px-2 @error('harga_per_kg') is-invalid @enderror"
                                           placeholder="0"
                                           min="0"
                                           step="100"
                                           value="{{ old('harga_per_kg') }}" required>
                                    <span class="input-group-text bg-slate-50 text-slate-500 border-start-0">/ kg</span>
                                </div>
                                <small class="text-slate-500 mt-2 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                    Harga ini yang akan menjadi acuan perhitungan saat nasabah menyetor sampah.
                                </small>
                                @error('harga_per_kg')
                                    <div class="invalid-feedback d-block fw-medium mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div class="col-12">
                                <label class="form-label">Keterangan Khusus <span class="text-slate-400 fw-normal">(Opsional)</span></label>
                                <textarea name="keterangan"
                                          class="form-control @error('keterangan') is-invalid @enderror"
                                          rows="3"
                                          style="resize: none;"
                                          placeholder="Contoh: Harus dalam keadaan bersih, kering, dan dipisahkan dari tutup botol.">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback d-block fw-medium mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-3 flex-wrap">
                        <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 d-sm-none">
                            Batal
                        </a>
                        <a href="{{ route('admin.jenis-sampah.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 d-none d-sm-block">
                            Batal
                        </a>
                        <button type="submit" id="btnSimpan" class="btn btn-primary shadow-sm rounded-pill fw-bold px-4 d-flex align-items-center w-100 w-sm-auto justify-content-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" id="iconSimpan" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span id="textSimpan">Simpan Data</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Visual feedback saat submit form
document.querySelector('form').addEventListener('submit', function(e) {
    const btn = document.getElementById('btnSimpan');
    const icon = document.getElementById('iconSimpan');
    const text = document.getElementById('textSimpan');

    if (btn && icon && text) {
        btn.disabled = true;
        btn.classList.add('btn-success');
        btn.classList.remove('btn-primary');

        // Ganti ikon dengan checkmark
        icon.innerHTML = '<circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/>';
        text.textContent = 'Menyimpan...';

        // Setelah 1 detik tampilkan "Selesai"
        setTimeout(() => {
            text.textContent = 'Selesai!';
        }, 1000);
    }
});
</script>
@endpush
