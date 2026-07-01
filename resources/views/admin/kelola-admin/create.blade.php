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

    {{-- Alert error umum (jika ada error validasi selain field spesifik) --}}
    @if ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('password'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M12 8l0 4"/><path d="M12 16l.01 0"/></svg>
            <div>Terjadi kesalahan saat menyimpan data. Periksa kembali isian Anda.</div>
        </div>
    @endif

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

        <form method="POST" action="{{ route('admin.kelola-admin.store') }}" id="formTambahAdmin" novalidate>
            @csrf

            <div class="card-body">
                <div class="row g-4">

                    {{-- Nama Lengkap --}}
                    <div class="col-12">
                        <label for="nameInput" class="form-label required fw-semibold">Nama Lengkap</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 21v-2a4 4 0 0 0 -4 -4h-8a4 4 0 0 0 -4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                            <input type="text" id="nameInput" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Ahmad Fauzi" autocomplete="name" maxlength="100" required autofocus>
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
                        <label for="emailInput" class="form-label required fw-semibold">Email</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8"/><path d="M5 19h14a2 2 0 0 0 2 -2V7a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2z"/></svg>
                            </span>
                            <input type="email" id="emailInput" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@email.com" autocomplete="email" required>
                        </div>
                        <div class="form-hint">Email ini akan digunakan sebagai username untuk login.</div>
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
                        <div class="text-muted small fw-semibold mt-2 mb-0 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15v2m-6 4h12a2 2 0 0 0 2 -2v-6a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2zm10 -10v-4a1 1 0 0 0 -1 -1h-4a1 1 0 0 0 -1 1v4"/></svg>
                            KEAMANAN AKUN
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label for="passwordInput" class="form-label required fw-semibold">Password</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15v2m-6 4h12a2 2 0 0 0 2 -2v-6a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2zm10 -10v-4a1 1 0 0 0 -1 -1h-4a1 1 0 0 0 -1 1v4"/></svg>
                            </span>
                            <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" autocomplete="new-password" minlength="6" required>
                            <span class="input-icon-addon cursor-pointer" onclick="togglePw('passwordInput', this)" role="button" aria-label="Tampilkan/sembunyikan password">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-eye" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0 -20z"/></svg>
                            </span>
                        </div>

                        {{-- Indikator kekuatan password --}}
                        <div class="mt-2" id="pwStrengthWrap" style="display:none;">
                            <div class="progress progress-sm">
                                <div class="progress-bar" id="pwStrengthBar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <div class="form-hint mt-1 mb-0" id="pwStrengthLabel"></div>
                        </div>
                        <div class="form-hint" id="pwDefaultHint">Minimal 6 karakter.</div>

                        @error('password')
                        <div class="invalid-feedback d-flex align-items-center gap-1 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><path d="M12 8l0 4"/><path d="M12 16l.01 0"/></svg>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="col-md-6">
                        <label for="passwordConfirmInput" class="form-label required fw-semibold">Konfirmasi Password</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12l2 2l4 -4m5.618 -4.016a11.955 11.955 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                            </span>
                            <input type="password" name="password_confirmation" id="passwordConfirmInput" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Ulangi password" autocomplete="new-password" required>
                            <span class="input-icon-addon cursor-pointer" onclick="togglePw('passwordConfirmInput', this)" role="button" aria-label="Tampilkan/sembunyikan password">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-eye" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="2"/><path d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0 -20z"/></svg>
                            </span>
                        </div>
                        <div class="form-hint" id="pwMatchHint">&nbsp;</div>
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
                <button type="submit" class="btn btn-primary" id="submitBtn">
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
    // Toggle tampilkan/sembunyikan password
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

    (function () {
        const pwInput = document.getElementById('passwordInput');
        const pwConfirmInput = document.getElementById('passwordConfirmInput');
        const pwStrengthWrap = document.getElementById('pwStrengthWrap');
        const pwStrengthBar = document.getElementById('pwStrengthBar');
        const pwStrengthLabel = document.getElementById('pwStrengthLabel');
        const pwDefaultHint = document.getElementById('pwDefaultHint');
        const pwMatchHint = document.getElementById('pwMatchHint');

        function scorePassword(value) {
            let score = 0;
            if (!value) return 0;
            if (value.length >= 6) score += 1;
            if (value.length >= 10) score += 1;
            if (/[A-Z]/.test(value)) score += 1;
            if (/[0-9]/.test(value)) score += 1;
            if (/[^A-Za-z0-9]/.test(value)) score += 1;
            return score; // 0 - 5
        }

        function updateStrength() {
            const value = pwInput.value;

            if (!value) {
                pwStrengthWrap.style.display = 'none';
                pwDefaultHint.style.display = 'block';
                return;
            }

            pwDefaultHint.style.display = 'none';
            pwStrengthWrap.style.display = 'block';

            const score = scorePassword(value);
            const levels = [
                { max: 1, width: '20%',  color: 'bg-danger',  label: 'Sangat lemah' },
                { max: 2, width: '40%',  color: 'bg-danger',  label: 'Lemah' },
                { max: 3, width: '60%',  color: 'bg-warning', label: 'Cukup' },
                { max: 4, width: '80%',  color: 'bg-info',    label: 'Kuat' },
                { max: 5, width: '100%', color: 'bg-success', label: 'Sangat kuat' },
            ];
            const level = levels.find(l => score <= l.max) || levels[levels.length - 1];

            pwStrengthBar.className = 'progress-bar ' + level.color;
            pwStrengthBar.style.width = level.width;
            pwStrengthLabel.textContent = 'Kekuatan password: ' + level.label;
        }

        function updateMatch() {
            if (!pwConfirmInput.value) {
                pwMatchHint.innerHTML = '&nbsp;';
                pwMatchHint.classList.remove('text-danger', 'text-success');
                return;
            }
            if (pwInput.value === pwConfirmInput.value) {
                pwMatchHint.textContent = 'Password cocok.';
                pwMatchHint.classList.remove('text-danger');
                pwMatchHint.classList.add('text-success');
            } else {
                pwMatchHint.textContent = 'Password tidak cocok.';
                pwMatchHint.classList.remove('text-success');
                pwMatchHint.classList.add('text-danger');
            }
        }

        pwInput.addEventListener('input', function () {
            updateStrength();
            updateMatch();
        });
        pwConfirmInput.addEventListener('input', updateMatch);

        // Validasi ringan sebelum submit (server tetap jadi sumber kebenaran utama)
        document.getElementById('formTambahAdmin').addEventListener('submit', function (e) {
            if (pwInput.value !== pwConfirmInput.value) {
                e.preventDefault();
                updateMatch();
                pwConfirmInput.focus();
            }
        });
    })();
</script>
@endpush
