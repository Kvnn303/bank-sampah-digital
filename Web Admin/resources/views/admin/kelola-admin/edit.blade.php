@extends('layouts.admin')

@section('title', 'Edit Admin - ' . $admin->name)
@section('page-title', 'Edit Admin')

@section('content')

<div class="container-narrow">

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.kelola-admin.index') }}" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 19l-7 -7m0 0l7 -7m-7 7h18"/></svg>
            Kelola Admin
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6"/></svg>
        <span class="text-muted small">Edit</span>
    </div>

    {{-- INFO AKUN --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-lg avatar-rounded bg-primary-lt text-primary d-flex align-items-center justify-content-center fw-bold" style="font-size:1.2rem;">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
                <div class="flex-fill">
                    <div class="fw-bold fs-6">{{ $admin->name }}</div>
                    <div class="text-muted small">{{ $admin->email }}</div>
                    <div class="d-flex gap-1 mt-1">
                        <span class="badge {{ $admin->is_active ? 'bg-success' : 'bg-danger' }}">
                            @if($admin->is_active)
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                            @endif
                            {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <span class="badge {{ $admin->password_changed ? 'bg-blue-lt text-blue' : 'bg-warning-lt text-warning' }}">
                            @if($admin->password_changed)
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="12" height="12" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4h.01"/></svg>
                            @endif
                            {{ $admin->password_changed ? 'Password Diganti' : 'Password Default' }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.kelola-admin.view', $admin->id) }}" class="btn btn-outline-info btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0 -20"/></svg>
                    Detail
                </a>
            </div>
        </div>
    </div>

    {{-- FORM EDIT --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.196 2.196 0 0 0 -1.606 -3.175a2.196 2.196 0 0 0 -2.606 1.297l-7.173 14.293l-2.4.4l1.6 -2.4l7.173 -14.293z"/></svg>
                Edit Informasi Akun
            </h3>
            <div class="card-options">
                <span class="badge bg-warning-lt text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4h.01"/></svg>
                    Mode Edit
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.kelola-admin.update', $admin->id) }}">
            @csrf @method('PUT')

            <div class="card-body">
                <div class="row g-4">

                    {{-- Nama Lengkap --}}
                    <div class="col-12">
                        <label class="form-label required fw-semibold">Nama Lengkap</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 21v-2a4 4 0 0 0 -4 -4h-8a4 4 0 0 0 -4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" placeholder="Contoh: Ahmad Fauzi" required autofocus>
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
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" placeholder="contoh@email.com" required>
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
                        <div class="d-flex align-items-center gap-2 mt-2 mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15v2m-6 4h12a2 2 0 0 0 2 -2v-6a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2zm10 -10v-4a1 1 0 0 0 -1 -1h-4a1 1 0 0 0 -1 1v4"/></svg>
                            <span class="text-muted small fw-semibold">UBAH PASSWORD</span>
                            <span class="badge bg-secondary-lt text-secondary" style="font-size:10px;">OPSIONAL</span>
                        </div>
                    </div>

                    {{-- Info password --}}
                    <div class="col-12">
                        <div class="alert alert-warning mb-0 py-2 px-3">
                            <div class="d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4h.01"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/></svg>
                                <span class="text-muted small">Biarkan kosong jika <strong>tidak ingin mengubah</strong> password. Jika diisi, status password akan berubah menjadi "Sudah Diganti".</span>
                            </div>
                        </div>
                    </div>

                    {{-- Password Baru --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15v2m-6 4h12a2 2 0 0 0 2 -2v-6a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2zm10 -10v-4a1 1 0 0 0 -1 -1h-4a1 1 0 0 0 -1 1v4"/></svg>
                            </span>
                            <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
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
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4m5.618 -4.016a11.955 11.955 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            </span>
                            <input type="password" name="password_confirmation" id="passwordConfirmInput" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Ulangi password baru">
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
                <button type="submit" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                    Simpan Perubahan
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