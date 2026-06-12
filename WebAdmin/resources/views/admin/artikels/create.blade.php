@extends('layouts.admin')

@section('title', 'Tambah Artikel Baru')
@section('page-title', 'Tambah Artikel')

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

    .text-blue-modern { color: #3b82f6 !important; }
    .bg-blue-lt { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }
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
                    <div class="icon-shape bg-blue-lt rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue-modern" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
                    </div>
                    <div>
                        <h3 class="card-title fw-bold text-dark m-0 fs-3">Tulis Artikel Baru</h3>
                        <p class="text-slate-500 small m-0 mt-1">Buat dan publikasikan artikel, berita, atau edukasi baru.</p>
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
                <form action="{{ route('admin.artikels.store') }}" method="POST" enctype="multipart/form-data" id="form-artikel">
                    @csrf

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
