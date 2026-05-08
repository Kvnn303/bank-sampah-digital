@extends('layouts.admin')

@section('title', 'Login Admin | Bank Sampah Digital')

@section('content')
<div class="login-root">

    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #0f172a;
            --primary-mid: #1e293b;
            --primary-light: #10b981;
            --primary-hover: #059669;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        /* Full Screen Reset */
        .login-root {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            background: radial-gradient(circle at top right, var(--primary-mid), var(--primary-dark));
            overflow-y: auto;
            font-family: 'Inter', sans-serif;
        }

        /* Hide Admin Layout Elements */
        .navbar-vertical, .navbar-top, .page-header, .sidebar,
        .sidebar-backdrop, .footer, nav:not(.login-nav),
        aside, .main-sidebar, .topbar {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }

        /* Tombol Kembali ke Beranda */
        .back-home-btn {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 50rem;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s;
            z-index: 10;
        }
        .back-home-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* Modern Mesh Background */
        .bg-mesh {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.15) 0, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.1) 0, transparent 50%);
            z-index: 0;
        }

        /* Floating Shapes */
        .shape {
            position: absolute;
            background: linear-gradient(45deg, rgba(255,255,255,0.03), rgba(255,255,255,0));
            border-radius: 50%;
            z-index: 0;
            animation: float 20s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(10deg); }
            66% { transform: translate(-20px, 20px) rotate(-10deg); }
        }

        /* Card Styling */
        .login-card {
            max-width: 440px;
            width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 28px;
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--glass-border);
            position: relative;
            z-index: 1;
            overflow: hidden;
            animation: cardEntrance 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Header with Gradient Wave */
        .card-wave {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-hover) 100%);
            padding: 3.5rem 2rem 2.5rem;
            position: relative;
            text-align: center;
        }

        .card-wave::after {
            content: "";
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 40px;
            background: var(--glass-bg);
            clip-path: ellipse(60% 100% at 50% 100%);
        }

        .logo-container {
            width: 85px;
            height: 85px;
            margin: 0 auto 1.5rem;
            background: white;
            padding: 10px;
            border-radius: 22px;
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 12px;
        }

        .brand-title {
            color: #ffffff;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
            position: relative;
            z-index: 2;
        }

        .brand-subtitle {
            color: rgba(255,255,255,0.85);
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            position: relative;
            z-index: 2;
        }

        /* Form Controls */
        .form-body {
            padding: 2rem 2.5rem 3rem;
        }

        .input-group-custom {
            margin-bottom: 1.5rem;
        }

        .input-group-custom label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.6rem;
            margin-left: 4px;
        }

        .input-wrapper {
            position: relative;
        }

        /* SVG Icons Positioning */
        .input-wrapper svg.input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: color 0.3s;
        }

        .input-wrapper input {
            width: 100%;
            padding: 15px 16px 15px 50px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 0.95rem;
            font-weight: 500;
            color: #0f172a;
            transition: all 0.3s;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-wrapper input:focus {
            background: white;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .input-wrapper input:focus + svg.input-icon {
            color: var(--primary-light);
        }

        /* Button Login */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--dark);
            color: white;
            border: none;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
            margin-top: 2rem;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15);
        }

        .btn-login:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Custom Alert */
        .alert-modern {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Toggle Password Button */
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
            border-radius: 50%;
        }

        .toggle-password:hover {
            color: var(--primary-light);
            background: #f1f5f9;
        }

        .footer-note {
            text-align: center;
            margin-top: 2.5rem;
            color: #94a3b8;
            font-size: 0.8rem;
            line-height: 1.6;
        }

        @media (max-width: 576px) {
            .back-home-btn { top: 1.5rem; left: 1.5rem; padding: 8px 16px; font-size: 0.8rem; }
            .login-card { margin-top: 60px; }
        }
    </style>
    @endpush

    {{-- Decorative Background --}}
    <div class="bg-mesh"></div>
    <div class="shape" style="width: 400px; height: 400px; top: -100px; right: -100px;"></div>
    <div class="shape" style="width: 300px; height: 300px; bottom: -50px; left: -50px; animation-delay: -5s;"></div>

    {{-- Tombol Kembali ke Beranda --}}
    <a href="{{ route('beranda') }}" class="back-home-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali ke Beranda
    </a>

    {{-- Main Content --}}
    <div class="d-flex align-items-center justify-content-center min-vh-100 p-4">
        <div class="login-card">

            {{-- Header --}}
            <div class="card-wave">
                <div class="logo-container">
                    <img src="{{ asset('image/BankSampahlogo.jpg') }}" alt="Bank Sampah Logo">
                </div>
                <h2 class="brand-title">Bank Sampah Induk Subang</h2>
                <p class="brand-subtitle text-uppercase">Dinas Lingkungan Hidup Subang</p>
            </div>

            {{-- Form Area --}}
            <div class="form-body">
                @if(session('error'))
                    <div class="alert-modern mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST" autocomplete="off">
                    @csrf

                    {{-- Username / Email (Ikon Profil) --}}
                    <div class="input-group-custom">
                        <label>Alamat Email / Username</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@banksampah.com" required autofocus>
                            <!-- Ikon Profil (User) -->
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        @error('email')
                            <p class="text-danger small mt-2 mb-0 fw-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password (Ikon Kunci) --}}
                    <div class="input-group-custom mb-2">
                        <label>Kata Sandi</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <!-- Ikon Kunci (Lock) -->
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>

                            <!-- Tombol Show/Hide Password -->
                            <button type="button" class="toggle-password" onclick="togglePassword()" title="Tampilkan/Sembunyikan Password">
                                <!-- Ikon Eye (Tampil) -->
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <!-- Ikon Eye Off (Sembunyi) -->
                                <svg id="eye-off-icon" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-danger small mt-2 mb-0 fw-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-login">
                        Login <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </form>

                <div class="footer-note">
                    <p class="mb-1 fw-bold text-dark">Dinas Lingkungan Hidup Subang</p>
                    <p class="mb-0">&copy; {{ date('Y') }} Bank Sampah Digital • Secure Access</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Fungsi untuk mengubah tipe input password dan ikon matanya
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Force cleanup background layout untuk layar admin
        document.body.style.backgroundColor = '#0f172a';
        const selectors = ['.modal-backdrop', '.sidebar-backdrop', '.fade'];
        selectors.forEach(s => {
            document.querySelectorAll(s).forEach(el => el.remove());
        });
    });
</script>
@endpush

@endsection
