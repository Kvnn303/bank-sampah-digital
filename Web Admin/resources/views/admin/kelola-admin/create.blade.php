@extends('layouts.admin')

@section('title', 'Tambah Admin')
@section('page-title', 'Tambah Admin Baru')

@section('content')

<div class="container-narrow">

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.kelola-admin.index') }}" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 19l-7 -7m0 0l7 -7m-7 7h18"/></svg>
            Kelola Admin
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6"/></svg>
        <span class="text-muted small">Tambah Baru</span>
    </div>

    {{-- Info Card --}}
    <div class="alert alert-info mb-4">
        <div class="d-flex align-items-start">
            <div class="me-3 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4h.01"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/></svg>
            </div>
            <div>
                <h4 class="alert-title fs-6 fw-bold mb-1">Informasi Akun Baru</h4>
                <p class="text-muted small mb-0">Admin baru akan dibuat dengan status <span class="badge bg-success-lt text-success px-2 py-0">Aktif</span> dan password bertanda <span class="badge bg-warning-lt text-warning px-2 py-0">Default</span>. Admin tersebut wajib mengganti password saat login pertama kali.</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M12 10a3 3 0 1 0 0 -6a3 3 0 0 0 0 6"/><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"/></svg>
                Data Akun Admin
            </h3>
            <div class="card-options">
                <span class="badge bg-primary-lt text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4h.01"/></svg>
                    Form Wajib
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.kelola-admin.store') }}">
            @csrf

            <div class="card-body">
                <div class="row g-4">

                    {{-- Nama Lengkap --}}
                    <div class="col-12">
                        <label class="form-label required fw-semibold">Nama Lengkap</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 21v-2a4 4 0 0 0 -4 -4h-8a4 4 0 0 0 -4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Ahmad Fauzi" required autofocus>
                        </div>
                        @error('name')
                        <div class="invalid-feedback d-flex align-items-center gap-1 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M12 8l0 4"/><path d="M12 16l.01 0"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-12">
                        <label class="form-label required fw-semibold">Email</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8"/><path d="M5 19h14a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2z"/></svg>
                            </span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                        </div>
                        @error('email')
                        <div class="invalid-feedback d-flex align-items-center gap-1 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M12 8l0 4"/><path d="M12 16l.01 0"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Divider --}}
                    <div class="col-12">
                        <hr class="my-1">
                        <div class="text-muted small fw-semibold mt-2 mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15v2m-6 4h12a2 2 0 0 0 2 -2v-6a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2zm10 -10v-4a1 1 0 0 0 -1 -1h-4a1 1 0 0 0 -1 1v4"/></svg>
                            KEAMANAN AKUN
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label class="form-label required fw-semibold">Password</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15v2m-6 4h12a2 2 0 0 0 2 -2v-6a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2zm10 -10v-4a1 1 0 0 0 -1 -1h-4a1 1 0 0 0 -1 1v4"/></svg>
                            </span>
                            <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                            <span class="input-icon-addon cursor-pointer" onclick="togglePw('passwordInput', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-eye" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0 -20z"/></svg>
                            </span>
                        </div>
                        <div class="form-hint">Minimal 6 karakter</div>
                        @error('password')
                        <div class="invalid-feedback d-flex align-items-center gap-1 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M12 8l0 4"/><path d="M12 16l.01 0"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="col-md-6">
                        <label class="form-label required fw-semibold">Konfirmasi Password</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4m5.618 -4.016a11.955 11.955 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            </span>
                            <input type="password" name="password_confirmation" id="passwordConfirmInput" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Ulangi password" required>
                            <span class="input-icon-addon cursor-pointer" onclick="togglePw('passwordConfirmInput', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-eye" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0 -20z"/></svg>
                            </span>
                        </div>
                        @error('password_confirmation')
                        <div class="invalid-feedback d-flex align-items-center gap-1 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M12 8l0 4"/><path d="M12 16l.01 0"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="card-footer d-flex justify-content-end gap-2 border-top">
                <a href="{{ route('admin.kelola-admin.index') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 19l-7 -7m0 0l7 -7m-7 7h18"/></svg>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                    Simpan Admin
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('.icon-eye');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 9a5 5 0 0 0 0 8m6.9 -5.7a9 9 0 0 1 1.1 5.7a9 9 0 0 1 -1.1 5.7"/><path d="M10.1 3.3a9 9 0 0 0 -7.1 6.7a9 9 0 0 0 7.1 6.7"/><line x1="2" y1="2" x2="22" y2="22"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0 -20z"/>';
    }
}
</script>
@endpush