@extends('layouts.admin')

@section('title', 'Detail Artikel')

@push('styles')
<style>
    /* Tipografi Konten agar nyaman dibaca */
    .article-content {
        line-height: 1.75;
        color: #374151;
        font-size: 1rem;
    }
    .article-content h2, .article-content h3 {
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        font-weight: 700;
        color: #1f2937;
    }
    .article-content p {
        margin-bottom: 1rem;
    }
    .article-content ul, .article-content ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    .article-content img {
        border-radius: 0.5rem;
        max-width: 100%;
        height: auto;
        margin: 1rem 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .article-content blockquote {
        border-left: 4px solid var(--tblr-primary);
        padding-left: 1rem;
        font-style: italic;
        color: #6b7280;
    }

    /* Hover effects */
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:active {
        transform: scale(0.98);
    }
</style>
@endpush

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Header Actions --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.artikels.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2 me-2">
                    <i class="ti ti-arrow-left" style="font-size: 1rem;"></i>
                    <span>Kembali</span>
                </a>
                <div class="vr d-none d-sm-block mx-2"></div>
                <div class="text-muted">
                    <span class="text-uppercase small fw-bold text-muted">Detail Artikel</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.artikels.edit', $artikel->id) }}" class="btn btn-primary action-btn d-inline-flex align-items-center gap-2">
                    <i class="ti ti-edit"></i> Edit
                </a>
                <button type="button" class="btn btn-danger action-btn d-inline-flex align-items-center gap-2" onclick="deleteArtikel({{ $artikel->id }})">
                    <i class="ti ti-trash"></i> Hapus
                </button>
            </div>
        </div>

        <div class="row row-cards">

            {{-- Kolom Kiri: Konten Utama --}}
            <div class="col-12 col-xl-9">

                {{-- Card Cover & Header --}}
                <div class="card card-lg border-0 shadow-sm mb-4 overflow-hidden">
                    @if($artikel->gambar)
                        <img src="{{ Storage::url($artikel->gambar) }}" alt="Cover" class="object-fit-cover w-100" style="max-height: 400px; width: 100%;">
                    @endif

                    <div class="card-body {{ $artikel->gambar ? 'mt-4' : 'pt-4' }}">

                        {{-- Badges & Meta --}}
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <span class="badge bg-primary-lt text-primary rounded-pill px-3 py-2">
                                {{ str_replace('_', ' ', ucfirst($artikel->kategori)) }}
                            </span>
                            @if($artikel->is_published)
                                <span class="badge bg-success-lt text-success rounded-pill px-3 py-2">
                                    Published
                                </span>
                            @else
                                <span class="badge bg-warning-lt text-warning rounded-pill px-3 py-2">
                                    Draft
                                </span>
                            @endif
                        </div>

                        <h1 class="card-title mb-4" style="font-size: 2rem; font-weight: 700; line-height: 1.3;">
                            {{ $artikel->judul }}
                        </h1>

                        {{-- Author & Date Compact --}}
                        <div class="d-flex align-items-center gap-3 mb-4 text-muted border-bottom pb-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm rounded-circle bg-primary text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($artikel->author?->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="small">
                                    <div class="fw-bold text-body">{{ $artikel->author?->name ?? 'Unknown' }}</div>
                                    <div style="font-size: 0.75rem;">Penulis</div>
                                </div>
                            </div>
                            <div class="vr"></div>
                            <div class="small">
                                <div class="fw-bold text-body">{{ $artikel->created_at?->format('d M Y') ?? '-' }}</div>
                                <div style="font-size: 0.75rem;">{{ $artikel->created_at?->format('H:i') ?? '' }} WIB</div>
                            </div>
                        </div>

                        {{-- Body Content --}}
                        <div class="article-content">
                            {!! $artikel->konten !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Sidebar Info --}}
            <div class="col-12 col-xl-3">

                {{-- Card Info Umum --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-transparent border-bottom-0 py-3">
                        <h3 class="card-title mb-0 small text-uppercase text-muted fw-bold ls-1">Informasi</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-4 py-3">
                            <div class="small text-muted mb-1">ID Artikel</div>
                            <div class="fw-bold text-dark">#{{ $artikel->id }}</div>
                        </div>
                        <div class="list-group-item px-4 py-3">
                            <div class="small text-muted mb-1">Status</div>
                            @if($artikel->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning text-dark">Draft</span>
                            @endif
                        </div>
                        <div class="list-group-item px-4 py-3">
                            <div class="small text-muted mb-1">Kategori</div>
                            <div class="fw-bold text-dark">{{ ucfirst($artikel->kategori) }}</div>
                        </div>
                        <div class="list-group-item px-4 py-3">
                            <div class="small text-muted mb-1">Terakhir Diperbarui</div>
                            <div class="fw-bold text-dark">{{ $artikel->updated_at?->diffForHumans() ?? 'Baru saja' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Card Slug --}}
                @if(isset($artikel->slug) && $artikel->slug)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-transparent border-bottom-0 py-3">
                        <h3 class="card-title mb-0 small text-uppercase text-muted fw-bold ls-1">URL Slug</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group input-group-flat">
                            <input type="text" class="form-control form-control-sm font-monospace bg-body-tertiary border-0"
                                value="{{ $artikel->slug }}" id="slugInput" readonly>
                            <button class="btn btn-sm btn-light" type="button" onclick="copySlug()" title="Salin Slug">
                                <i class="ti ti-copy"></i>
                            </button>
                        </div>
                        <div id="copyMsg" class="text-success small mt-2 text-center" style="display:none;">
                            <i class="ti ti-check"></i> Slug berhasil disalin
                        </div>
                    </div>
                </div>
                @endif

                {{-- Card Galeri (Dokumentasi) --}}
                @if($artikel->galeri && $artikel->galeri->count() > 0)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 small text-uppercase text-muted fw-bold ls-1">Galeri ({{ $artikel->galeri->count() }})</h3>
                    </div>
                    <div class="card-body pt-2">
                        <div class="row g-2">
                            @foreach($artikel->galeri as $photo)
                            <div class="col-6">
                                <a href="{{ Storage::url($photo->gambar) }}" data-bs-toggle="lightbox" data-gallery="gallery-artikel" class="d-block">
                                    <img src="{{ Storage::url($photo->gambar) }}" alt="Galeri" class="img-fluid rounded" style="aspect-ratio: 4/3; object-fit: cover; width: 100%; cursor: zoom-in;">
                                </a>
                                @if($photo->keterangan)
                                <div class="small text-muted text-truncate mt-1" title="{{ $photo->keterangan }}">{{ $photo->keterangan }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Quick Action Buttons for Mobile/Tablet --}}
                <div class="d-xl-none card border-0 shadow-sm mb-3">
                    <div class="card-body d-flex flex-column gap-2">
                        <a href="{{ route('admin.artikels.edit', $artikel->id) }}" class="btn btn-primary w-100">Edit Artikel</a>
                        <button type="button" class="btn btn-outline-danger w-100" onclick="deleteArtikel({{ $artikel->id }})">Hapus Artikel</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Form Hidden untuk Delete --}}
<form id="formDelete" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

});

function deleteArtikel(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus artikel ini?\n\nSemua data galeri yang terkait juga akan dihapus permanen.')) return;

    const form = document.getElementById('formDelete');
    form.action = '{{ route("admin.artikels.destroy", "__ID__") }}'.replace('__ID__', id);
    form.submit();
}

function copySlug() {
    const input = document.getElementById('slugInput');
    if (!input) return;

    input.select();
    input.setSelectionRange(0, 99999); // Untuk mobile

    navigator.clipboard.writeText(input.value).then(function () {
        const msg = document.getElementById('copyMsg');
        msg.style.display = 'block';
        setTimeout(() => {
            msg.style.display = 'none';
        }, 2000);
    }, function(err) {
        console.error('Gagal menyalin: ', err);
    });
}
</script>
@endpush
