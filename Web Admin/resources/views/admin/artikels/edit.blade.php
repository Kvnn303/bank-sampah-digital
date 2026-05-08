@extends('layouts.admin')

@section('title', 'Edit Artikel')
@section('page-title', 'Edit Artikel')

@push('styles')
<style>
    /* Styling Modern untuk Form Wrapper */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    /* Override gaya form di dalam include admin.artikels.form */
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

    .text-amber { color: #f59e0b !important; }
    .bg-amber-lt { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">

        <!-- Card Form Modern -->
        <div class="card card-modern">

            <!-- Header -->
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-amber-lt rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-amber" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-3">Edit Artikel</h3>
                        <p class="text-slate-500 small m-0 mt-1">Perbarui konten, ubah gambar, atau kelola status publikasi artikel.</p>
                    </div>
                </div>
                <div class="card-options">
                    <a href="{{ route('admin.artikels.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4 d-none d-sm-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Body Form -->
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.artikels.update', $artikel->id) }}" method="POST" enctype="multipart/form-data" id="form-artikel">
                    @csrf
                    @method('PUT')

                    <!-- Include Form Template -->
                    @include('admin.artikels.form')

                </form>
            </div>

        </div>

        <!-- Tombol Kembali Mobile -->
        <div class="mt-4 text-center d-block d-sm-none">
            <a href="{{ route('admin.artikels.index') }}" class="btn btn-light border shadow-sm rounded-pill fw-semibold px-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-slate-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Daftar
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi TinyMCE
    tinymce.init({
        selector: '#editor',
        height: 300,
        menubar: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family: "Plus Jakarta Sans", sans-serif; font-size: 15px; }' // Optional: Membuat font editor modern
    });

    // Preview Gambar
    document.getElementById('gambarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewContainer').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
