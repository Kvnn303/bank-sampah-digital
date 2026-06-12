@extends('layouts.admin')

@section('title', 'Edit Stok Sampah')
@section('page-title', 'Edit Stok Sampah')

@push('styles')
<style>
    .card-modern { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .form-control:focus, .form-select:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
    .required::after { content: ' *'; color: #ef4444; }
    .text-emerald { color: #10b981 !important; }
    .bg-emerald-lt { background: rgba(16,185,129,0.1) !important; color: #10b981 !important; }
    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background: rgba(245,158,11,0.1) !important; color: #f59e0b !important; }
    .text-rose { color: #f43f5e !important; }
    .bg-rose-lt { background: rgba(244,63,94,0.1) !important; color: #f43f5e !important; }
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
                        <h3 class="card-title fw-bold text-dark mb-0">Edit Stok Sampah</h3>
                        <p class="text-muted mb-0 small">Edit stok ID #{{ $stok->id }}</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="alert bg-emerald-lt border-0 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <x-icon name="info" class="text-emerald flex-shrink-0" size="20" />
                        <div>
                            <div class="fw-bold text-emerald small">Status Saat Ini</div>
                            <div class="text-dark">Berat Masuk: <strong>{{ number_format($stok->stok_masuk_kg, 2, ',', '.') }} kg</strong> |
                            Terjual: <strong class="text-emerald">{{ number_format($stok->stok_terjual_kg, 2, ',', '.') }} kg</strong> |
                            Tersisa: <strong class="text-amber">{{ number_format($stok->stok_tersisa_kg, 2, ',', '.') }} kg</strong></div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.stok-sampah.update', $stok->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small required">Jenis Sampah</label>
                            <select name="jenis_sampah_id" class="form-select shadow-sm @error('jenis_sampah_id') is-invalid @enderror" required>
                                @foreach($jenisSampahList as $js)
                                    <option value="{{ $js->id }}" {{ (old('jenis_sampah_id', $stok->jenis_sampah_id) == $js->id) ? 'selected' : '' }}>
                                        {{ $js->nama }} ({{ $js->kategori }}) — Rp {{ number_format($js->harga_per_kg, 0, ',', '.') }}/kg
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_sampah_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small required">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control shadow-sm @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', $stok->tanggal_masuk->format('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small required">Berat Masuk (kg)</label>
                            <div class="input-group shadow-sm @error('stok_masuk_kg') is-invalid @enderror">
                                <input type="number" name="stok_masuk_kg" class="form-control fw-bold text-emerald" value="{{ old('stok_masuk_kg', $stok->stok_masuk_kg) }}" step="0.01" min="0.01" required>
                                <span class="input-group-text bg-white fw-semibold">kg</span>
                            </div>
                            @error('stok_masuk_kg')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small required">Harga Jual per Kg</label>
                            <div class="input-group shadow-sm @error('harga_jual_per_kg') is-invalid @enderror">
                                <span class="input-group-text bg-white fw-bold">Rp</span>
                                <input type="number" name="harga_jual_per_kg" class="form-control fw-bold text-emerald" value="{{ old('harga_jual_per_kg', $stok->harga_jual_per_kg) }}" min="0" required>
                                <span class="input-group-text bg-white fw-semibold">/kg</span>
                            </div>
                            @error('harga_jual_per_kg')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small">Status Press</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_pres" id="is_pres" value="1" {{ old('is_pres', $stok->is_pres) ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="is_pres">Sampah sudah di-press / dikompres</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold small">Publikasi</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $stok->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="is_published">Publikasikan ke pihak ketiga</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-dark fw-bold small">Keterangan</label>
                            <textarea name="keterangan" class="form-control shadow-sm" rows="3">{{ old('keterangan', $stok->keterangan) }}</textarea>
                        </div>

                        {{-- FOTO SECTION --}}
                        <div class="col-12">
                            <label class="form-label text-dark fw-bold small">Foto Stok</label>
                            <div class="border rounded-3 p-3" style="background: #f8fafc;">
                                @if($stok->gambar)
                                {{-- Tampilkan gambar lama --}}
                                <div class="mb-3 text-center" id="currentImageContainer">
                                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-emerald"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        <span class="text-success small fw-semibold">Foto sebelumnya</span>
                                    </div>
                                    <img src="{{ asset('storage/' . $stok->gambar) }}" alt="Foto Stok" class="rounded-3" style="max-height: 120px; object-fit: cover; border: 2px solid #e2e8f0;">
                                </div>
                                @endif

                                {{-- Preview gambar baru --}}
                                <div id="previewContainer" class="mb-2 text-center" style="{{ old('gambar') ? '' : 'display:none;' }}">
                                    <img id="previewImage" src="" alt="Preview" class="rounded-3" style="max-height: 120px; object-fit: cover; border: 2px solid #10b981;">
                                    <div class="mt-1">
                                        <span class="badge bg-emerald-lt text-emerald">Preview baru</span>
                                    </div>
                                </div>

                                {{-- Placeholder upload --}}
                                <div id="uploadPlaceholder" class="text-center py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-300 mb-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    <p class="text-muted small mb-0">{{ $stok->gambar ? 'Klik untuk ganti foto' : 'Klik untuk upload foto' }}</p>
                                    <p class="text-muted small mb-0">JPG, PNG, maks 2MB</p>
                                </div>

                                {{-- Hidden file input --}}
                                <input type="file" name="gambar" id="gambar" class="d-none" accept="image/*" onchange="previewImage(this)">

                                {{-- Button --}}
                                <div class="text-center mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="document.getElementById('gambar').click()">
                                        <x-icon name="upload" size="14" class="me-1" />
                                        {{ $stok->gambar ? 'Ganti Foto' : 'Pilih File' }}
                                    </button>
                                    @if($stok->gambar)
                                    <span class="text-muted small ms-2">— Kosongkan jika tidak ingin ganti</span>
                                    @endif
                                </div>
                            </div>
                            @error('gambar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.stok-sampah.index') }}" class="btn btn-light border fw-semibold shadow-sm px-4">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4">
                            <x-icon name="save" size="16" class="me-1" />Perbarui
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

            // Hide current image if exists
            var currentContainer = document.getElementById('currentImageContainer');
            if (currentContainer) {
                currentContainer.style.display = 'none';
            }

            // Hide placeholder
            document.getElementById('uploadPlaceholder').style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
