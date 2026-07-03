<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover"/>
    <meta name="theme-color" content="#10b981">
    <meta name="description" content="Bank Sampah Digital Subang - Tabung sampahmu, dapatkan penghasilan tambahan, dan wujudkan lingkungan bersih di Kabupaten Subang.">
    <link rel="icon" type="image/png" href="{{ asset('image/BankSampahlogo.png') }}">
    <title>Bank Sampah Digital Subang - Ubah Sampah Jadi Berkah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --dark: #0f172a;
            --darker: #020617;
            --slate: #475569;
            --slate-light: #94a3b8;
            --surface: #f8fafc;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; scroll-padding-top: 90px; -webkit-text-size-adjust: 100%; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--slate); overflow-x: hidden; background-color: var(--surface); }

        img { max-width: 100%; height: auto; }

        /* ===== ACCESSIBILITY: FOCUS & MOTION ===== */
        a:focus-visible, button:focus-visible, input:focus-visible, .quick-btn:focus-visible {
            outline: 3px solid var(--primary-dark);
            outline-offset: 3px;
            border-radius: 8px;
        }
        .skip-link {
            position: absolute; top: -100px; left: 12px; z-index: 2000;
            background: var(--dark); color: #fff; padding: 12px 20px; border-radius: 10px;
            font-weight: 700; text-decoration: none; transition: top 0.25s ease;
        }
        .skip-link:focus { top: 12px; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        /* ===== UTILITIES & GLASSMORPHISM ===== */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .icon-box-primary {
            width: 48px; height: 48px; background: var(--primary-light); color: var(--primary-dark);
            border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }

        .icon-box-success {
            width: 48px; height: 48px; background: #dcfce7; color: #16a34a;
            border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }

        .icon-box-large {
            width: 64px; height: 64px; background: white; border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04); display: flex; align-items: center;
            justify-content: center; border: 1px solid #f1f5f9; flex-shrink: 0;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .bg-mesh {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; overflow: hidden;
            background: radial-gradient(at 0% 0%, hsla(147,100%,96%,1) 0, transparent 50%),
                        radial-gradient(at 50% 0%, hsla(200,100%,96%,1) 0, transparent 50%),
                        radial-gradient(at 100% 0%, hsla(280,100%,96%,1) 0, transparent 50%);
        }

        .floating-shape {
            position: absolute; border-radius: 50%; opacity: 0.15;
            animation: floatShape 18s ease-in-out infinite alternate; filter: blur(70px);
        }
        .shape-1 { width: 500px; height: 500px; background: var(--primary); top: -10%; right: -5%; }
        .shape-2 { width: 450px; height: 450px; background: #0ea5e9; bottom: 5%; left: -10%; animation-delay: -5s; }

        @keyframes floatShape {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            100% { transform: translate(-50px, 40px) scale(1.1) rotate(10deg); }
        }

        /* ===== NAVBAR ===== */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1rem 0;
            z-index: 1030;
        }
        .navbar-custom.scrolled {
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            padding: 0.65rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }
        .navbar-brand-custom {
            font-weight: 800; font-size: 1.25rem; color: var(--dark) !important;
            letter-spacing: -0.5px; text-decoration: none; display: flex; align-items: center; gap: 12px;
        }
        .brand-logo { width: 42px; height: 42px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); flex-shrink: 0; }

        .nav-link-custom {
            color: var(--slate) !important; font-weight: 600; font-size: 0.95rem;
            padding: 0.65rem 1.2rem !important; border-radius: 50rem;
            transition: all 0.3s ease; position: relative; margin: 2px 0;
            min-height: 44px; display: flex; align-items: center;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--primary-dark) !important;
            background: rgba(16, 185, 129, 0.1);
        }
        .btn-masuk {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white !important; border: none; border-radius: 50rem;
            padding: 0.7rem 1.8rem; font-weight: 700; font-size: 0.95rem;
            transition: all 0.3s ease; box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
            text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            min-height: 44px;
        }
        .btn-masuk:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4); }

        /* Responsive Mobile Navbar Menu */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(25px);
                -webkit-backdrop-filter: blur(25px);
                margin-top: 1rem;
                padding: 1.5rem;
                border-radius: 22px;
                box-shadow: 0 20px 45px rgba(0, 0, 0, 0.12);
                border: 1px solid rgba(0, 0, 0, 0.06);
            }
            .navbar-nav { gap: 0.35rem; margin-bottom: 1.25rem; }
            .nav-link-custom { justify-content: center; font-size: 1rem; padding: 0.8rem 1rem !important; }
            .btn-masuk { width: 100%; font-size: 1rem; padding: 0.85rem; }
        }

        /* ===== HERO ===== */
        .hero-section {
            padding: 150px 0 90px; position: relative;
            min-height: 100vh; display: flex; align-items: center;
        }
        @supports (min-height: 100dvh) {
            .hero-section { min-height: 100dvh; }
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 8px 20px; border-radius: 50rem;
            background: rgba(255,255,255,0.9); border: 1px solid rgba(16,185,129,0.2);
            backdrop-filter: blur(10px); box-shadow: 0 4px 20px rgba(16, 185, 129, 0.08);
            font-size: 0.8rem; font-weight: 800; color: var(--primary-dark);
            margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;
        }
        .pulse-dot {
            width: 8px; height: 8px; background: var(--primary); border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); } 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }

        .hero-title {
            font-weight: 900; color: var(--dark);
            font-size: clamp(2.3rem, 6.5vw, 4.4rem); line-height: 1.12;
            letter-spacing: -1.5px; margin-bottom: 1.5rem;
        }
        .hero-title .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, #0ea5e9 50%, var(--primary-dark) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            animation: gradientMove 4s ease infinite;
        }
        @keyframes gradientMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

        .hero-desc {
            font-size: clamp(1rem, 2vw, 1.125rem); color: var(--slate);
            line-height: 1.75; margin-bottom: 2rem; max-width: 580px;
            font-weight: 500;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white !important; border-radius: 50rem; padding: 1rem 1.8rem; font-weight: 700; font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 10px; border: 2px solid transparent;
            min-height: 50px;
        }
        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 18px 35px rgba(16, 185, 129, 0.4); border-color: rgba(255,255,255,0.3); }

        .btn-hero-secondary {
            background: white; color: var(--dark) !important; border-radius: 50rem; padding: 1rem 2rem; font-weight: 700;
            font-size: 1rem; transition: all 0.3s; text-decoration: none; box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            display: inline-flex; align-items: center; justify-content: center; gap: 10px; border: 1px solid rgba(0,0,0,0.08);
            min-height: 50px;
        }
        .btn-hero-secondary:hover { color: var(--primary-dark) !important; transform: translateY(-3px); box-shadow: 0 18px 35px rgba(0,0,0,0.1); border-color: var(--primary-light); }

        .hero-buttons { display: flex; flex-wrap: wrap; gap: 0.85rem; }
        .hero-buttons a { flex: 0 1 auto; }

        /* TOMBOL APK TAMBAHAN */
        .btn-hero-dark {
            background: linear-gradient(135deg, var(--dark) 0%, var(--darker) 100%);
            color: white !important; border-radius: 50rem; padding: 1rem 2rem; font-weight: 700; font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 25px rgba(15, 23, 42, 0.3);
            text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px; border: 1px solid rgba(255,255,255,0.1);
            min-height: 52px; text-align: center;
        }
        .btn-hero-dark:hover { transform: translateY(-3px); box-shadow: 0 18px 35px rgba(15, 23, 42, 0.4); border-color: rgba(255,255,255,0.2); }

        .hero-apk-wrap { max-width: 440px; margin-bottom: 2.25rem; }

        .hero-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 1rem;
            margin-top: 1.75rem;
            margin-bottom: 1.75rem;
            max-width: 650px;
        }
        .hero-highlights .highlight-card {
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(16,185,129,0.15);
            border-radius: 18px;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .hero-highlights .highlight-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(16,185,129,0.12);
            border-color: rgba(16,185,129,0.35);
        }
        .highlight-icon {
            width: 42px; height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: rgba(16,185,129,0.1);
            color: var(--primary-dark);
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        .hero-stats {
            display: flex; flex-wrap: wrap; gap: 1.25rem;
        }
        .stat-box {
            background: white; border: 1px solid rgba(15, 23, 42, 0.06);
            padding: 1rem 1.5rem; border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            display: flex; align-items: center; gap: 1rem;
        }
        .hero-stat-num { font-size: clamp(1.6rem, 4vw, 2.3rem); font-weight: 900; color: var(--dark); letter-spacing: -1px; line-height: 1.1; }
        .hero-stat-label { font-weight: 600; color: var(--slate); font-size: 0.85rem; }

        .hero-visual { position: relative; margin-top: 1rem; }
        .hero-image-main {
            border-radius: 28px; overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15), inset 0 0 0 8px rgba(255,255,255,0.4);
            transform: perspective(1000px) rotateY(-6deg) rotateX(3deg);
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; z-index: 2;
        }
        .hero-image-main:hover { transform: perspective(1000px) rotateY(0deg) rotateX(0deg); }
        .hero-image-main img { width: 100%; height: 500px; object-fit: cover; display: block; }

        .floating-card {
            position: absolute; display: flex; align-items: center; gap: 14px; z-index: 3;
            animation: floatCard 6s ease-in-out infinite;
        }
        .floating-card-1 { bottom: 40px; left: -25px; animation-delay: 0s; }
        .floating-card-2 { top: 30px; right: -15px; animation-delay: 3s; }
        @keyframes floatCard { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }

        /* ===== COMMON ===== */
        .section-label {
            display: inline-flex; font-size: 0.82rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 2px; margin-bottom: 0.8rem; color: var(--primary);
        }
        .section-title {
            font-weight: 900; color: var(--dark); font-size: clamp(1.7rem, 4.5vw, 2.7rem);
            letter-spacing: -1px; line-height: 1.25; margin-bottom: 1rem;
        }
        .section-desc {
            font-size: clamp(0.98rem, 1.5vw, 1.08rem);
            color: var(--slate);
            line-height: 1.75;
            margin: 0 auto;
            max-width: 700px;
        }
        section { scroll-margin-top: 100px; }

        /* ===== CARA KERJA ===== */
        .cara-kerja-section { padding: clamp(60px, 8vw, 100px) 0; }
        .timeline-wrapper { position: relative; margin-top: 3.5rem; }
        .timeline-line {
            position: absolute; top: 45px; left: 12%; right: 12%; height: 4px;
            background: linear-gradient(90deg, rgba(16,185,129,0.2) 50%, transparent 0);
            background-size: 18px 4px; z-index: 0;
        }
        @media (max-width: 991px) { .timeline-line { display: none; } }

        .step-card {
            background: white; border-radius: 24px; padding: 2.25rem 1.75rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; z-index: 1; height: 100%;
        }
        .step-card:hover, .step-card:focus-within { transform: translateY(-8px); box-shadow: 0 25px 50px rgba(16, 185, 129, 0.08); border-color: rgba(16,185,129,0.3); }
        .step-badge {
            position: absolute; top: 16px; right: 20px; font-size: 1.25rem; font-weight: 900;
            color: rgba(16, 185, 129, 0.2); letter-spacing: -1px; transition: color 0.3s;
        }
        .step-card:hover .step-badge { color: var(--primary); }
        .step-icon-wrap {
            width: 76px; height: 76px; border-radius: 22px; margin: 0 auto 1.3rem;
            background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            display: flex; align-items: center; justify-content: center; position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .step-card:hover .step-icon-wrap { background: var(--primary); color: white; transform: scale(1.08) rotate(4deg); box-shadow: 0 15px 30px rgba(16,185,129,0.3); }
        .step-card:hover .step-icon-wrap svg { stroke: white; }
        .step-icon-wrap svg { stroke: var(--primary); transition: 0.4s; }

        /* ===== HARGA ===== */
        .harga-section {
            background: var(--darker); padding: clamp(70px, 10vw, 110px) 0; position: relative; overflow: hidden;
        }
        .harga-bg-glow {
            position: absolute; width: 80vw; height: 80vw; background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 60%);
            top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; pointer-events: none;
        }
        .search-price-wrap {
            max-width: 480px; margin: 0 auto 2.5rem; position: relative; z-index: 2;
        }
        .search-price-input {
            width: 100%; padding: 0.9rem 1.4rem 0.9rem 3.2rem; border-radius: 50rem;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.18);
            color: white; font-size: 0.95rem; outline: none; transition: all 0.3s;
            backdrop-filter: blur(10px);
        }
        .search-price-input::placeholder { color: rgba(255,255,255,0.5); }
        .search-price-input:focus { background: rgba(255,255,255,0.14); border-color: var(--primary); box-shadow: 0 0 0 4px rgba(16,185,129,0.2); }
        .search-price-icon {
            position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,0.5); pointer-events: none;
        }
        .harga-card {
            background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 22px; padding: 2rem 1.5rem; text-align: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; z-index: 1; height: 100%;
        }
        .harga-card:hover { transform: translateY(-6px); background: rgba(255,255,255,0.06); box-shadow: 0 20px 45px rgba(0,0,0,0.5); border-color: var(--primary); }
        .harga-kategori { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); margin-bottom: 0.75rem; }
        .harga-nama { color: white; font-weight: 700; font-size: 1.15rem; margin-bottom: 0.85rem; }
        .harga-nominal { font-size: clamp(1.4rem, 3.5vw, 2rem); font-weight: 800; color: white; margin-bottom: 0.15rem; letter-spacing: -0.5px; }

        /* ===== STOK TERSEDIA ===== */
        .stok-section { padding: clamp(60px, 8vw, 100px) 0; background: white; }
        .stok-card {
            background: var(--surface); border-radius: 24px; padding: 1.5rem; border: 1px solid rgba(0,0,0,0.04);
            height: 100%; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .stok-card:hover, .stok-card:focus-within { border-color: rgba(16,185,129,0.4); box-shadow: 0 20px 40px rgba(16,185,129,0.1); transform: translateY(-6px); }

        /* ===== ARTIKEL ===== */
        .artikel-section { padding: clamp(60px, 8vw, 100px) 0; background: var(--surface); }
        .artikel-card {
            border: 1px solid rgba(0,0,0,0.04); border-radius: 24px; overflow: hidden; background: white; text-decoration: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; height: 100%;
        }
        .artikel-card:hover, .artikel-card:focus-within { transform: translateY(-8px); box-shadow: 0 25px 50px rgba(0,0,0,0.08); border-color: rgba(0,0,0,0.08); }
        .artikel-img-wrapper { height: 210px; position: relative; overflow: hidden; }
        .artikel-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .artikel-card:hover .artikel-img { transform: scale(1.08); }
        .artikel-date-badge {
            position: absolute; top: 16px; left: 16px; background: rgba(255,255,255,0.96); backdrop-filter: blur(5px);
            border-radius: 14px; padding: 8px 12px; text-align: center; font-weight: 800; line-height: 1.1; box-shadow: 0 8px 18px rgba(0,0,0,0.1);
        }
        .artikel-date-badge .day { font-size: 1.2rem; color: var(--dark); display: block; }
        .artikel-date-badge .month { font-size: 0.72rem; color: var(--primary); text-transform: uppercase; }
        .artikel-body { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; }
        .artikel-title { font-weight: 800; color: var(--dark); font-size: 1.15rem; margin-bottom: 1rem; line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.3s; }
        .artikel-card:hover .artikel-title { color: var(--primary-dark); }
        .artikel-link { margin-top: auto; color: var(--primary); font-weight: 700; display: inline-flex; align-items: center; gap: 6px; transition: 0.3s; }
        .artikel-card:hover .artikel-link { gap: 10px; }

        /* ===== TESTIMONI ===== */
        .testi-section { background: white; padding: clamp(60px, 8vw, 100px) 0; }
        .testi-card { background: var(--surface); border-radius: 24px; padding: 2rem 1.75rem; border: 1px solid rgba(0,0,0,0.03); position: relative; overflow: hidden; z-index: 1; box-shadow: 0 10px 30px rgba(0,0,0,0.02);}
        .testi-card::before { content: '"'; position: absolute; top: -10px; right: 20px; font-size: 7rem; color: rgba(16,185,129,0.06); font-family: serif; font-weight: 900; z-index: -1; line-height: 1;}
        .testi-quote { font-size: 1rem; font-style: italic; color: var(--slate); margin-bottom: 1.75rem; line-height: 1.7; position: relative; z-index: 2;}
        .testi-user { display: flex; align-items: center; gap: 14px; }
        .testi-avatar { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.08); flex-shrink: 0; }
        .testi-name { font-weight: 800; color: var(--dark); margin: 0; }
        .testi-role { font-size: 0.82rem; color: var(--slate-light); margin: 0; font-weight: 600;}

        /* ===== LOKASI ===== */
        .lokasi-section { padding: clamp(60px, 8vw, 100px) 0; background: var(--surface); }
        .map-wrapper { border-radius: 26px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.08); border: 5px solid white; }
        .map-wrapper iframe { width: 100%; min-height: 360px; aspect-ratio: 16 / 10; display: block; }

        /* ===== FOOTER & CTA ===== */
        .cta-box {
            background: linear-gradient(135deg, var(--dark) 0%, var(--darker) 100%);
            border-radius: 28px; padding: clamp(2.5rem, 6vw, 4.5rem) clamp(1.5rem, 5vw, 3.5rem); text-align: center; color: white; position: relative; overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.18); transform: translateY(50px); z-index: 10;
        }
        .cta-box::before { content: ''; position: absolute; width: 400px; height: 400px; background: var(--primary); filter: blur(120px); opacity: 0.25; top: -200px; right: -100px; border-radius: 50%; }
        .cta-title { font-size: clamp(1.6rem, 5vw, 2.4rem); }

        .footer { background: #020617; color: rgba(255,255,255,0.65); padding: clamp(80px, 15vw, 120px) 0 2rem; position: relative; }
        .footer-title { color: white; font-weight: 800; font-size: 1.05rem; margin-bottom: 1.3rem; letter-spacing: 0.5px; }
        .footer-link { color: rgba(255,255,255,0.6); text-decoration: none; display: block; margin-bottom: 0.75rem; transition: all 0.3s ease; font-weight: 500; padding: 2px 0;}
        .footer-link:hover { color: var(--primary); transform: translateX(4px); }
        .social-icons a { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.06); color: white; transition: all 0.3s ease; margin-right: 8px; }
        .social-icons a:hover { background: var(--primary); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(16,185,129,0.3); }
        .footer-newsletter-form { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .footer-newsletter-form input { flex: 1 1 180px; }
        .footer-newsletter-form button { flex: 0 0 auto; }
        .newsletter-feedback { font-size: 0.85rem; margin-top: 0.6rem; min-height: 1.2em; }
        .newsletter-feedback.success { color: #34d399; font-weight: 600; }

        /* ===== BACK TO TOP ===== */
        .back-to-top {
            position: fixed; bottom: 24px; left: 24px; z-index: 1040;
            width: 46px; height: 46px; border-radius: 50%; border: none;
            background: var(--dark); color: #fff; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); cursor: pointer;
            opacity: 0; visibility: hidden; transform: translateY(10px);
            transition: all 0.3s ease;
        }
        .back-to-top.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .back-to-top:hover { background: var(--primary-dark); }

        /* ===== RESPONSIVE MEDIA QUERIES ===== */
        @media (max-width: 991px) {
            .hero-section { padding: 130px 0 70px; text-align: center; }
            .hero-buttons { justify-content: center; }
            .hero-buttons a { flex: 1 1 auto; max-width: 280px; }
            .hero-apk-wrap { margin-left: auto; margin-right: auto; }
            .hero-highlights { grid-template-columns: 1fr; margin-left: auto; margin-right: auto; text-align: left; }
            .hero-stats { justify-content: center; }
            .hero-image-main { transform: none; margin-top: 2rem; }
            .hero-image-main img { height: 350px; }
            .floating-card-1 { bottom: 20px; left: 10px; transform: scale(0.9); }
            .floating-card-2 { top: 20px; right: 10px; transform: scale(0.9); }
            .cta-box { padding: 3rem 1.5rem; transform: translateY(40px); }
        }

        @media (max-width: 575px) {
            .hero-section { padding: 115px 0 60px; }
            .hero-image-main img { height: 260px; }
            .hero-buttons { flex-direction: column; align-items: center; width: 100%; }
            .hero-buttons a { width: 100%; max-width: 100%; }
            .hero-stats { flex-direction: column; gap: 0.85rem !important; align-items: stretch; }
            .stat-box { justify-content: center; }
            .back-to-top { left: 16px; bottom: 16px; width: 42px; height: 42px; }
            .floating-card { display: none; }
        }

        @media (max-width: 380px) {
            .hero-title { letter-spacing: -0.5px; }
            .navbar-brand-custom { font-size: 1.1rem; }
            .brand-logo { width: 36px; height: 36px; }
            .hero-image-main img { height: 220px; }
        }

        @media (max-width: 340px) {
            .navbar-brand-custom span { display: none; }
        }

        /* ===== AI CHATBOT WIDGET ===== */
        .chatbot-container {
            position: fixed; bottom: 24px; right: 24px; z-index: 1050;
            display: flex; flex-direction: column; align-items: flex-end; gap: 12px;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        .chatbot-tooltip {
            background: white; color: var(--dark); padding: 10px 16px; border-radius: 16px; font-size: 0.85rem;
            font-weight: 700; box-shadow: 0 15px 35px rgba(0,0,0,0.12); position: relative; transform-origin: bottom right;
            animation: bounceTooltip 2.5s infinite; cursor: pointer; border: 1px solid rgba(0,0,0,0.06);
        }
        .chatbot-tooltip::after {
            content: ''; position: absolute; bottom: -6px; right: 24px; width: 14px; height: 14px;
            background: white; transform: rotate(45deg); border-right: 1px solid rgba(0,0,0,0.06); border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        @keyframes bounceTooltip { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

        .chatbot-toggler {
            width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            cursor: pointer; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4); border: 2px solid rgba(255,255,255,0.25); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }
        .chatbot-toggler:hover { transform: scale(1.08) rotate(5deg); box-shadow: 0 15px 32px rgba(16, 185, 129, 0.5); }
        .chatbot-toggler .close-icon { display: none; }
        .show-chatbot .chatbot-toggler .chat-icon { display: none; }
        .show-chatbot .chatbot-toggler .close-icon { display: block; }
        .show-chatbot .chatbot-tooltip { display: none; }

        .chatbot-window {
            position: absolute; bottom: 76px; right: 0; width: 360px; max-width: calc(100vw - 36px);
            background: white; border-radius: 24px; overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.18); transform: scale(0.5); opacity: 0; pointer-events: none; transform-origin: bottom right;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid rgba(0,0,0,0.06);
        }
        .show-chatbot .chatbot-window { transform: scale(1); opacity: 1; pointer-events: auto; }

        .chat-header { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 16px 18px; display: flex; align-items: center; gap: 12px; color: white; }
        .chat-header img { width: 42px; height: 42px; border-radius: 50%; background: white; padding: 2px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1); flex-shrink: 0;}
        .chat-title { font-weight: 800; font-size: 1.05rem; line-height: 1.2; margin: 0; }
        .chat-status { font-size: 0.82rem; opacity: 0.9; display: flex; align-items: center; gap: 6px; font-weight: 500;}
        .chat-status::before { content: ''; width: 8px; height: 8px; background: #34d399; border-radius: 50%; animation: pulse-status 2s infinite; box-shadow: 0 0 0 rgba(52, 211, 153, 0.4);}
        @keyframes pulse-status { 0% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7); } 70% { box-shadow: 0 0 0 6px rgba(52, 211, 153, 0); } 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0); } }

        .chat-body { height: min(380px, 55vh); overflow-y: auto; padding: 18px; background: #f8fafc; display: flex; flex-direction: column; gap: 14px; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; }

        .message { max-width: 86%; font-size: 0.92rem; line-height: 1.5; padding: 12px 16px; position: relative; word-wrap: break-word; }
        .bot-message { background: white; color: var(--slate); border-radius: 4px 18px 18px 18px; border: 1px solid #e2e8f0; align-self: flex-start; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
        .user-message { background: var(--primary); color: white; border-radius: 18px 18px 4px 18px; align-self: flex-end; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }

        .quick-replies { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
        .quick-btn { background: white; border: 1px solid var(--primary); color: var(--primary); padding: 8px 13px; border-radius: 50rem; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); min-height: 38px;}
        .quick-btn:hover { background: var(--primary); color: white; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(16,185,129,0.2);}

        .chat-input-area { padding: 12px 14px; background: white; border-top: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px; }
        .chat-input { flex-grow: 1; border: 1px solid #e2e8f0; background: #f8fafc; padding: 11px 16px; border-radius: 50rem; font-size: 0.92rem; outline: none; transition: all 0.3s ease; min-width: 0; }
        .chat-input:focus { box-shadow: 0 0 0 3px rgba(16,185,129,0.1); border-color: var(--primary-light); background: white; }
        .send-btn { background: var(--primary); color: white; border: none; width: 42px; height: 42px; min-width: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 10px rgba(16,185,129,0.2);}
        .send-btn:hover { background: var(--primary-dark); transform: scale(1.05); }
        .send-btn svg { width: 18px; height: 18px; transform: translateX(-1px) translateY(1px); }

        .typing-indicator { display: none; gap: 5px; padding: 12px 16px; background: white; border-radius: 4px 18px 18px 18px; align-self: flex-start; border: 1px solid #e2e8f0; }
        .typing-indicator span { width: 7px; height: 7px; background: #cbd5e1; border-radius: 50%; animation: typing 1.4s infinite ease-in-out both; }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing { 0%, 80%, 100% { transform: scale(0); opacity: 0.5; } 40% { transform: scale(1); opacity: 1; background: var(--primary); } }

        @media (max-width: 576px) {
            .chatbot-container { right: 14px; bottom: 14px; }
            .chatbot-toggler { width: 54px; height: 54px; }
            .chatbot-tooltip { max-width: 65vw; font-size: 0.8rem; padding: 8px 12px; }
            .chatbot-window { width: calc(100vw - 28px); bottom: 68px; right: 0; max-height: 75vh; }
            .chat-body { height: min(340px, 46vh); }
        }
        @media (max-height: 700px) {
            .chat-body { height: 42vh; }
        }

        /* ===== INTERACTIVE CHAT EXTENSIONS ===== */
        .chat-header-actions { display: flex; gap: 8px; align-items: center; margin-left: auto; }
        .chat-icon-btn { background: rgba(255,255,255,0.18); border: none; color: white; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; }
        .chat-icon-btn:hover { background: rgba(255,255,255,0.32); transform: scale(1.05); }
        .chat-card-mini { background: #f1f5f9; border-radius: 14px; padding: 12px; margin-top: 10px; border: 1px solid #e2e8f0; font-size: 0.85rem; color: var(--dark); }
        .chat-btn-action { display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: var(--primary); color: white !important; padding: 7px 14px; border-radius: 50rem; text-decoration: none; font-weight: 700; font-size: 0.82rem; margin-top: 10px; transition: 0.2s; border: none; cursor: pointer; width: 100%; box-shadow: 0 4px 10px rgba(16,185,129,0.2); }
        .chat-btn-action:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(16,185,129,0.3); }
        .chat-calc-box { background: white; border: 1px solid #cbd5e1; border-radius: 14px; padding: 12px; margin-top: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .chat-calc-select, .chat-calc-input { width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; margin-bottom: 8px; outline: none; background: #f8fafc; }
        .chat-calc-select:focus, .chat-calc-input:focus { border-color: var(--primary); background: white; }
    </style>
</head>
<body>
    <a href="#beranda" class="skip-link">Langsung ke konten utama</a>

    <div class="bg-mesh">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
    </div>

    <nav class="navbar navbar-expand-lg fixed-top navbar-custom" id="mainNav">
        <div class="container">
            <a class="navbar-brand-custom" href="#beranda">
                <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo Bank Sampah Subang" class="brand-logo">
                <span>Bank Sampah Subang</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link-custom active" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="#cara-kerja">Cara Kerja</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="#harga">Harga Beli</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="{{ route('publik.stok') ?? '#' }}">Stok Tersedia</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="#artikel">Edukasi</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="#lokasi">Lokasi</a></li>
                </ul>
                <div class="mt-2 mt-lg-0 text-center">
                    <a href="{{ route('admin.login') ?? '#' }}" class="btn-masuk">
                        Masuk Sistem
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-12 col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="hero-badge">
                        <span class="pulse-dot"></span> Ekosistem Digital Terpadu
                    </div>
                    <h1 class="hero-title">
                        Jadikan Sampahmu<br>
                        <span class="gradient-text">Lebih Bernilai.</span>
                    </h1>
                    <p class="hero-desc mx-auto mx-lg-0">
                        Ubah kebiasaan membuang sampah menjadi menabung. Dapatkan penghasilan tambahan dan bantu wujudkan Kabupaten Subang yang bersih dan lestari.
                    </p>

                    <div class="hero-buttons mb-3">
                        <a href="#harga" class="btn-hero-primary">
                            Cek Harga Hari Ini
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                        <a href="#cara-kerja" class="btn-hero-secondary">Pelajari Caranya</a>
                    </div>

                    <div class="hero-highlights mb-4">
                        <div class="highlight-card">
                            <span class="highlight-icon">💚</span>
                            <div>
                                <div class="fw-bold text-dark">Menabung dari Sampah</div>
                                <p class="text-muted small mb-0">Dapatkan saldo setiap kali setor sampah bersih dan terpilah.</p>
                            </div>
                        </div>
                        <div class="highlight-card">
                            <span class="highlight-icon">🔄</span>
                            <div>
                                <div class="fw-bold text-dark">Transparan & Aman</div>
                                <p class="text-muted small mb-0">Riwayat saldo dan harga bisa dicek langsung di aplikasi.</p>
                            </div>
                        </div>
                        <div class="highlight-card">
                            <span class="highlight-icon">📍</span>
                            <div>
                                <div class="fw-bold text-dark">Akses Mudah</div>
                                <p class="text-muted small mb-0">Cek lokasi dan jadwal operasional sebelum datang.</p>
                            </div>
                        </div>
                    </div>

                    <div class="hero-apk-wrap">
                        <a href="{{ asset('download/banksampahdigital.apk') }}" download="BankSampahDigital.apk" class="btn-hero-dark w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 10l0 6"></path>
                                <path d="M20 10l0 6"></path>
                                <path d="M7 9h10v8a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-8a5 5 0 0 1 10 0"></path>
                                <path d="M8 3l1 2"></path>
                                <path d="M16 3l-1 2"></path>
                                <path d="M9 18l0 3"></path>
                                <path d="M15 18l0 3"></path>
                            </svg>
                            Download Aplikasi Mobile (APK)
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-box">
                            <div class="icon-box-primary" style="width:42px; height:42px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <div class="hero-stat-num"><span class="counter" data-target="{{ $totalNasabah ?? 0 }}">0</span>+</div>
                                <div class="hero-stat-label">Nasabah Aktif</div>
                            </div>
                        </div>
                        <div class="stat-box">
                            <div class="icon-box-success" style="width:42px; height:42px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            </div>
                            <div>
                                <div class="hero-stat-num"><span class="counter" data-target="{{ $totalSampah ?? 0 }}">0</span><span class="fs-6 ms-1">kg</span></div>
                                <div class="hero-stat-label">Sampah Dikelola</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="200">
                    <div class="hero-visual">
                        <div class="hero-image-main">
                            <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=2070&auto=format&fit=crop" alt="Ilustrasi kegiatan daur ulang dan pemilahan sampah" fetchpriority="high">
                        </div>
                        <div class="floating-card glass-card floating-card-1">
                            <div class="icon-box-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <div>
                                <div class="fw-bolder text-dark" style="font-size: 0.95rem;">Tabungan Masuk!</div>
                                <div class="small text-muted fw-semibold">+Rp 25.000 (Plastik)</div>
                            </div>
                        </div>
                        <div class="floating-card glass-card floating-card-2">
                            <div class="icon-box-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div>
                                <div class="fw-bolder text-dark" style="font-size: 0.95rem;">Sistem Terverifikasi</div>
                                <div class="small text-muted fw-semibold">Aman & Transparan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="cara-kerja-section bg-white">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="section-label">Langkah Mudah</div>
                <h2 class="section-title">Cara Mulai Menabung</h2>
                <p class="section-desc">Hanya dengan 4 langkah sederhana, Anda bisa mulai menukar sampah rumah tangga menjadi pundi-pundi rupiah.</p>
            </div>
            <div class="timeline-wrapper">
                <div class="timeline-line"></div>
                <div class="row g-4">
                    <div class="col-12 col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="step-card text-center">
                            <span class="step-badge">01</span>
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                            </div>
                            <h3 class="fw-bolder text-dark fs-5 mb-2">Daftar Akun</h3>
                            <p class="text-muted lh-base font-medium mb-0">Daftarkan diri Anda sebagai nasabah Bank Sampah di kantor atau via aplikasi.</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="step-card text-center">
                            <span class="step-badge">02</span>
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg>
                            </div>
                            <h3 class="fw-bolder text-dark fs-5 mb-2">Pilah Sampah</h3>
                            <p class="text-muted lh-base font-medium mb-0">Pisahkan sampah organik dan anorganik (plastik, logam, kertas) di rumah.</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="step-card text-center">
                            <span class="step-badge">03</span>
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                            </div>
                            <h3 class="fw-bolder text-dark fs-5 mb-2">Setor & Timbang</h3>
                            <p class="text-muted lh-base font-medium mb-0">Bawa sampah ke titik kumpul atau kantor kami untuk ditimbang petugas secara akurat.</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                        <div class="step-card text-center">
                            <span class="step-badge">04</span>
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            </div>
                            <h3 class="fw-bolder text-dark fs-5 mb-2">Cairkan Saldo</h3>
                            <p class="text-muted lh-base font-medium mb-0">Saldo langsung masuk ke tabungan digital dan siap ditarik tunai kapan saja.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="harga" class="harga-section">
        <div class="harga-bg-glow"></div>
        <div class="container position-relative z-1">
            <div class="text-center mb-4" data-aos="fade-up">
                <div class="section-label" style="color:var(--primary-light);">Transparansi Harga</div>
                <h2 class="section-title text-white">Daftar Harga Beli Terkini</h2>
                <p class="section-desc mx-auto text-white-50" style="max-width:620px;">Harga otomatis diperbarui dari sistem. Pastikan sampah dalam kondisi bersih dan kering untuk mendapatkan nilai tukar terbaik.</p>
            </div>

            @if(isset($jenisSampah) && count($jenisSampah) > 0)
            <div class="search-price-wrap" data-aos="fade-up">
                <label for="priceSearchInput" class="visually-hidden">Cari jenis sampah</label>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="search-price-icon"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="priceSearchInput" class="search-price-input" placeholder="Cari nama atau kategori sampah (cth: Kardus, Besi, Plastik)...">
            </div>
            @endif

            <div class="row g-4 justify-content-center" id="priceGrid">
                @if(isset($jenisSampah) && count($jenisSampah) > 0)
                    @foreach($jenisSampah as $index => $sampah)
                    <div class="col-12 col-sm-6 col-lg-3 price-card-col" data-name="{{ strtolower($sampah->nama) }}" data-category="{{ strtolower($sampah->kategori ?? 'umum') }}" data-aos="zoom-in" data-aos-delay="{{ min(($index * 80), 400) }}">
                        <div class="harga-card">
                            <div class="harga-kategori">{{ $sampah->kategori ?? 'Umum' }}</div>
                            <div class="harga-nama">{{ $sampah->nama }}</div>
                            <div class="harga-nominal">Rp {{ number_format($sampah->harga_per_kg, 0, ',', '.') }}</div>
                            <div class="harga-satuan text-white-50 small fw-semibold">/ Kilogram</div>
                        </div>
                    </div>
                    @endforeach
                    <div class="col-12 text-center text-white-50 py-5 d-none" id="noPriceResult">
                        <p class="fs-5 mb-1 fw-semibold">Pencarian tidak ditemukan.</p>
                        <p class="small opacity-75">Coba kata kunci lain seperti "kertas" atau "botol".</p>
                    </div>
                @else
                    <div class="col-12 text-center text-white-50 py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3 opacity-50"><rect x="3" y="6" width="18" height="14" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h4"/></svg>
                        <p class="mb-0 fw-semibold fs-5">Daftar harga sedang diperbarui.</p>
                        <p class="mb-0 small opacity-75">Silakan cek kembali beberapa saat lagi.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- STOK TERSEDIA (UNTUK PIHAK KETIGA) --}}
    @if(isset($stokTersedia) && $stokTersedia->count() > 0)
    <section id="stok" class="stok-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="section-label">Pengepul & Partner</div>
                <h2 class="section-title">Stok Sampah Siap Dijual</h2>
                <p class="text-muted mx-auto" style="max-width: 580px;">Sampah terpilah dan siap dibeli dalam partai besar. Hubungi kami untuk kerja sama pengambilan secara rutin.</p>
                <a href="{{ route('publik.stok') ?? '#' }}" class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold mt-3 transition hover-scale">
                    Lihat Semua Stok
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="ms-1 align-middle"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
            <div class="row g-4">
                @foreach($stokTersedia as $stok)
                <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up">
                    <a href="{{ route('publik.stok.detail', $stok->slug ?? '') ?? '#' }}" class="text-decoration-none">
                        <div class="stok-card d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                <div>
                                    <h3 class="fw-bold text-dark mb-1 fs-5">{{ $stok->jenisSampah->nama ?? '-' }}</h3>
                                    <span class="badge rounded-pill" style="background: rgba(139,92,246,0.1); color: #7c3aed; border: 1px solid rgba(139,92,246,0.2); font-size: 0.7rem; padding: 4px 10px; font-weight:800;">PRESSED</span>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <div class="fw-bold fs-4" style="color: #10b981; letter-spacing: -0.5px;">Rp {{ number_format($stok->harga_jual_per_kg ?? 0, 0, ',', '.') }}</div>
                                    <div class="text-muted small fw-semibold">/kg</div>
                                </div>
                            </div>
                            @if(!empty($stok->gambar))
                            <div class="mb-4 mt-2 rounded-4 overflow-hidden shadow-sm" style="max-height: 160px;">
                                <img src="{{ asset('storage/' . $stok->gambar) }}" alt="Foto {{ $stok->jenisSampah->nama ?? '' }}" class="w-100" style="object-fit: cover; height: 160px;" loading="lazy">
                            </div>
                            @endif
                            <div class="mb-3 mt-auto">
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted fw-semibold">Tersedia</span>
                                    <span class="fw-bold text-dark">{{ number_format($stok->stok_tersisa_kg ?? 0, 1, ',', '.') }} kg</span>
                                </div>
                                @php $pct = (isset($stok->stok_masuk_kg) && $stok->stok_masuk_kg > 0) ? min(($stok->stok_tersisa_kg / $stok->stok_masuk_kg) * 100, 100) : 0; @endphp
                                <div class="progress" style="height: 6px; border-radius: 99px;">
                                    <div class="progress-bar" style="width: {{ $pct }}%; background: linear-gradient(90deg, #10b981, #34d399);"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-light flex-wrap gap-2">
                                <span class="text-muted small fw-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1 align-middle"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    {{ !empty($stok->tanggal_masuk) ? \Carbon\Carbon::parse($stok->tanggal_masuk)->locale('id')->format('d M Y') : '-' }}
                                </span>
                                <span class="badge rounded-pill" style="background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.2); font-size: 0.7rem; font-weight:800;">TERSEDIA</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section id="artikel" class="artikel-section bg-surface">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3" data-aos="fade-right">
                <div>
                    <div class="section-label">Pojok Literasi</div>
                    <h2 class="section-title">Edukasi & Berita Terbaru</h2>
                    <p class="section-desc mb-0">Tingkatkan wawasan seputar kelestarian lingkungan dan inovasi daur ulang sampah.</p>
                </div>
                <a href="#" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold mb-2 transition hover-scale">Lihat Semua Berita</a>
            </div>

            <div class="row g-4">
                @if(isset($artikels) && count($artikels) > 0)
                    @foreach($artikels as $index => $artikel)
                    <div class="col-12 col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                        <a href="{{ route('publik.artikel.baca', $artikel->slug ?? '') ?? '#' }}" class="artikel-card">
                            <div class="artikel-img-wrapper">
                                <img src="{{ !empty($artikel->gambar) ? asset('storage/' . $artikel->gambar) : 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop' }}"
                                     onerror="this.src='https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop'"
                                     class="artikel-img" alt="{{ $artikel->judul ?? 'Artikel' }}" loading="lazy">
                                <div class="artikel-date-badge">
                                    <span class="day">{{ !empty($artikel->created_at) ? $artikel->created_at->format('d') : '-' }}</span>
                                    <span class="month">{{ !empty($artikel->created_at) ? $artikel->created_at->format('M') : '-' }}</span>
                                </div>
                            </div>
                            <div class="artikel-body">
                                <span class="badge bg-light text-primary border border-light mb-3 align-self-start fw-bold px-3 py-2">{{ ucfirst($artikel->kategori ?? 'Umum') }}</span>
                                <h3 class="artikel-title">{{ $artikel->judul ?? 'Judul Artikel' }}</h3>
                                <div class="artikel-link mt-auto">
                                    Lanjut Membaca <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                @else
                    <div class="col-12 text-center text-muted py-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3 opacity-50"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        <p class="mb-0 fw-semibold fs-5">Belum ada artikel yang dipublikasikan.</p>
                        <p class="mb-0 small opacity-75">Nantikan konten edukasi dan berita terbaru dari kami di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="testi-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Apa Kata Warga Subang?</h2>
                <p class="section-desc mx-auto text-slate fw-medium">Pengalaman inspiratif nasabah setelah bergabung menjadi bagian dari perubahan.</p>
            </div>
            <div class="row g-4">
                <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testi-card h-100 d-flex flex-column justify-content-between">
                        <p class="testi-quote">"Sangat membantu! Selain lingkungan rumah jadi bersih dari botol plastik, saldonya lumayan buat nambah uang belanja bulanan."</p>
                        <div class="testi-user">
                            <img src="https://ui-avatars.com/api/?name=Ibu+Siti&background=10b981&color=fff" class="testi-avatar" alt="Foto Ibu Siti" loading="lazy">
                            <div>
                                <h4 class="testi-name fs-6">Ibu Siti</h4>
                                <p class="testi-role">Nasabah Pasirkareumbi</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testi-card h-100 d-flex flex-column justify-content-between">
                        <p class="testi-quote">"Sistemnya sangat transparan. Saya bisa ngecek saldo dan history setoran langsung dari HP. Penarikan dananya juga cepat diproses."</p>
                        <div class="testi-user">
                            <img src="https://ui-avatars.com/api/?name=Pak+Budi&background=3b82f6&color=fff" class="testi-avatar" alt="Foto Budi Santoso" loading="lazy">
                            <div>
                                <h4 class="testi-name fs-6">Budi Santoso</h4>
                                <p class="testi-role">Nasabah Sukadana</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testi-card h-100 d-flex flex-column justify-content-between">
                        <p class="testi-quote">"Edukasi dari Bank Sampah bikin sadar kalau kardus dan kertas koran yang biasa dibakar ternyata punya nilai ekonomi tinggi."</p>
                        <div class="testi-user">
                            <img src="https://ui-avatars.com/api/?name=Andi+W&background=f59e0b&color=fff" class="testi-avatar" alt="Foto Andi Wijaya" loading="lazy">
                            <div>
                                <h4 class="testi-name fs-6">Andi Wijaya</h4>
                                <p class="testi-role">Ketua RT 02</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="lokasi" class="lokasi-section">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-12 col-lg-5" data-aos="fade-right">
                    <div class="section-label">Kunjungi Kami</div>
                    <h2 class="section-title">Kantor Pelayanan Bank Sampah</h2>
                    <p class="section-desc mb-4 mb-lg-5 text-slate fw-medium">Tim kami siap melayani penimbangan sampah dan pencairan dana Anda setiap hari kerja.</p>

                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="icon-box-large">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-dark fs-5">Alamat Utama</h3>
                            <p class="text-slate mb-0 fw-medium">Kabupaten Subang, Jawa Barat</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <div class="icon-box-large">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1 text-dark fs-5">Jam Operasional</h3>
                            <p class="text-slate mb-0 fw-medium">Senin - Jumat: 08:00 - 15:00 WIB</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-7" data-aos="fade-left">
                    <div class="map-wrapper">
                        <iframe src="https://maps.google.com/maps?q=Kabupaten%20Subang,%20Jawa%20Barat&t=&z=11&ie=UTF8&iwloc=&output=embed" title="Peta lokasi Bank Sampah Subang" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div style="background: var(--surface);">
        <div class="container">
            <div class="cta-box" data-aos="zoom-in">
                <h2 class="cta-title fw-bolder mb-3">Siap Wujudkan Lingkungan Bersih?</h2>
                <p class="cta-desc mx-auto mb-4 text-white-50 fs-5" style="max-width: 620px;">Pendaftaran gratis dan mudah. Mulai kelola sampahmu hari ini dan nikmati manfaat ekonominya untuk keluarga.</p>

                <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
                    <a href="{{ route('admin.login') ?? '#' }}" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-success d-inline-flex align-items-center justify-content-center gap-2 shadow-lg" style="transition: transform 0.3s; min-height: 50px;">
                        Gabung Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                    <a href="{{ asset('download/banksampahdigital.apk') }}" download="BankSampahDigital.apk" class="btn border border-light border-opacity-25 text-white rounded-pill px-5 py-3 fw-bold d-inline-flex align-items-center justify-content-center gap-2" style="background: rgba(255,255,255,0.08); transition: all 0.3s; min-height: 50px;" onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 10l0 6"></path><path d="M20 10l0 6"></path><path d="M7 9h10v8a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-8a5 5 0 0 1 10 0"></path><path d="M8 3l1 2"></path><path d="M16 3l-1 2"></path><path d="M9 18l0 3"></path><path d="M15 18l0 3"></path></svg>
                        Download APK
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="col-12 col-lg-4">
                    <h3 class="fw-bold d-flex align-items-center gap-3 mb-4 text-white fs-5">
                        <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo Bank Sampah Subang" style="width:40px; border-radius:10px;">
                        Bank Sampah Subang
                    </h3>
                    <p class="footer-desc mb-4" style="line-height: 1.8;">Sistem informasi pengelolaan bank sampah digital yang transparan, mudah, dan menguntungkan masyarakat Subang.</p>
                    <div class="social-icons">
                        <a href="#" aria-label="Kunjungi Twitter/X kami"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" aria-label="Kunjungi Instagram kami"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h4 class="footer-title fs-6">Jelajahi</h4>
                    <a href="#beranda" class="footer-link">Beranda</a>
                    <a href="#cara-kerja" class="footer-link">Cara Kerja</a>
                    <a href="#harga" class="footer-link">Daftar Harga</a>
                    <a href="#artikel" class="footer-link">Pojok Edukasi</a>
                </div>
                <div class="col-lg-2 col-6">
                    <h4 class="footer-title fs-6">Layanan</h4>
                    <a href="#" class="footer-link">Setor Sampah</a>
                    <a href="#" class="footer-link">Tarik Saldo</a>
                    <a href="#" class="footer-link">Bantuan (FAQ)</a>
                    <a href="{{ route('admin.login') ?? '#' }}" class="footer-link">Masuk Admin</a>
                </div>
                <div class="col-12 col-lg-4">
                    <h4 class="footer-title fs-6">Berlangganan Info</h4>
                    <p class="footer-desc mb-3">Dapatkan info kenaikan harga sampah dan edukasi terbaru langsung di email Anda.</p>
                    <form class="footer-newsletter-form" id="newsletterForm">
                        <label for="newsletter-email" class="visually-hidden">Alamat email</label>
                        <input type="email" id="newsletter-email" class="form-control bg-dark border-secondary text-white shadow-none" placeholder="Alamat email Anda" required style="border-radius: 50rem; padding: 0.75rem 1.2rem;">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Kirim</button>
                    </form>
                    <p class="newsletter-feedback" id="newsletterFeedback" role="status" aria-live="polite"></p>
                </div>
            </div>
            <div class="footer-bottom text-center pt-4 border-top border-secondary border-opacity-25">
                <p class="mb-0 fw-medium opacity-75">© {{ date('Y') }} Bank Sampah Digital Subang. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <button class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
    </button>

    <div class="chatbot-container">
        <div class="chatbot-tooltip" onclick="document.querySelector('.chatbot-toggler').click();">
            Ada pertanyaan? Nura siap bantu! 👋
        </div>
        <button class="chatbot-toggler" aria-label="Buka chat dengan Nura AI Asisten" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" class="chat-icon" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="close-icon" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <div class="chatbot-window" role="dialog" aria-label="Jendela chat Nura AI Asisten" aria-modal="false" inert>
        <div class="chat-header">
            <img src="{{ asset('image/nura.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=Nura&background=10b981&color=fff'" alt="Avatar Nura AI">
            <div>
                <h4 class="chat-title">Nura AI Asisten</h4>
                <div class="chat-status">Online siap membantu</div>
            </div>
            <div class="chat-header-actions">
                <button class="chat-icon-btn" id="soundToggleBtn" title="Aktifkan/Matikan Suara" aria-label="Toggle suara chat">
                    <svg id="soundIconOn" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
                    <svg id="soundIconOff" class="d-none" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>
                </button>
                <button class="chat-icon-btn" id="resetChatBtn" title="Bersihkan Percakapan" aria-label="Reset chat">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            </div>
        </div>
        <div class="chat-body" id="chat-body">
            <div class="quick-replies" id="quick-replies">
                <button class="quick-btn" onclick="sendQuickReply('🧮 Simulasikan Saldo')">🧮 Simulasikan Saldo</button>
                <button class="quick-btn" onclick="sendQuickReply('Cek Harga')">💰 Cek Harga</button>
                <button class="quick-btn" onclick="sendQuickReply('Cara Daftar')">🚀 Cara Daftar</button>
                <button class="quick-btn" onclick="sendQuickReply('Jam Buka')">⏰ Jam Buka</button>
            </div>
            <div class="typing-indicator" id="typing-indicator">
                <span></span><span></span><span></span>
            </div>
        </div>
        <div class="chat-input-area">
            <label for="chat-input" class="visually-hidden">Ketik pesan</label>
            <input type="text" id="chat-input" class="chat-input" placeholder="Tanya Nura (cth: harga kardus, cara setor)..." autocomplete="off">
            <button class="send-btn" id="send-btn" aria-label="Kirim pesan">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z"/></svg>
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 60, duration: 700, disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches });

        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => { nav.classList.toggle('scrolled', window.scrollY > 50); }, { passive: true });

        /* Auto-close mobile navbar menu after clicking a link */
        const navbarCollapseEl = document.getElementById('navbarNav');
        const bsCollapse = navbarCollapseEl ? new bootstrap.Collapse(navbarCollapseEl, { toggle: false }) : null;
        document.querySelectorAll('#navbarNav .nav-link-custom, #navbarNav .btn-masuk').forEach(link => {
            link.addEventListener('click', () => {
                if (navbarCollapseEl.classList.contains('show') && bsCollapse) {
                    bsCollapse.hide();
                }
            });
        });

        const sections = document.querySelectorAll('section[id]');
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY + 200;
            sections.forEach(sec => {
                const top = sec.offsetTop, height = sec.offsetHeight, id = sec.getAttribute('id');
                const link = document.querySelector(`.nav-link-custom[href="#${id}"]`);
                if (link) {
                    if (scrollY >= top && scrollY < top + height) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                }
            });
        }, { passive: true });

        /* Fitur Live Search untuk Daftar Harga */
        const priceSearchInput = document.getElementById('priceSearchInput');
        const priceCols = document.querySelectorAll('.price-card-col');
        const noPriceResult = document.getElementById('noPriceResult');

        if (priceSearchInput && priceCols.length > 0) {
            priceSearchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                let matchCount = 0;

                priceCols.forEach(col => {
                    const name = col.getAttribute('data-name') || '';
                    const category = col.getAttribute('data-category') || '';
                    if (name.includes(query) || category.includes(query)) {
                        col.classList.remove('d-none');
                        matchCount++;
                    } else {
                        col.classList.add('d-none');
                    }
                });

                if (noPriceResult) {
                    if (matchCount === 0) {
                        noPriceResult.classList.remove('d-none');
                    } else {
                        noPriceResult.classList.add('d-none');
                    }
                }
            });
        }

        /* Back to top button */
        const backToTopBtn = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            backToTopBtn.classList.toggle('show', window.scrollY > 500);
        }, { passive: true });
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
        });

        document.querySelectorAll('.counter').forEach(counter => {
            counter.dataset.raw = '0';
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.dataset.raw;
                const inc = target / 40;
                if (count < target && target > 0 && !reduceMotion) {
                    const next = Math.ceil(count + inc);
                    counter.dataset.raw = String(next);
                    counter.innerText = next.toLocaleString('id-ID');
                    setTimeout(updateCount, 40);
                } else {
                    counter.dataset.raw = String(target);
                    counter.innerText = target.toLocaleString('id-ID');
                }
            };
            let observer = new IntersectionObserver(e => { if(e[0].isIntersecting){ updateCount(); observer.disconnect(); } }, { threshold: 0.5 });
            observer.observe(counter);
        });

        /* =======================================
           LOGIKA INTERAKTIF NURA AI (CHATBOT)
           ======================================= */
        const chatbotToggler = document.querySelector(".chatbot-toggler");
        const chatbotWindow = document.querySelector(".chatbot-window");
        const chatBody = document.getElementById("chat-body");
        const chatInput = document.getElementById("chat-input");
        const sendBtn = document.getElementById("send-btn");
        const typingIndicator = document.getElementById("typing-indicator");
        const quickReplies = document.getElementById("quick-replies");
        const soundToggleBtn = document.getElementById("soundToggleBtn");
        const soundIconOn = document.getElementById("soundIconOn");
        const soundIconOff = document.getElementById("soundIconOff");
        const resetChatBtn = document.getElementById("resetChatBtn");

        let audioEnabled = true;

        /* Synthesized Web Audio Tone for Bot Replies */
        const playPopSound = () => {
            if (!audioEnabled) return;
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5 note
                osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.1); // A5 note
                gain.gain.setValueAtTime(0.08, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
            } catch (e) {
                /* Audio context not allowed or blocked by browser */
            }
        };

        soundToggleBtn.addEventListener('click', () => {
            audioEnabled = !audioEnabled;
            if (audioEnabled) {
                soundIconOn.classList.remove('d-none');
                soundIconOff.classList.add('d-none');
                playPopSound();
            } else {
                soundIconOn.classList.add('d-none');
                soundIconOff.classList.remove('d-none');
            }
        });

        const getGreetingTime = () => {
            const hour = new Date().getHours();
            if (hour >= 4 && hour < 11) return "Pagi";
            if (hour >= 11 && hour < 15) return "Siang";
            if (hour >= 15 && hour < 18) return "Sore";
            return "Malam";
        };

        const initChat = () => {
            document.querySelectorAll('.message').forEach(e => e.remove());
            const welcomeMsg = document.createElement("div");
            welcomeMsg.classList.add("message", "bot-message", "shadow-sm");
            welcomeMsg.innerHTML = `Halo Kak! Selamat ${getGreetingTime()}! 👋<br>Saya Nura, asisten cerdas Bank Sampah Subang. Kakak bisa klik tombol di bawah atau ketik langsung pertanyaan seputar layanan kami! ✨`;

            chatBody.insertBefore(welcomeMsg, quickReplies);
            quickReplies.style.display = "flex";
            chatBody.scrollTop = 0;
        };

        resetChatBtn.addEventListener('click', () => {
            initChat();
        });

        const closeChatbot = () => {
            document.body.classList.remove("show-chatbot");
            chatbotToggler.setAttribute('aria-expanded', 'false');
            chatbotWindow.setAttribute('inert', '');
            chatbotToggler.focus({ preventScroll: true });
        };

        const openChatbot = () => {
            document.body.classList.add("show-chatbot");
            chatbotToggler.setAttribute('aria-expanded', 'true');
            chatbotWindow.removeAttribute('inert');
            if (document.querySelectorAll('.message').length === 0) initChat();
            setTimeout(() => chatInput && chatInput.focus({ preventScroll: true }), 350);
        };

        chatbotToggler.addEventListener("click", () => {
            const isShowing = document.body.classList.contains("show-chatbot");
            if (isShowing) {
                closeChatbot();
            } else {
                openChatbot();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.body.classList.contains('show-chatbot')) {
                closeChatbot();
            }
        });

        const chatbotTooltipEl = document.querySelector('.chatbot-tooltip');
        if (chatbotTooltipEl) {
            setTimeout(() => { chatbotTooltipEl.style.animation = 'none'; }, 12000);
        }

        const appendMessage = (text, sender) => {
            const msgDiv = document.createElement("div");
            msgDiv.classList.add("message", sender === "user" ? "user-message" : "bot-message");
            msgDiv.innerHTML = text;
            chatBody.insertBefore(msgDiv, typingIndicator);
            chatBody.scrollTop = chatBody.scrollHeight;
            if (sender === "bot") playPopSound();
        };

        /* Dynamic Interactive Component Actions */
        window.scrollToPriceAndSearch = () => {
            closeChatbot();
            const hargaSec = document.getElementById('harga');
            if (hargaSec) {
                hargaSec.scrollIntoView({ behavior: 'smooth' });
                setTimeout(() => {
                    const pInput = document.getElementById('priceSearchInput');
                    if (pInput) pInput.focus();
                }, 800);
            }
        };

        window.scrollToSection = (secId) => {
            closeChatbot();
            const sec = document.getElementById(secId);
            if (sec) sec.scrollIntoView({ behavior: 'smooth' });
        };

        window.runChatSimulation = (formEl) => {
            const typeSelect = formEl.querySelector('.chat-calc-select');
            const kgInput = formEl.querySelector('.chat-calc-input');
            const kg = parseFloat(kgInput.value) || 0;
            if (kg <= 0) {
                alert('Mohon masukkan berat perkiraan sampah (kg) yang valid.');
                return;
            }
            const price = parseInt(typeSelect.value);
            const name = typeSelect.options[typeSelect.selectedIndex].getAttribute('data-name');
            const total = kg * price;

            const replyText = `🎉 <b>Hasil Estimasi Simulasi:</b><br>Untuk <b>${kg} kg ${name}</b>, estimasi saldo yang akan Kakak dapatkan adalah <b>Rp ${total.toLocaleString('id-ID')}</b>!<br><div class="small text-muted mt-1">Saldo langsung masuk ke tabungan digital saat setelah ditimbang oleh petugas kami.</div>`;
            appendMessage(replyText, "bot");
        };

        const generateBotResponse = (userText) => {
            const lowerText = userText.toLowerCase();
            let reply = "";
            let nextChips = [];

            if(quickReplies) quickReplies.style.display = "none";

            /* Interactive Logic Matcher */
            if (lowerText.includes("simulasikan") || lowerText.includes("hitung") || lowerText.includes("kalkulator") || lowerText.includes("estimasi")) {
                reply = `Ingin tahu perkiraan saldo yang akan didapat? Yuk hitung langsung di bawah ini:
                <form class="chat-calc-box" onsubmit="event.preventDefault(); runChatSimulation(this);">
                    <div class="fw-bold mb-1" style="font-size:0.8rem;">1. Pilih Jenis Sampah:</div>
                    <select class="chat-calc-select">
                        <option value="2500" data-name="Plastik Bersih">Plastik Bersih (~Rp 2.500/kg)</option>
                        <option value="4000" data-name="Logam / Besi">Logam / Besi (~Rp 4.000/kg)</option>
                        <option value="1800" data-name="Kardus / Kertas">Kardus / Kertas (~Rp 1.800/kg)</option>
                        <option value="15000" data-name="Tembaga">Tembaga (~Rp 15.000/kg)</option>
                    </select>
                    <div class="fw-bold mb-1" style="font-size:0.8rem;">2. Perkiraan Berat (kg):</div>
                    <input type="number" class="chat-calc-input" placeholder="Contoh: 5" min="0.1" step="0.1" required>
                    <button type="submit" class="chat-btn-action mt-1">🧮 Hitung Estimasi Saldo</button>
                </form>`;
                nextChips = ['💰 Cek Harga Lengkap', '🚀 Cara Daftar', '⏰ Jam Buka'];
            }
            else if (lowerText.includes("harga") || lowerText.includes("kilo") || lowerText.includes("berapa") || lowerText.includes("duit") || lowerText.includes("rp") || lowerText.includes("jual")) {
                reply = `Harga beli sampah di Bank Sampah Subang selalu ter-update secara otomatis sesuai harga pasar dan kualitas sampah (bersih & terpilah).<br>
                <div class="chat-card-mini">
                    <div class="fw-bold text-success mb-1">💡 Tips Nilai Tinggi:</div>
                    Pastikan sampah plastik & kardus dalam kondisi kering dan sudah dipisahkan dari kotoran sisa makanan ya!
                </div>
                <button type="button" class="chat-btn-action" onclick="scrollToPriceAndSearch()">🔍 Lihat & Cari Tabel Harga</button>`;
                nextChips = ['🧮 Simulasikan Saldo', '🚀 Cara Daftar', '📍 Lokasi Bank'];
            }
            else if (lowerText.includes("daftar") || lowerText.includes("gabung") || lowerText.includes("registrasi") || lowerText.includes("buat akun") || lowerText.includes("join")) {
                reply = `Mudah banget Kak! Anda bisa menjadi nasabah dengan langkah berikut:<br>
                <div class="chat-card-mini">
                    <b>1. Siapkan KTP</b> aktif.<br>
                    <b>2. Datang ke Kantor Pelayanan</b> atau daftar via Aplikasi Mobile.<br>
                    <b>3. Dapatkan Buku Tabungan</b> / Akun Digital aktif!
                </div>
                <a href="{{ route('admin.login') ?? '#' }}" class="chat-btn-action">🚀 Masuk / Daftar Sekarang</a>`;
                nextChips = ['📱 Download APK', '💰 Cek Harga', '⏰ Jam Buka'];
            }
            else if (lowerText.includes("jam") || lowerText.includes("buka") || lowerText.includes("tutup") || lowerText.includes("operasional") || lowerText.includes("kapan")) {
                const now = new Date();
                const day = now.getDay(); // 0 = Sun, 6 = Sat
                const hour = now.getHours();
                const isOpen = (day >= 1 && day <= 5 && hour >= 8 && hour < 15);
                const statusBadge = isOpen
                    ? '<span class="badge bg-success">🟢 SEDANG BUKA</span>'
                    : '<span class="badge bg-danger">🔴 SEDANG TUTUP</span>';

                reply = `Status pelayanan kantor saat ini: ${statusBadge}<br><br>
                <b>Jadwal Operasional Kami:</b><br>
                📅 <b>Senin - Jumat:</b> 08:00 - 15:00 WIB<br>
                📅 <b>Sabtu - Minggu & Libur Nasional:</b> Tutup<br>
                <div class="small text-muted mt-2">Penerimaan sampah ditutup 30 menit sebelum jam operasional berakhir.</div>`;
                nextChips = ['📍 Lokasi Bank', '💰 Cek Harga', '🧮 Simulasikan Saldo'];
            }
            else if (lowerText.includes("lokasi") || lowerText.includes("alamat") || lowerText.includes("dimana") || lowerText.includes("tempat") || lowerText.includes("kantor") || lowerText.includes("map") || lowerText.includes("titik")) {
                reply = `Kantor pelayanan utama Bank Sampah Digital berlokasi di <b>Kabupaten Subang, Jawa Barat</b>.<br>
                <div class="chat-card-mini">
                    📍 <b>Fasilitas:</b> Timbangan digital akurat, loket penarikan tunai, dan area pemilahan edukatif.
                </div>
                <button type="button" class="chat-btn-action" onclick="scrollToSection('lokasi')">🗺️ Lihat Peta Google Maps</button>`;
                nextChips = ['⏰ Jam Buka', '🚀 Cara Daftar', '🧮 Simulasikan Saldo'];
            }
            else if (lowerText.includes("tarik") || lowerText.includes("cair") || lowerText.includes("withdraw") || lowerText.includes("saldo") || lowerText.includes("uang")) {
                reply = `Saldo tabungan sampah Anda adalah uang tunai nyata! 💸<br><br>
                <b>Cara Pencairan:</b><br>
                1. Bawa buku tabungan atau tunjukkan ID Nasabah di aplikasi.<br>
                2. Kunjungi kasir kami pada jam operasional.<br>
                3. Saldo langsung dicairkan tanpa potongan admin bulanan!`;
                nextChips = ['⏰ Jam Buka', '📍 Lokasi Bank', '📱 Download APK'];
            }
            else if (lowerText.includes("apk") || lowerText.includes("download") || lowerText.includes("aplikasi") || lowerText.includes("android")) {
                reply = `Dengan aplikasi mobile <b>Bank Sampah Digital Subang</b>, Anda bisa memantau saldo tabungan, riwayat setoran, dan update harga langsung dari HP! 📱✨<br>
                <a href="{{ asset('download/banksampahdigital.apk') }}" download="BankSampahDigital.apk" class="chat-btn-action">📲 Download APK Mobile Sekarang</a>`;
                nextChips = ['🚀 Cara Daftar', '💰 Cek Harga', '🧮 Simulasikan Saldo'];
            }
            else if (lowerText.includes("halo") || lowerText.includes("hai") || lowerText.includes("pagi") || lowerText.includes("siang") || lowerText.includes("sore") || lowerText.includes("malam") || lowerText.includes("assalam")) {
                reply = `Halo Kak! Selamat ${getGreetingTime()}! 😊 Ada yang bisa Nura bantu hari ini? Kakak bisa pilih opsi di bawah ya:`;
                nextChips = ['🧮 Simulasikan Saldo', '💰 Cek Harga', '🚀 Cara Daftar', '⏰ Jam Buka'];
            }
            else if (lowerText.includes("terima kasih") || lowerText.includes("makasih") || lowerText.includes("thanks") || lowerText.includes("oke") || lowerText.includes("sip") || lowerText.includes("mantap")) {
                reply = `Sama-sama Kak! 💚 Senang sekali bisa membantu. Semangat terus kelola sampah dan wujudkan Subang yang bersih dan lestari! 🌿✨`;
                nextChips = ['🧮 Simulasikan Saldo', '💰 Cek Harga'];
            }
            else {
                reply = `Maaf Kak, Nura belum sepenuhnya paham pertanyaan itu 😅. Namun Nura siap membantu informasi seputar layanan utama kami di bawah ini:`;
                nextChips = ['🧮 Simulasikan Saldo', '💰 Cek Harga', '🚀 Cara Daftar', '📍 Lokasi Bank'];
            }

            chatInput.disabled = true;
            typingIndicator.style.display = "flex";
            chatBody.scrollTop = chatBody.scrollHeight;

            setTimeout(() => {
                typingIndicator.style.display = "none";
                chatInput.disabled = false;
                chatInput.focus();
                appendMessage(reply, "bot");

                /* Render contextual next chips */
                if (nextChips && nextChips.length > 0) {
                    const chipsDiv = document.createElement("div");
                    chipsDiv.classList.add("quick-replies");
                    chipsDiv.style.display = "flex";
                    nextChips.forEach(chipText => {
                        const btn = document.createElement("button");
                        btn.classList.add("quick-btn");
                        btn.innerText = chipText;
                        btn.onclick = () => sendQuickReply(chipText);
                        chipsDiv.appendChild(btn);
                    });
                    chatBody.insertBefore(chipsDiv, typingIndicator);
                    chatBody.scrollTop = chatBody.scrollHeight;
                }
            }, 900);
        };

        const handleChat = () => {
            const text = chatInput.value.trim();
            if (!text) return;
            appendMessage(text, "user");
            chatInput.value = "";
            generateBotResponse(text);
        };

        window.sendQuickReply = (text) => {
            appendMessage(text, "user");
            generateBotResponse(text);
        };

        sendBtn.addEventListener("click", handleChat);
        chatInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") handleChat();
        });

        const newsletterForm = document.getElementById('newsletterForm');
        const newsletterFeedback = document.getElementById('newsletterFeedback');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const emailInput = document.getElementById('newsletter-email');
                if (!emailInput.checkValidity()) {
                    newsletterFeedback.textContent = 'Mohon isi alamat email yang valid.';
                    newsletterFeedback.classList.remove('success');
                    return;
                }
                newsletterFeedback.textContent = 'Terima kasih! Fitur berlangganan info akan segera hadir.';
                newsletterFeedback.classList.add('success');
                newsletterForm.reset();
            });
        }
    </script>
</body>
</html>
