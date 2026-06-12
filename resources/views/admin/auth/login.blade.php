@extends('layouts.admin')

@section('title', 'Login Admin | Bank Sampah Digital')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-dark: #0f172a;
        --primary-mid:  #1e293b;
        --green:        #10b981;
        --green-hover:  #059669;
        --glass-bg:     rgba(255,255,255,0.97);
    }

    .login-root {
        position: fixed; inset: 0;
        background: radial-gradient(circle at top right, var(--primary-mid), var(--primary-dark));
        overflow-y: auto;
        font-family: 'Inter', sans-serif;
        z-index: 99999;
    }

    .navbar-vertical, .navbar-top, .page-header, .sidebar,
    .sidebar-backdrop, .footer, nav:not(.login-nav),
    aside, .main-sidebar, .topbar { display: none !important; }

    .bg-mesh {
        position: absolute; inset: 0; pointer-events: none;
        background-image:
            radial-gradient(at 0% 0%,    rgba(16,185,129,.15) 0, transparent 50%),
            radial-gradient(at 100% 100%, rgba(59,130,246,.10) 0, transparent 50%);
    }
    .shape {
        position: absolute; border-radius: 50%;
        background: linear-gradient(45deg, rgba(255,255,255,.04), transparent);
        animation: float 20s infinite ease-in-out;
    }
    @keyframes float {
        0%,100% { transform: translate(0,0)           rotate(0deg);  }
        33%      { transform: translate(30px,-50px)    rotate(10deg); }
        66%      { transform: translate(-20px,20px)    rotate(-10deg);}
    }

    .back-btn {
        position: absolute; top: 2rem; left: 2rem; z-index: 10;
        display: inline-flex; align-items: center; gap: 8px;
        color: rgba(255,255,255,.8); text-decoration: none;
        font-weight: 600; font-size: .88rem;
        padding: 9px 18px;
        background: rgba(255,255,255,.1); backdrop-filter: blur(10px);
        border-radius: 50rem; border: 1px solid rgba(255,255,255,.08);
        transition: all .25s;
    }
    .back-btn:hover { background: rgba(255,255,255,.2); color: #fff; transform: translateX(-4px); }

    /* ── Card ── */
    .login-card {
        width: 100%; max-width: 420px;
        background: var(--glass-bg);
        border-radius: 28px;
        box-shadow: 0 30px 70px -12px rgba(0,0,0,.55);
        overflow: hidden; position: relative; z-index: 1;
        animation: cardIn .55s cubic-bezier(.2,.8,.2,1);
    }
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(28px) scale(.96); }
        to   { opacity: 1; transform: translateY(0)    scale(1);   }
    }

    /* ── Card header ── */
    .card-header-wave {
        background: linear-gradient(135deg, var(--green), var(--green-hover));
        padding: 3rem 2rem;
        text-align: center; position: relative;
    }
    .card-header-wave::after {
        content: ""; position: absolute; bottom: -1px; left: 0;
        width: 100%; height: 36px;
        background: var(--glass-bg);
        clip-path: ellipse(60% 100% at 50% 100%);
    }
    .logo-box {
        width: 80px; height: 80px; margin: 0 auto 1.25rem;
        background: #fff; border-radius: 20px; padding: 10px;
        box-shadow: 0 12px 30px rgba(16,185,129,.3);
        display: flex; align-items: center; justify-content: center;
        position: relative; z-index: 2;
    }
    .logo-box img   { width: 100%; height: 100%; object-fit: contain; border-radius: 10px; }
    .card-title     { color: #fff; font-size: 1.5rem; font-weight: 800; letter-spacing: -.4px; position: relative; z-index: 2; margin-bottom: .2rem; }
    .card-subtitle  { color: rgba(255,255,255,.8); font-size: .78rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; position: relative; z-index: 2; }

    /* ── Form ── */
    .form-body { padding: 2rem 2.25rem 2.75rem; }

    .field             { margin-bottom: 1.25rem; }
    .field label       { display: block; font-size: .82rem; font-weight: 700; color: #475569; margin-bottom: .5rem; margin-left: 2px; }
    .input-wrap        { position: relative; }
    .input-wrap .ico   { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; transition: color .25s; }
    .input-wrap input  {
        width: 100%; padding: 14px 44px 14px 48px;
        background: #f8fafc; border: 2px solid #e2e8f0;
        border-radius: 14px; font-size: .93rem; font-weight: 500;
        color: #0f172a; outline: none; transition: all .25s;
    }
    .input-wrap input::placeholder { color: #94a3b8; font-weight: 400; }
    .input-wrap input:focus        { background: #fff; border-color: var(--green); box-shadow: 0 0 0 4px rgba(16,185,129,.12); }
    .input-wrap input.is-invalid   { border-color: #f87171; background: #fff5f5; }
    .input-wrap input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(239,68,68,.1); }

    /* ── Error states ── */
    .alert-error {
        display: flex; align-items: flex-start; gap: 12px;
        background: #fef2f2; border-left: 4px solid #ef4444;
        color: #b91c1c; border-radius: 12px;
        padding: 13px 16px; font-size: .85rem; font-weight: 600;
        margin-bottom: 1.5rem;
        animation: shake .4s cubic-bezier(.36,.07,.19,.97);
    }
    .alert-error svg { flex-shrink: 0; margin-top: 1px; }
    @keyframes shake {
        0%,100% { transform: translateX(0);  }
        20%      { transform: translateX(-6px); }
        40%      { transform: translateX(6px);  }
        60%      { transform: translateX(-4px); }
        80%      { transform: translateX(4px);  }
    }

    .field-error {
        display: flex; align-items: center; gap: 5px;
        color: #dc2626; font-size: .8rem; font-weight: 600; margin-top: 6px;
    }

    /* ── Toggle password ── */
    .eye-btn {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        border: none; background: none; color: #94a3b8;
        cursor: pointer; padding: 4px; border-radius: 8px;
        display: flex; align-items: center; transition: color .2s, background .2s;
    }
    .eye-btn:hover { color: var(--green); background: #f0fdf4; }

    /* ── Submit button ── */
    .btn-submit {
        width: 100%; margin-top: 1.75rem; padding: 15px;
        border: none; border-radius: 14px;
        background: var(--primary-dark); color: #fff;
        font-weight: 700; font-size: .97rem;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        cursor: pointer; transition: all .3s cubic-bezier(.2,.8,.2,1);
        box-shadow: 0 8px 20px rgba(15,23,42,.2);
    }
    .btn-submit:hover    { background: var(--green); transform: translateY(-2px); box-shadow: 0 14px 28px rgba(16,185,129,.3); }
    .btn-submit:active   { transform: translateY(0); }
    .btn-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; box-shadow: none; }

    /* ── Spinner (PERBAIKAN: dipindah ke sini dari HTML) ── */
    @keyframes spin { to { transform: rotate(360deg); } }
    .spin { animation: spin .8s linear infinite; }

    /* ── Footer ── */
    .footer-note { text-align: center; margin-top: 2rem; color: #94a3b8; font-size: .78rem; line-height: 1.7; }

    @media (max-width: 480px) {
        .back-btn   { top: 1.25rem; left: 1.25rem; font-size: .8rem; padding: 7px 14px; }
        .login-card { margin-top: 64px; border-radius: 20px; }
        .form-body  { padding: 1.75rem 1.5rem 2.25rem; }
    }
</style>
@endpush

@section('content')
<div class="login-root">
    <div class="bg-mesh"></div>
    <div class="shape" style="width:400px;height:400px;top:-100px;right:-100px;"></div>
    <div class="shape" style="width:280px;height:280px;bottom:-60px;left:-60px;animation-delay:-7s;"></div>

    <a href="{{ route('beranda') }}" class="back-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Kembali ke Beranda
    </a>

    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">
        <div class="login-card">

            <div class="card-header-wave">
                <div class="logo-box">
                    <img src="{{ asset('image/BankSampahlogo.jpg') }}" alt="Logo Bank Sampah">
                </div>
                <h2 class="card-title">Bank Sampah Induk Subang</h2>
                <p class="card-subtitle">Dinas Lingkungan Hidup Subang</p>
            </div>

            <div class="form-body">

                @if ($errors->any())
                    <div class="alert-error" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8"  x2="12"   y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST" autocomplete="off" id="loginForm">
                    @csrf

                    {{-- Email --}}
                    <div class="field">
                        <label for="email">Alamat Email</label>
                        <div class="input-wrap">
                            <svg class="ico" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="admin@banksampah.com"
                                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <p class="field-error">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8"  x2="12"   y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="field mb-1">
                        <label for="password">Kata Sandi</label>
                        <div class="input-wrap">
                            <svg class="ico" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                required
                            >
                            <button type="button" class="eye-btn" onclick="togglePassword()" title="Tampilkan/Sembunyikan">
                                <svg id="eye-show" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg id="eye-hide" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="field-error">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8"  x2="12"   y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span id="btnText">Masuk</span>
                        <svg id="btnArrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                        <svg id="btnSpinner" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             class="spin">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                        </svg>
                    </button>
                </form>

                <div class="footer-note">
                    <p class="mb-1" style="font-weight:700;color:#334155;">Dinas Lingkungan Hidup Subang</p>
                    <p class="mb-0">&copy; {{ date('Y') }} Bank Sampah Digital &bull; Secure Access</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const show  = document.getElementById('eye-show');
    const hide  = document.getElementById('eye-hide');
    const isHidden = input.type === 'password';
    input.type         = isHidden ? 'text'  : 'password';
    show.style.display = isHidden ? 'none'  : 'block';
    hide.style.display = isHidden ? 'block' : 'none';
}

document.getElementById('loginForm').addEventListener('submit', function () {
    const btn     = document.getElementById('submitBtn');
    const text    = document.getElementById('btnText');
    const arrow   = document.getElementById('btnArrow');
    const spinner = document.getElementById('btnSpinner');
    btn.disabled          = true;
    text.textContent      = 'Memproses...';
    arrow.style.display   = 'none';
    spinner.style.display = 'inline-block';
});

document.addEventListener('DOMContentLoaded', function () {
    document.body.style.backgroundColor = '#0f172a';
    ['.modal-backdrop', '.sidebar-backdrop', '.fade'].forEach(sel =>
        document.querySelectorAll(sel).forEach(el => el.remove())
    );
});
</script>
@endpush