<div class="row row-cards">

    {{-- Kolom Kiri: Konten Utama --}}
    <div class="col-lg-8">

        {{-- Card Judul & Konten --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-file-text me-2 text-primary"></i>Konten Artikel
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Judul Artikel</label>
                    <input type="text" name="judul" id="judulInput"
                           class="form-control form-control-lg {{ $errors->has('judul') ? 'is-invalid' : '' }}"
                           value="{{ old('judul', $artikel->judul ?? '') }}"
                           placeholder="Masukkan judul artikel...">
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-0">
                    <label class="form-label required">Konten Artikel</label>
                    <textarea name="konten" id="editor"
                              class="form-control {{ $errors->has('konten') ? 'is-invalid' : '' }}"
                              rows="12">{{ old('konten', $artikel->konten ?? '') }}</textarea>
                    @error('konten')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Card Galeri Dokumentasi --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-photo me-2 text-primary"></i>Galeri Dokumentasi
                </h3>
                <div class="card-options">
                    <span class="badge bg-blue-lt text-blue">Opsional</span>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Upload foto dokumentasi kegiatan. Bisa lebih dari satu foto. Format: JPG, PNG. Maks. 2MB per foto.
                </p>

                {{-- Galeri yang sudah ada (mode edit) --}}
                @if(isset($artikel) && $artikel->galeri && $artikel->galeri->count() > 0)
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Saat Ini</label>
                        <div class="row g-2" id="existingGaleri">
                            @foreach($artikel->galeri as $galeriItem)
                            <div class="col-6 col-sm-4 col-md-3" id="galeri-item-{{ $galeriItem->id }}">
                                <div class="position-relative border rounded overflow-hidden"
                                    style="aspect-ratio: 4/3; background: #f1f1f1;">
                                    <img src="{{ Storage::url($galeriItem->gambar) }}"
                                         alt="{{ $galeriItem->keterangan ?? 'Foto' }}"
                                         class="w-100 h-100" style="object-fit: cover;">
                                    {{-- Checkbox hapus --}}
                                    <div class="position-absolute top-0 end-0 m-1">
                                        <label class="d-flex align-items-center justify-content-center bg-danger rounded"
                                               style="width: 26px; height: 26px; cursor: pointer;"
                                               data-bs-toggle="tooltip" title="Centang untuk hapus foto ini">
                                            <input type="checkbox" name="hapus_galeri[]"
                                                   value="{{ $galeriItem->id }}"
                                                   class="d-none hapus-galeri-cb"
                                                   data-target="galeri-item-{{ $galeriItem->id }}">
                                            <i class="ti ti-trash text-white" style="font-size: 0.8rem;"></i>
                                        </label>
                                    </div>
                                    @if($galeriItem->keterangan)
                                        <div class="position-absolute bottom-0 start-0 end-0 px-2 py-1"
                                            style="background: rgba(0,0,0,0.5);">
                                            <small class="text-white" style="font-size: 0.7rem;">{{ $galeriItem->keterangan }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="ti ti-info-circle me-1"></i>
                            Klik ikon hapus (merah) pada foto yang ingin dihapus, lalu simpan artikel.
                        </small>
                    </div>
                @endif

                {{-- Area Drop / Upload Galeri Baru --}}
                <div id="dropZone"
                     class="border border-dashed rounded p-4 text-center"
                     style="cursor: pointer; border-color: var(--tblr-border-color) !important; transition: background 0.2s;">
                    <i class="ti ti-cloud-upload text-muted mb-2" style="font-size: 2rem;"></i>
                    <div class="text-muted mb-2">Seret foto ke sini atau</div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('galeriInput').click()">
                        <i class="ti ti-plus me-1"></i>Pilih Foto
                    </button>
                    <input type="file" name="galeri[]" id="galeriInput"
                           accept="image/jpeg,image/png,image/jpg"
                           multiple class="d-none">
                </div>

                {{-- Preview galeri baru yang dipilih --}}
                <div id="galeriPreviewContainer" class="row g-2 mt-2" style="display: none !important;"></div>

                @error('galeri.*')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

    {{-- Kolom Kanan: Pengaturan --}}
    <div class="col-lg-4">

        {{-- Card Pengaturan --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-settings me-2 text-primary"></i>Pengaturan
                </h3>
            </div>
            <div class="card-body">

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label required">Kategori</label>
                    <select name="kategori" class="form-select {{ $errors->has('kategori') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ old('kategori', $artikel->kategori ?? '') == $kat ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($kat)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status Publikasi --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <label class="form-check form-switch">
                        <input type="checkbox" name="is_published" class="form-check-input"
                               {{ old('is_published', isset($artikel) ? $artikel->is_published : false) ? 'checked' : '' }}>
                        <span class="form-check-label fw-semibold">Publikasikan Artikel</span>
                    </label>
                    <small class="text-muted d-block mt-1">Jika tidak dicentang, artikel tersimpan sebagai draft.</small>
                </div>

            </div>
        </div>

        {{-- Card Gambar Sampul --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-photo me-2 text-primary"></i>Gambar Sampul
                </h3>
            </div>
            <div class="card-body">

                {{-- Preview gambar saat ini (mode edit) --}}
                @if(isset($artikel) && $artikel->gambar)
                    <div class="mb-3" id="currentSampulContainer">
                        <label class="form-label text-muted small">Gambar Saat Ini</label>
                        <div class="position-relative rounded overflow-hidden border"
                            style="aspect-ratio: 16/9; background: #f1f1f1;">
                            <img src="{{ Storage::url($artikel->gambar) }}"
                                 id="currentSampul"
                                 alt="Gambar Sampul"
                                 class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <small class="text-muted">Upload gambar baru untuk mengganti.</small>
                    </div>
                @endif

                {{-- Area upload sampul --}}
                <div id="sampulDropZone"
                     class="border border-dashed rounded p-3 text-center mb-2"
                     style="cursor: pointer; transition: background 0.2s;"
                     onclick="document.getElementById('gambarInput').click()">
                    <div id="sampulPlaceholder">
                        <i class="ti ti-upload text-muted" style="font-size: 1.5rem;"></i>
                        <div class="text-muted small mt-1">Klik untuk pilih gambar sampul</div>
                    </div>
                    <div id="sampulPreviewWrap" style="display: none;">
                        <img id="sampulPreview" src="#" alt="Preview Sampul"
                             class="img-fluid rounded" style="max-height: 160px; object-fit: cover;">
                        <div class="text-muted small mt-1" id="sampulFileName"></div>
                    </div>
                </div>

                <input type="file" name="gambar" id="gambarInput"
                       accept="image/jpeg,image/png,image/jpg"
                       class="d-none {{ $errors->has('gambar') ? 'is-invalid' : '' }}">
                <small class="text-muted">Format: JPG, PNG. Maks. 2MB.</small>
                @error('gambar')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

            </div>
        </div>

        {{-- Card Aksi --}}
        <div class="card">
            <div class="card-body d-flex flex-column gap-2">
                <button type="submit" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="ti ti-device-floppy"></i>
                    Simpan Artikel
                </button>
                <a href="{{ route('admin.artikels.index') }}"
                   class="btn btn-outline-secondary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="ti ti-x"></i>
                    Batal
                </a>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {


    // TinyMCE
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#editor',
            height: 420,
            menubar: false,
            language: 'id',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code fullscreen',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; font-size: 15px; line-height: 1.7; }',
        });
    }

    // Preview Gambar Sampul
    const gambarInput   = document.getElementById('gambarInput');
    const sampulPreview = document.getElementById('sampulPreview');
    const sampulPreviewWrap = document.getElementById('sampulPreviewWrap');
    const sampulPlaceholder = document.getElementById('sampulPlaceholder');
    const sampulFileName    = document.getElementById('sampulFileName');

    gambarInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            sampulPreview.src = e.target.result;
            sampulPreviewWrap.style.display = 'block';
            sampulPlaceholder.style.display = 'none';
            sampulFileName.textContent = file.name;
        };
        reader.readAsDataURL(file);
    });

    // Drag over sampul drop zone
    const sampulDropZone = document.getElementById('sampulDropZone');
    sampulDropZone.addEventListener('dragover', e => { e.preventDefault(); sampulDropZone.style.background = 'var(--tblr-primary-lt)'; });
    sampulDropZone.addEventListener('dragleave', () => { sampulDropZone.style.background = ''; });
    sampulDropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        sampulDropZone.style.background = '';
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            gambarInput.files = dt.files;
            gambarInput.dispatchEvent(new Event('change'));
        }
    });

    // Galeri Multi Upload
    const galeriInput     = document.getElementById('galeriInput');
    const galeriContainer = document.getElementById('galeriPreviewContainer');
    const dropZone        = document.getElementById('dropZone');

    let selectedFiles = []; // array of File objects

    function renderGaleriPreview() {
        galeriContainer.innerHTML = '';
        if (selectedFiles.length === 0) {
            galeriContainer.style.setProperty('display', 'none', 'important');
            return;
        }
        galeriContainer.style.removeProperty('display');

        selectedFiles.forEach(function (file, index) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const col = document.createElement('div');
                col.className = 'col-6 col-sm-4 col-md-3';
                col.innerHTML = `
                    <div class="border rounded overflow-hidden position-relative" style="aspect-ratio: 4/3; background: #f1f1f1;">
                        <img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;" alt="Preview">
                        <button type="button"
                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 p-0 d-flex align-items-center justify-content-center"
                            style="width: 24px; height: 24px; border-radius: 50%;"
                            onclick="removeGaleriFile(${index})">
                            <i class="ti ti-x" style="font-size: 0.7rem;"></i>
                        </button>
                        <div class="p-1" style="background: rgba(255,255,255,0.9); position: absolute; bottom:0; left:0; right:0;">
                            <input type="text" name="galeri_keterangan[]"
                                   class="form-control form-control-sm"
                                   placeholder="Keterangan foto..."
                                   style="font-size: 0.72rem; padding: 2px 6px;">
                        </div>
                    </div>
                `;
                galeriContainer.appendChild(col);
            };
            reader.readAsDataURL(file);
        });

        syncGaleriInput();
    }

    function syncGaleriInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        galeriInput.files = dt.files;
    }

    window.removeGaleriFile = function (index) {
        selectedFiles.splice(index, 1);
        renderGaleriPreview();
    };

    galeriInput.addEventListener('change', function () {
        Array.from(this.files).forEach(file => selectedFiles.push(file));
        renderGaleriPreview();
    });

    // Drag & drop galeri
    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropZone.style.background = 'var(--tblr-primary-lt)';
    });
    dropZone.addEventListener('dragleave', function () {
        dropZone.style.background = '';
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.style.background = '';
        Array.from(e.dataTransfer.files).forEach(function (file) {
            if (file.type.startsWith('image/')) selectedFiles.push(file);
        });
        renderGaleriPreview();
    });

    // Hapus Galeri yang Ada (mode edit)
    document.querySelectorAll('.hapus-galeri-cb').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const targetId = this.dataset.target;
            const card = document.getElementById(targetId);
            if (this.checked) {
                card.style.opacity = '0.4';
                card.style.outline = '2px solid red';
            } else {
                card.style.opacity = '';
                card.style.outline = '';
            }
        });
    });

    // Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
});
</script>
@endpush
