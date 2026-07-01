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
    .form-text-hint { font-size: 0.8rem; color: #64748b; }

    .upload-box {
        border: 2px dashed #cbd5e1; border-radius: 14px; padding: 1.25rem; text-align: center;
        background: #f8fafc; transition: all 0.2s ease; cursor: pointer;
    }
    .upload-box.dragover { border-color: #10b981; background: rgba(16,185,129,0.06); }
    .upload-box.has-error { border-color: #ef4444; background: rgba(239,68,68,0.04); }
    .upload-preview-wrap { position: relative; display: inline-block; }
    .upload-preview-wrap img { max-height: 140px; border-radius: 10px; object-fit: cover; display: block; }
    .upload-remove-btn {
        position: absolute; top: -8px; right: -8px; width: 26px; height: 26px; border-radius: 50%;
        background: #ef4444; color: #fff; border: 2px solid #fff; display: flex; align-items: center;
        justify-content: center; cursor: pointer; font-size: 14px; line-height: 1; box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .file-meta { font-size: 0.8rem; color: #475569; margin-top: 6px; word-break: break-all; }

    .total-nilai-card {
        border-radius: 14px; padding: 1rem 1.25rem; display: flex; align-items: center;
        justify-content: space-between; gap: 1rem; flex-wrap: wrap;
    }

    .btn-submit-loading .btn-text { display: none; }
    .btn-submit-loading .btn-spinner { display: inline-flex !important; }
    .btn-spinner { display: none; }

    @media (max-width: 575.98px) {
        .card-body.p-4 { padding: 1.25rem !important; }
        .d-flex.justify-content-end.gap-2 { flex-direction: column-reverse; }
        .d-flex.justify-content-end.gap-2 a, .d-flex.justify-content-end.gap-2 button { width: 100%; text-align: center; }
    }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card card-modern">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.stok-sampah.index') }}" class="btn btn-light btn-icon shadow-sm rounded-3" aria-label="Kembali ke daftar stok sampah">
                        <x-icon name="arrow-left" size="20" />
                    </a>
                    <div>
                        <h3 class="card-title fw-bold text-dark mb-0">Tambah Stok Sampah</h3>
                        <p class="text-muted mb-0 small">Pencatatan stok sampah yang siap dijual ke pengepul</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.stok-sampah.store') }}" enctype="multipart/form-data" id="formStokSampah" novalidate>
                    @csrf

                    <div class="row g-4">
                        {{-- JENIS SAMPAH --}}
                        <div class="col-md-6">
                            <label for="jenis_sampah_id" class="form-label text-dark fw-bold small required">Jenis Sampah</label>
                            <select name="jenis_sampah_id" id="jenis_sampah_id" class="form-select shadow-sm @error('jenis_sampah_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisSampahList as $js)
                                    <option value="{{ $js->id }}" data-harga="{{ $js->harga_per_kg }}" {{ old('jenis_sampah_id') == $js->id ? 'selected' : '' }}>
                                        {{ $js->nama }} ({{ $js->kategori }}) — Rp {{ number_format($js->harga_per_kg, 0, ',', '.') }}/kg
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text-hint mt-1" id="hargaAcuanText"></div>
                            @error('jenis_sampah_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TANGGAL MASUK --}}
                        <div class="col-md-6">
                            <label for="tanggal_masuk" class="form-label text-dark fw-bold small required">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control shadow-sm @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('tanggal_masuk')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BERAT MASUK --}}
                        <div class="col-md-6">
                            <label for="stok_masuk_kg" class="form-label text-dark fw-bold small required">Berat Masuk (kg)</label>
                            <div class="input-group shadow-sm">
                                <input type="number" name="stok_masuk_kg" id="stok_masuk_kg" class="form-control fw-bold text-emerald @error('stok_masuk_kg') is-invalid @enderror" value="{{ old('stok_masuk_kg') }}" placeholder="0.00" step="0.01" min="0.01" inputmode="decimal" required>
                                <span class="input-group-text bg-white fw-semibold">kg</span>
                            </div>
                            @error('stok_masuk_kg')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- HARGA JUAL (dengan format ribuan) --}}
                        <div class="col-md-6">
                            <label for="harga_display" class="form-label text-dark fw-bold small required">Harga Jual per Kg</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white fw-bold">Rp</span>
                                <input type="text" id="harga_display" class="form-control fw-bold text-emerald @error('harga_jual_per_kg') is-invalid @enderror" placeholder="0" inputmode="numeric" autocomplete="off" required>
                                <span class="input-group-text bg-white fw-semibold">/kg</span>
                            </div>
                            <input type="hidden" name="harga_jual_per_kg" id="harga_jual_per_kg" value="{{ old('harga_jual_per_kg') }}">
                            @error('harga_jual_per_kg')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ESTIMASI TOTAL NILAI --}}
                        <div class="col-12">
                            <div class="total-nilai-card bg-emerald-lt">
                                <span class="fw-semibold small">Estimasi Nilai Stok (Berat × Harga Jual)</span>
                                <span class="fw-bold fs-5" id="totalNilaiText">Rp 0</span>
                            </div>
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
                            <label for="keterangan" class="form-label text-dark fw-bold small">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control shadow-sm" rows="3" maxlength="500" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                            <div class="form-text-hint mt-1 text-end"><span id="keteranganCount">0</span>/500</div>
                        </div>

                        {{-- FOTO --}}
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-bold small">Foto Sampah</label>
                            <div class="upload-box" id="uploadBox">
                                <div id="previewContainer" class="mb-2" style="display:none;">
                                    <div class="upload-preview-wrap">
                                        <img id="previewImage" src="" alt="Preview foto sampah">
                                        <button type="button" class="upload-remove-btn" id="removeImageBtn" aria-label="Hapus foto yang dipilih">&times;</button>
                                    </div>
                                    <div class="file-meta" id="fileMeta"></div>
                                </div>
                                <div id="uploadPlaceholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-300 mb-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    <p class="text-muted small mb-0">Klik atau seret foto ke sini</p>
                                </div>
                                <input type="file" name="gambar" id="gambar" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp" onchange="handleFileSelect(this.files)">
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2 rounded-pill" id="pickFileBtn">
                                    <x-icon name="upload" size="14" class="me-1" />Pilih File
                                </button>
                                <p class="text-muted small mt-1 mb-0">JPG, PNG, atau WEBP, maks 2MB</p>
                            </div>
                            <div class="text-danger small mt-1" id="fileErrorText" style="display:none;"></div>
                            @error('gambar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                        <a href="{{ route('admin.stok-sampah.index') }}" class="btn btn-light border fw-semibold shadow-sm px-4">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4" id="submitBtn">
                            <span class="btn-text"><x-icon name="save" size="16" class="me-1" />Simpan Stok</span>
                            <span class="btn-spinner align-items-center gap-2">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Menyimpan...
                            </span>
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
(function () {
    /* ================= Utilitas Format Rupiah ================= */
    const formatRibuan = (angka) => {
        const bersih = String(angka).replace(/\D/g, '');
        if (!bersih) return '';
        return bersih.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };
    const angkaMentah = (str) => String(str).replace(/\D/g, '');

    const hargaDisplay = document.getElementById('harga_display');
    const hargaHidden = document.getElementById('harga_jual_per_kg');
    const beratInput = document.getElementById('stok_masuk_kg');
    const totalNilaiText = document.getElementById('totalNilaiText');
    const jenisSampahSelect = document.getElementById('jenis_sampah_id');
    const hargaAcuanText = document.getElementById('hargaAcuanText');

    // Inisialisasi nilai awal (mis. setelah validation error / old input)
    if (hargaHidden.value) {
        hargaDisplay.value = formatRibuan(hargaHidden.value);
    }

    const updateTotal = () => {
        const berat = parseFloat(beratInput.value) || 0;
        const harga = parseInt(angkaMentah(hargaDisplay.value), 10) || 0;
        const total = berat * harga;
        totalNilaiText.textContent = 'Rp ' + total.toLocaleString('id-ID', { maximumFractionDigits: 0 });
    };

    hargaDisplay.addEventListener('input', () => {
        const raw = angkaMentah(hargaDisplay.value);
        hargaDisplay.value = formatRibuan(raw);
        hargaHidden.value = raw;
        hargaDisplay.setCustomValidity('');
        updateTotal();
    });

    beratInput.addEventListener('input', updateTotal);
    updateTotal();

    // Saran harga otomatis dari jenis sampah yang dipilih (hanya jika field harga masih kosong)
    jenisSampahSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        const hargaAcuan = opt ? opt.getAttribute('data-harga') : null;

        if (hargaAcuan) {
            hargaAcuanText.textContent = 'Harga acuan jenis ini: Rp ' + formatRibuan(hargaAcuan) + '/kg';
            if (!hargaDisplay.value) {
                hargaDisplay.value = formatRibuan(hargaAcuan);
                hargaHidden.value = angkaMentah(hargaAcuan);
                updateTotal();
            }
        } else {
            hargaAcuanText.textContent = '';
        }
    });
    if (jenisSampahSelect.value) {
        jenisSampahSelect.dispatchEvent(new Event('change'));
    }

    /* ================= Cegah angka berubah saat scroll ================= */
    [beratInput].forEach((el) => {
        el.addEventListener('wheel', () => el.blur(), { passive: true });
    });

    /* ================= Hitung karakter Keterangan ================= */
    const keterangan = document.getElementById('keterangan');
    const keteranganCount = document.getElementById('keteranganCount');
    const updateCount = () => { keteranganCount.textContent = keterangan.value.length; };
    keterangan.addEventListener('input', updateCount);
    updateCount();

    /* ================= Upload Foto: validasi, preview, drag&drop, hapus ================= */
    const MAX_SIZE = 2 * 1024 * 1024; // 2MB
    const ALLOWED_TYPES = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];

    const uploadBox = document.getElementById('uploadBox');
    const fileInput = document.getElementById('gambar');
    const pickFileBtn = document.getElementById('pickFileBtn');
    const previewContainer = document.getElementById('previewContainer');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const previewImage = document.getElementById('previewImage');
    const fileMeta = document.getElementById('fileMeta');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const fileErrorText = document.getElementById('fileErrorText');

    const formatBytes = (bytes) => {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    };

    const showFileError = (msg) => {
        fileErrorText.textContent = msg;
        fileErrorText.style.display = 'block';
        uploadBox.classList.add('has-error');
    };
    const clearFileError = () => {
        fileErrorText.textContent = '';
        fileErrorText.style.display = 'none';
        uploadBox.classList.remove('has-error');
    };

    window.handleFileSelect = function (files) {
        clearFileError();
        if (!files || !files[0]) return;
        const file = files[0];

        if (!ALLOWED_TYPES.includes(file.type)) {
            showFileError('Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
            fileInput.value = '';
            return;
        }
        if (file.size > MAX_SIZE) {
            showFileError('Ukuran file terlalu besar (maks 2MB). File Anda: ' + formatBytes(file.size));
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
            fileMeta.textContent = file.name + ' — ' + formatBytes(file.size);
        };
        reader.readAsDataURL(file);
    };

    pickFileBtn.addEventListener('click', () => fileInput.click());

    removeImageBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.value = '';
        previewContainer.style.display = 'none';
        uploadPlaceholder.style.display = 'block';
        clearFileError();
    });

    ['dragenter', 'dragover'].forEach((evt) => {
        uploadBox.addEventListener(evt, (e) => {
            e.preventDefault(); e.stopPropagation();
            uploadBox.classList.add('dragover');
        });
    });
    ['dragleave', 'drop'].forEach((evt) => {
        uploadBox.addEventListener(evt, (e) => {
            e.preventDefault(); e.stopPropagation();
            uploadBox.classList.remove('dragover');
        });
    });
    uploadBox.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        if (dt && dt.files && dt.files.length) {
            fileInput.files = dt.files;
            handleFileSelect(dt.files);
        }
    });

    /* ================= Validasi & Loading State saat Submit ================= */
    const form = document.getElementById('formStokSampah');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function (e) {
        let valid = true;

        if (!angkaMentah(hargaDisplay.value)) {
            hargaDisplay.setCustomValidity('Harga jual wajib diisi.');
            valid = false;
        } else {
            hargaDisplay.setCustomValidity('');
        }

        if (!form.checkValidity() || !valid) {
            e.preventDefault();
            form.reportValidity();
            return;
        }

        submitBtn.disabled = true;
        submitBtn.classList.add('btn-submit-loading');
    });
})();
</script>
@endpush
