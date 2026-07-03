@extends('layouts.admin')

@section('title', 'Edit Admin - ' . $admin->name)
@section('page-title', 'Perbarui Data Administrator')

@push('styles')
<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        background: #ffffff;
        overflow: hidden;
    }
    .form-label {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.82rem;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .form-control {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 0.75rem 1.1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        color: #0f172a;
    }
    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
        outline: none;
    }
    .icon-shape {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.kelola-admin.index') }}" class="text-slate-500 small fw-bold text-decoration-none d-inline-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Admin
        </a>
    </div>
    <span class="badge bg-amber bg-opacity-10 text-amber border border-amber border-opacity-25 px-3 py-2 rounded-pill fw-bold">
        🛠️ Mode Perubahan Data
    </span>
</div>

{{-- INFO AKUN RINGKAS --}}
<div class="card card-modern mb-4 border-start border-4 border-primary" style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);">
    <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            @if($admin->foto)
                <img src="{{ asset('storage/' . $admin->foto) }}" alt="{{ $admin->name }}" class="avatar avatar-xl rounded-circle shadow-sm border border-2" style="width: 68px; height: 68px; object-fit: cover;">
            @else
                <div class="avatar avatar-xl rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 68px; height: 68px; font-size: 1.8rem;">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    {{ $admin->name }}
                    @if($admin->is_active)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill small px-2">Aktif</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill small px-2">Nonaktif</span>
                    @endif
                </h4>
                <div class="text-slate-500 small d-flex align-items-center gap-2 flex-wrap">
                    <span>📧 {{ $admin->email }}</span>
                    <span>•</span>
                    <span>🛡️ Status Sandi: <strong class="{{ $admin->password_changed ? 'text-success' : 'text-warning' }}">{{ $admin->password_changed ? 'Sandi Aman' : 'Default (admin123)' }}</strong></span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.kelola-admin.view', $admin->id) }}" class="btn btn-light border rounded-pill fw-bold px-4 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Lihat Detail Lengkap
            </a>
        </div>
    </div>
</div>

{{-- FORM EDIT --}}
<div class="card card-modern">
    <div class="card-header bg-white border-bottom p-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="icon-shape bg-amber bg-opacity-10 text-amber me-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
            </div>
            <div>
                <h3 class="card-title fw-bold text-dark m-0 fs-4">Pembaruan Identitas & Kredensial</h3>
                <p class="text-slate-500 small m-0 mt-1">Perubahan informasi akan langsung diterapkan dan tercatat dalam log audit sistem.</p>
            </div>
        </div>
        <span class="text-muted small font-monospace">Terakhir Diperbarui: {{ $admin->updated_at->format('d/m/Y H:i') }}</span>
    </div>

    <div class="card-body p-4 p-md-5">
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 p-3 mb-4">
                <div class="small fw-bold mb-1">Terjadi kesalahan pada input data:</div>
                <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.kelola-admin.update', $admin->id) }}">
            @csrf @method('PUT')

            <div class="row g-4">
                {{-- Nama Lengkap --}}
                <div class="col-md-6">
                    <label class="form-label required">Nama Lengkap Pengurus</label>
                    <input type="text" name="name" class="form-control fw-bold text-dark @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label class="form-label required">Alamat Email Resmi</label>
                    <input type="email" name="email" class="form-control fw-bold text-dark @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="col-12 mt-4">
                    <div class="p-4 rounded-4 bg-light border">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-dark text-white rounded-pill px-3 py-1">OPSIONAL</span>
                            <h6 class="m-0 fw-bold text-dark">Perbarui Keamanan Password</h6>
                        </div>
                        <p class="text-muted small mb-3">Biarkan kedua kolom di bawah ini <strong>kosong</strong> jika Anda tidak ingin mengganti kata sandi admin ini.</p>

                        <div class="row g-3">
                            {{-- Password Baru --}}
                            <div class="col-md-6">
                                <label class="form-label">Password Baru</label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diganti">
                                    <span class="position-absolute end-0 top-50 translate-middle-y me-3 text-slate-400" style="cursor: pointer;" onclick="togglePw('passwordInput', this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20z"/></svg>
                                    </span>
                                </div>
                                <span class="text-slate-400 small mt-1 d-block">Minimal 6 karakter</span>
                                @error('password')
                                    <div class="invalid-feedback fw-medium mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" id="passwordConfirmInput" class="form-control" placeholder="Ulangi password baru">
                                    <span class="position-absolute end-0 top-50 translate-middle-y me-3 text-slate-400" style="cursor: pointer;" onclick="togglePw('passwordConfirmInput', this)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20z"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Action --}}
                <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="text-muted small">💡 Sistem akan mencatat waktu pembaruan (`updated_at`) setelah disimpan.</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.kelola-admin.index') }}" class="btn btn-light border rounded-pill px-4 fw-bold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm d-inline-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
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
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20z"/>';
    }
}
</script>
@endpush