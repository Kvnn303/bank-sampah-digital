@extends('layouts.admin')

@section('title', 'Tambah Stok Sampah')
@section('page-title', 'Tambah Stok Sampah')

@push('styles')
<style>
    .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .form-control:focus, .form-select:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
    .required::after { content: ' *'; color: #ef4444; }
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background: rgba(16,185,129,0.1) !important; color: #10b981 !important; }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.stok-sampah.index') }}" class="btn btn-light btn-icon shadow-sm rounded-3">
                        <x-icon name="arrow-left" size="20" />
                    </a>
                    <div>
                        <h3 class="card-title fw-bold text-dark mb-0">Tambah Stok Sampah</h3>
                        <p class="text-muted mb-0 small">Pencatatan stok sampah yang siap dijual ke pengepul</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.stok-sampah.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        {{-- JENIS SAMPAH --}}
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small required">Jenis Sampah</label>
                            <select name="jenis_sampah_id" class="form-select shadow-sm @error('jenis_sampah_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisSampahList as $js)
                                    <option value="{{ $js->id }}" {{ old('jenis_sampah_id') == $js->id ? 'selected' : '' }}>
                                        {{ $js->nama }} ({{ $js->kategori }}) — Rp {{ number_format($js->harga_per_kg, 0, ',', '.') }}/kg
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_sampah_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TANGGAL MASUK --}}
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small required">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control shadow-sm @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BERAT MASUK --}}
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small required">Berat Masuk (kg)</label>
                            <div class="input-group shadow-sm">
                                <input type="number" name="stok_masuk_kg" class="form-control fw-bold text-emerald @error('stok_masuk_kg') is-invalid @enderror" value="{{ old('stok_masuk_kg') }}" placeholder="0.00" step="0.01" min="0.01" required>
                                <span class="input-group-text bg-white fw-semibold">kg</span>
                            </div>
                            @error('stok_masuk_kg')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- HARGA JUAL --}}
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small required">Harga Jual per Kg</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white fw-bold">Rp</span>
                                <input type="number" name="harga_jual_per_kg" class="form-control fw-bold text-emerald @error('harga_jual_per_kg') is-invalid @enderror" value="{{ old('harga_jual_per_kg') }}" placeholder="0" min="0" required>
                                <span class="input-group-text bg-white fw-semibold">/kg</span>
                            </div>
                            @error('harga_jual_per_kg')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- IS PRESS --}}
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small">Status Press</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_pres" id="is_pres" value="1" {{ old('is_pres') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="is_pres">Centang jika sampah sudah di-press / dikompres</label>
                            </div>
                        </div>

                        {{-- IS PUBLISHED --}}
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small">Publikasi</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="is_published">Publikasikan ke pihak ketiga (pengepul)</label>
                            </div>
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="col-md-8">
                            <label class="form-label text-dark fw-bold small">Keterangan</label>
                            <textarea name="keterangan" class="form-control shadow-sm" rows="3" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>

                        {{-- FOTO --}}
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-bold small">Foto Sampah</label>
                            <div class="border rounded-3 p-3 text-center" style="background: #f8fafc;">
                                <div id="previewContainer" class="mb-2" style="{{ old('gambar') ? '' : 'display:none;' }}">
                                    <img id="previewImage" src="" alt="Preview" class="rounded-3" style="max-height: 120px; object-fit: cover;">
                                </div>
                                <div id="uploadPlaceholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-300 mb-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    <p class="text-muted small mb-0">Klik untuk upload foto</p>
                                </div>
                                <input type="file" name="gambar" id="gambar" class="d-none" accept="image/*" onchange="previewImage(this)">
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2 rounded-pill" onclick="document.getElementById('gambar').click()">
                                    <x-icon name="upload" size="14" class="me-1" />Pilih File
                                </button>
                                <p class="text-muted small mt-1 mb-0">JPG, PNG, maks 2MB</p>
                            </div>
                            @error('gambar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.stok-sampah.index') }}" class="btn btn-light border fw-semibold shadow-sm px-4">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4">
                            <x-icon name="save" size="16" class="me-1" />Simpan Stok
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
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('previewContainer').style.display = 'block';
            document.getElementById('uploadPlaceholder').style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
