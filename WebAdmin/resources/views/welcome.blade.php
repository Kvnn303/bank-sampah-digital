<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta name="theme-color" content="#10b981">
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--slate); overflow-x: hidden; background-color: var(--surface); }

        /* Smooth scroll & mencegah navbar menutupi konten saat diklik */
        html { scroll-behavior: smooth; scroll-padding-top: 90px; }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        /* ===== UTILITIES & GLASSMORPHISM ===== */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .icon-box-primary {
            width: 48px; height: 48px; background: var(--primary-light); color: var(--primary-dark);
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
        }

        .icon-box-success {
            width: 48px; height: 48px; background: #dcfce7; color: #16a34a;
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
        }

        .icon-box-large {
            width: 64px; height: 64px; background: white; border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04); display: flex; align-items: center;
            justify-content: center; border: 1px solid #f1f5f9;
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
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1rem 0;
            z-index: 1030;
        }
        .navbar-custom.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 0.6rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.02);
        }
        .navbar-brand-custom {
            font-weight: 800; font-size: 1.25rem; color: var(--dark) !important;
            letter-spacing: -0.5px; text-decoration: none;
        }
        .brand-logo { width: 42px; height: 42px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

        .nav-link-custom {
            color: var(--slate) !important; font-weight: 600; font-size: 0.95rem;
            padding: 0.5rem 1.2rem !important; border-radius: 50rem;
            transition: all 0.3s ease; position: relative; margin: 0 2px;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--primary-dark) !important;
            background: rgba(16, 185, 129, 0.08);
        }
        .btn-masuk {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white !important; border: none; border-radius: 50rem;
            padding: 0.6rem 1.8rem; font-weight: 700; font-size: 0.95rem;
            transition: all 0.3s ease; box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-masuk:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4); }

        /* ===== HERO ===== */
        .hero-section {
            padding: 160px 0 100px; position: relative;
            min-height: 100vh; display: flex; align-items: center;
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
            font-size: clamp(2.8rem, 5vw, 4.5rem); line-height: 1.1;
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
            font-size: 1.1rem; color: var(--slate);
            line-height: 1.7; margin-bottom: 2.5rem; max-width: 500px;
            font-weight: 500;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white !important; border-radius: 50rem; padding: 1.1rem 2.2rem; font-weight: 700; font-size: 1.05rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            text-decoration: none; display: inline-flex; align-items: center; gap: 10px; border: 2px solid transparent;
        }
        .btn-hero-primary:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(16, 185, 129, 0.4); border-color: rgba(255,255,255,0.3); }
        .btn-hero-secondary {
            background: white; color: var(--dark) !important; border-radius: 50rem; padding: 1.1rem 2.2rem; font-weight: 700;
            font-size: 1.05rem; transition: all 0.3s; text-decoration: none; box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            display: inline-flex; align-items: center; gap: 10px; border: 1px solid rgba(0,0,0,0.05);
        }
        .btn-hero-secondary:hover { color: var(--primary-dark) !important; transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); border-color: var(--primary-light); }

        .hero-stat-num { font-size: 2.5rem; font-weight: 900; color: var(--dark); letter-spacing: -1px; line-height: 1.2; }
        .hero-stat-label { font-weight: 600; color: var(--slate); font-size: 0.95rem; }

        .hero-visual { position: relative; margin-top: 2rem; }
        .hero-image-main {
            border-radius: 32px; overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15), inset 0 0 0 8px rgba(255,255,255,0.4);
            transform: perspective(1000px) rotateY(-8deg) rotateX(4deg);
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; z-index: 2;
        }
        .hero-image-main:hover { transform: perspective(1000px) rotateY(0deg) rotateX(0deg); }
        .hero-image-main img { width: 100%; height: 500px; object-fit: cover; display: block; }

        .floating-card {
            position: absolute; display: flex; align-items: center; gap: 15px; z-index: 3;
            animation: floatCard 6s ease-in-out infinite;
        }
        .floating-card-1 { bottom: 60px; left: -30px; animation-delay: 0s; }
        .floating-card-2 { top: 40px; right: -20px; animation-delay: 3s; }
        @keyframes floatCard { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }

        /* ===== COMMON ===== */
        .section-label {
            display: inline-flex; font-size: 0.85rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 2px; margin-bottom: 1rem; color: var(--primary);
        }
        .section-title {
            font-weight: 900; color: var(--dark); font-size: clamp(2rem, 4vw, 2.8rem);
            letter-spacing: -1px; line-height: 1.2; margin-bottom: 1rem;
        }

        /* ===== CARA KERJA ===== */
        .cara-kerja-section { padding: 100px 0; }
        .timeline-wrapper { position: relative; margin-top: 3rem; }
        .timeline-line {
            position: absolute; top: 40px; left: 10%; right: 10%; height: 4px;
            background: linear-gradient(90deg, rgba(16,185,129,0.15) 50%, transparent 0);
            background-size: 20px 4px; z-index: 0;
        }
        @media (max-width: 991px) { .timeline-line { display: none; } }

        .step-card {
            background: white; border-radius: 24px; padding: 2.5rem 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; z-index: 1; height: 100%;
        }
        .step-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(16, 185, 129, 0.08); border-bottom: 4px solid var(--primary); }
        .step-icon-wrap {
            width: 80px; height: 80px; border-radius: 24px; margin: 0 auto 1.5rem;
            background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            display: flex; align-items: center; justify-content: center; position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .step-card:hover .step-icon-wrap { background: var(--primary); color: white; transform: scale(1.1) rotate(5deg); box-shadow: 0 15px 30px rgba(16,185,129,0.3); }
        .step-card:hover .step-icon-wrap svg { stroke: white; }
        .step-icon-wrap svg { stroke: var(--primary); transition: 0.4s; }

        /* ===== HARGA ===== */
        .harga-section {
            background: var(--darker); padding: 120px 0; position: relative; overflow: hidden;
        }
        .harga-bg-glow {
            position: absolute; width: 80vw; height: 80vw; background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, transparent 60%);
            top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; pointer-events: none;
        }
        .harga-card {
            background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255,255,255,0.15); border-left: 1px solid rgba(255,255,255,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.02); border-right: 1px solid rgba(255,255,255,0.02);
            border-radius: 24px; padding: 2.5rem 2rem; text-align: center; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; z-index: 1;
        }
        .harga-card:hover { transform: translateY(-10px); background: rgba(255,255,255,0.06); box-shadow: 0 30px 60px rgba(0,0,0,0.6); border-top-color: var(--primary); }
        .harga-kategori { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); margin-bottom: 1rem; }
        .harga-nama { color: white; font-weight: 700; font-size: 1.2rem; margin-bottom: 1rem; }
        .harga-nominal { font-size: 2.2rem; font-weight: 800; color: white; margin-bottom: 0.2rem; letter-spacing: -1px; }

        /* ===== STOK TERSEDIA ===== */
        .stok-section { padding: 100px 0; background: white; }
        .stok-card {
            background: var(--surface); border-radius: 24px; padding: 1.5rem; border: 1px solid rgba(0,0,0,0.04);
            height: 100%; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .stok-card:hover { border-color: rgba(16,185,129,0.4); box-shadow: 0 20px 40px rgba(16,185,129,0.1); transform: translateY(-6px); }

        /* ===== ARTIKEL ===== */
        .artikel-section { padding: 100px 0; background: var(--surface); }
        .artikel-card {
            border: 1px solid rgba(0,0,0,0.04); border-radius: 24px; overflow: hidden; background: white; text-decoration: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; height: 100%;
        }
        .artikel-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); border-color: rgba(0,0,0,0.08); }
        .artikel-img-wrapper { height: 240px; position: relative; overflow: hidden; }
        .artikel-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .artikel-card:hover .artikel-img { transform: scale(1.08); }
        .artikel-date-badge {
            position: absolute; top: 20px; left: 20px; background: rgba(255,255,255,0.95); backdrop-filter: blur(5px);
            border-radius: 16px; padding: 10px 14px; text-align: center; font-weight: 800; line-height: 1.1; box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .artikel-date-badge .day { font-size: 1.3rem; color: var(--dark); display: block; }
        .artikel-date-badge .month { font-size: 0.75rem; color: var(--primary); text-transform: uppercase; }
        .artikel-body { padding: 2rem; flex-grow: 1; display: flex; flex-direction: column; }
        .artikel-title { font-weight: 800; color: var(--dark); font-size: 1.25rem; margin-bottom: 1rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.3s; }
        .artikel-card:hover .artikel-title { color: var(--primary-dark); }
        .artikel-link { margin-top: auto; color: var(--primary); font-weight: 700; display: inline-flex; align-items: center; gap: 5px; transition: 0.3s; }
        .artikel-card:hover .artikel-link { gap: 10px; }

        /* ===== TESTIMONI ===== */
        .testi-section { background: white; padding: 100px 0; }
        .testi-card { background: var(--surface); border-radius: 24px; padding: 2.5rem; border: 1px solid rgba(0,0,0,0.03); position: relative; overflow: hidden; z-index: 1; box-shadow: 0 10px 30px rgba(0,0,0,0.02);}
        .testi-card::before { content: '"'; position: absolute; top: -10px; right: 20px; font-size: 8rem; color: rgba(16,185,129,0.05); font-family: serif; font-weight: 900; z-index: -1; line-height: 1;}
        .testi-quote { font-size: 1.1rem; font-style: italic; color: var(--slate); margin-bottom: 2rem; line-height: 1.7; position: relative; z-index: 2;}
        .testi-user { display: flex; align-items: center; gap: 15px; }
        .testi-avatar { width: 55px; height: 55px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .testi-name { font-weight: 800; color: var(--dark); margin: 0; }
        .testi-role { font-size: 0.85rem; color: var(--slate-light); margin: 0; font-weight: 600;}

        /* ===== LOKASI ===== */
        .lokasi-section { padding: 100px 0; background: var(--surface); }
        .map-wrapper { border-radius: 32px; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.08); border: 8px solid white; }

        /* ===== FOOTER & CTA ===== */
        .cta-box {
            background: linear-gradient(135deg, var(--dark) 0%, var(--darker) 100%);
            border-radius: 32px; padding: 5rem 4rem; text-align: center; color: white; position: relative; overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15); transform: translateY(60px); z-index: 10;
        }
        .cta-box::before { content: ''; position: absolute; width: 400px; height: 400px; background: var(--primary); filter: blur(120px); opacity: 0.25; top: -200px; right: -100px; border-radius: 50%; }

        .footer { background: #020617; color: rgba(255,255,255,0.6); padding: 140px 0 2rem; position: relative; }
        .footer-title { color: white; font-weight: 800; font-size: 1.05rem; margin-bottom: 1.5rem; letter-spacing: 1px; }
        .footer-link { color: rgba(255,255,255,0.5); text-decoration: none; display: block; margin-bottom: 0.8rem; transition: all 0.3s ease; font-weight: 500;}
        .footer-link:hover { color: var(--primary); transform: translateX(5px); }
        .social-icons a { display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 50%; background: rgba(255,255,255,0.05); color: white; transition: all 0.3s ease; margin-right: 10px; }
        .social-icons a:hover { background: var(--primary); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(16,185,129,0.3); }

        @media (max-width: 991px) {
            .hero-section { padding: 130px 0 80px; text-align: center; }
            .hero-buttons { justify-content: center; }
            .hero-stats { justify-content: center; }
            .hero-image-main { transform: none; margin-top: 3rem; }
            .floating-card { display: none; }
            .cta-box { padding: 3.5rem 2rem; transform: translateY(40px); }
        }

        /* ===== AI CHATBOT WIDGET ===== */
        .chatbot-container { position: fixed; bottom: 30px; right: 30px; z-index: 1050; display: flex; flex-direction: column; align-items: flex-end; gap: 15px; }
        .chatbot-tooltip {
            background: white; color: var(--dark); padding: 12px 18px; border-radius: 16px; font-size: 0.9rem;
            font-weight: 700; box-shadow: 0 15px 35px rgba(0,0,0,0.1); position: relative; transform-origin: bottom right;
            animation: bounceTooltip 2.5s infinite; cursor: pointer; border: 1px solid rgba(0,0,0,0.05);
        }
        .chatbot-tooltip::after {
            content: ''; position: absolute; bottom: -6px; right: 24px; width: 14px; height: 14px;
            background: white; transform: rotate(45deg); border-right: 1px solid rgba(0,0,0,0.05); border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        @keyframes bounceTooltip { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }

        .chatbot-toggler {
            width: 65px; height: 65px; background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            cursor: pointer; box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4); border: 2px solid rgba(255,255,255,0.2); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .chatbot-toggler:hover { transform: scale(1.1) rotate(5deg); box-shadow: 0 15px 35px rgba(16, 185, 129, 0.5); }
        .chatbot-toggler .close-icon { display: none; }
        .show-chatbot .chatbot-toggler .chat-icon { display: none; }
        .show-chatbot .chatbot-toggler .close-icon { display: block; }
        .show-chatbot .chatbot-tooltip { display: none; }

        .chatbot-window {
            position: absolute; bottom: 85px; right: 0; width: 360px; background: white; border-radius: 24px; overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15); transform: scale(0.5); opacity: 0; pointer-events: none; transform-origin: bottom right;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid rgba(0,0,0,0.05);
        }
        .show-chatbot .chatbot-window { transform: scale(1); opacity: 1; pointer-events: auto; }

        .chat-header { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); padding: 20px; display: flex; align-items: center; gap: 14px; color: white; }
        .chat-header img { width: 48px; height: 48px; border-radius: 50%; background: white; padding: 2px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}
        .chat-title { font-weight: 800; font-size: 1.15rem; line-height: 1.2; margin: 0; }
        .chat-status { font-size: 0.85rem; opacity: 0.9; display: flex; align-items: center; gap: 6px; font-weight: 500;}
        .chat-status::before { content: ''; width: 8px; height: 8px; background: #34d399; border-radius: 50%; animation: pulse-status 2s infinite; box-shadow: 0 0 0 rgba(52, 211, 153, 0.4);}
        @keyframes pulse-status { 0% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7); } 70% { box-shadow: 0 0 0 6px rgba(52, 211, 153, 0); } 100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0); } }

        .chat-body { height: 400px; overflow-y: auto; padding: 20px; background: #f8fafc; display: flex; flex-direction: column; gap: 16px; scroll-behavior: smooth; }

        .message { max-width: 85%; font-size: 0.95rem; line-height: 1.5; padding: 14px 18px; position: relative; }
        .bot-message { background: white; color: var(--slate); border-radius: 4px 20px 20px 20px; border: 1px solid #e2e8f0; align-self: flex-start; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .user-message { background: var(--primary); color: white; border-radius: 20px 20px 4px 20px; align-self: flex-end; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2); }

        .quick-replies { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 5px; }
        .quick-btn { background: white; border: 1px solid var(--primary); color: var(--primary); padding: 8px 14px; border-radius: 50rem; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.02);}
        .quick-btn:hover { background: var(--primary); color: white; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(16,185,129,0.2);}

        .chat-input-area { padding: 15px 20px; background: white; border-top: 1px solid #f1f5f9; display: flex; align-items: center; gap: 12px; }
        .chat-input { flex-grow: 1; border: 1px solid #e2e8f0; background: #f8fafc; padding: 14px 18px; border-radius: 50rem; font-size: 0.95rem; outline: none; transition: all 0.3s ease; }
        .chat-input:focus { box-shadow: 0 0 0 3px rgba(16,185,129,0.1); border-color: var(--primary-light); background: white; }
        .send-btn { background: var(--primary); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 10px rgba(16,185,129,0.2);}
        .send-btn:hover { background: var(--primary-dark); transform: scale(1.05); }
        .send-btn svg { width: 20px; height: 20px; transform: translateX(-1px) translateY(1px); }

        .typing-indicator { display: none; gap: 5px; padding: 14px 20px; background: white; border-radius: 4px 20px 20px 20px; align-self: flex-start; border: 1px solid #e2e8f0; }
        .typing-indicator span { width: 8px; height: 8px; background: #cbd5e1; border-radius: 50%; animation: typing 1.4s infinite ease-in-out both; }
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing { 0%, 80%, 100% { transform: scale(0); opacity: 0.5; } 40% { transform: scale(1); opacity: 1; background: var(--primary); } }

        @media (max-width: 576px) { .chatbot-container { right: 20px; bottom: 20px; } .chatbot-window { width: calc(100vw - 40px); bottom: 80px; right: 0; } }
    </style>
</head>
<body>
    <div class="bg-mesh">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
    </div>

    <nav class="navbar navbar-expand-lg fixed-top navbar-custom" id="mainNav">
        <div class="container">
            <a class="navbar-brand-custom d-flex align-items-center gap-3" href="#beranda">
                <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo" class="brand-logo">
                Bank Sampah Subang
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                <div class="mt-3 mt-lg-0">
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
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="hero-badge">
                        <span class="pulse-dot"></span> Ekosistem Digital Terpadu
                    </div>
                    <h1 class="hero-title">
                        Jadikan Sampahmu<br>
                        <span class="gradient-text">Lebih Bernilai.</span>
                    </h1>
                    <p class="hero-desc">
                        Ubah kebiasaan membuang sampah menjadi menabung. Dapatkan penghasilan tambahan dan bantu wujudkan Kabupaten Subang yang bersih dan lestari.
                    </p>
                    <div class="hero-buttons mb-5">
                        <a href="#harga" class="btn-hero-primary">Cek Harga Hari Ini</a>
                        <a href="#cara-kerja" class="btn-hero-secondary">Pelajari Caranya</a>
                    </div>
                    <div class="hero-stats d-flex gap-5">
                        <div>
                            <div class="hero-stat-num"><span class="counter" data-target="{{ $totalNasabah ?? 0 }}">0</span>+</div>
                            <div class="hero-stat-label">Nasabah Aktif</div>
                        </div>
                        <div>
                            <div class="hero-stat-num"><span class="counter" data-target="{{ $totalSampah ?? 0 }}">0</span><span class="fs-5 ms-1">kg</span></div>
                            <div class="hero-stat-label">Sampah Dikelola</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="zoom-in" data-aos-duration="1200" data-aos-delay="200">
                    <div class="hero-visual">
                        <div class="hero-image-main">
                            <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=2070&auto=format&fit=crop" alt="Daur Ulang Sampah">
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
            </div>
            <div class="timeline-wrapper">
                <div class="timeline-line"></div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="step-card text-center">
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                            </div>
                            <h4 class="fw-bolder text-dark fs-5 mb-2">Daftar Akun</h4>
                            <p class="text-muted lh-base font-medium">Daftarkan diri Anda sebagai nasabah Bank Sampah.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="step-card text-center">
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg>
                            </div>
                            <h4 class="fw-bolder text-dark fs-5 mb-2">Pilah Sampah</h4>
                            <p class="text-muted lh-base font-medium">Pisahkan sampah organik dan anorganik dari rumah.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="step-card text-center">
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                            </div>
                            <h4 class="fw-bolder text-dark fs-5 mb-2">Setor & Timbang</h4>
                            <p class="text-muted lh-base font-medium">Bawa ke titik kumpul kami untuk ditimbang petugas.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                        <div class="step-card text-center">
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            </div>
                            <h4 class="fw-bolder text-dark fs-5 mb-2">Cairkan Saldo</h4>
                            <p class="text-muted lh-base font-medium">Saldo bertambah dan siap ditarik tunai kapan saja.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="harga" class="harga-section">
        <div class="harga-bg-glow"></div>
        <div class="container position-relative z-1">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="section-label" style="color:var(--primary-light);">Transparansi Harga</div>
                <h2 class="section-title text-white">Daftar Harga Beli Terkini</h2>
                <p class="section-desc mx-auto text-white-50" style="max-width:600px;">Harga otomatis diperbarui dari sistem. Pastikan sampah dalam kondisi bersih untuk nilai tukar terbaik.</p>
            </div>

            <div class="row g-4 justify-content-center">
                @if(isset($jenisSampah) && count($jenisSampah) > 0)
                    @foreach($jenisSampah as $index => $sampah)
                    <div class="col-sm-6 col-lg-3" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
                        <div class="harga-card">
                            <div class="harga-kategori">{{ $sampah->kategori ?? 'Umum' }}</div>
                            <div class="harga-nama">{{ $sampah->nama }}</div>
                            <div class="harga-nominal">Rp {{ number_format($sampah->harga_per_kg, 0, ',', '.') }}</div>
                            <div class="harga-satuan text-white-50 small fw-semibold">/ Kilogram</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12 text-center text-white-50">Data harga belum tersedia.</div>
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
                <p class="text-muted mx-auto" style="max-width: 550px;">Sampah yang sudah di-press dan siap dibeli. Hubungi kami untuk kerja sama pengambilan.</p>
                <a href="{{ route('publik.stok') ?? '#' }}" class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold mt-3 transition hover-scale">
                    Lihat Semua Stok
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="ms-1 align-middle"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
            <div class="row g-4">
                @foreach($stokTersedia as $stok)
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                    <a href="{{ route('publik.stok.detail', $stok->slug ?? '') ?? '#' }}" class="text-decoration-none">
                        <div class="stok-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ $stok->jenisSampah->nama ?? '-' }}</h5>
                                    <span class="badge rounded-pill" style="background: rgba(139,92,246,0.1); color: #7c3aed; border: 1px solid rgba(139,92,246,0.2); font-size: 0.7rem; padding: 4px 10px; font-weight:800;">PRESSED</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold fs-4" style="color: #10b981; letter-spacing: -0.5px;">Rp {{ number_format($stok->harga_jual_per_kg ?? 0, 0, ',', '.') }}</div>
                                    <div class="text-muted small fw-semibold">/kg</div>
                                </div>
                            </div>
                            @if(!empty($stok->gambar))
                            <div class="mb-4 mt-3 rounded-4 overflow-hidden shadow-sm" style="max-height: 140px;">
                                <img src="{{ asset('storage/' . $stok->gambar) }}" alt="Foto {{ $stok->jenisSampah->nama ?? '' }}" class="w-100" style="object-fit: cover; height: 140px;">
                            </div>
                            @endif
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted fw-semibold">Tersedia</span>
                                    <span class="fw-bold text-dark">{{ number_format($stok->stok_tersisa_kg ?? 0, 1, ',', '.') }} kg</span>
                                </div>
                                @php $pct = (isset($stok->stok_masuk_kg) && $stok->stok_masuk_kg > 0) ? min(($stok->stok_tersisa_kg / $stok->stok_masuk_kg) * 100, 100) : 0; @endphp
                                <div class="progress" style="height: 6px; border-radius: 99px;">
                                    <div class="progress-bar" style="width: {{ $pct }}%; background: linear-gradient(90deg, #10b981, #34d399);"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-light">
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
                </div>
                <a href="#" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold mb-2 transition hover-scale">Lihat Semua Berita</a>
            </div>

            <div class="row g-4">
                @if(isset($artikels) && count($artikels) > 0)
                    @foreach($artikels as $index => $artikel)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                        <a href="{{ route('publik.artikel.baca', $artikel->slug ?? '') ?? '#' }}" class="artikel-card">
                            <div class="artikel-img-wrapper">
                                <img src="{{ !empty($artikel->gambar) ? asset('storage/' . $artikel->gambar) : 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop' }}"
                                     onerror="this.src='https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop'"
                                     class="artikel-img" alt="{{ $artikel->judul ?? 'Artikel' }}">
                                <div class="artikel-date-badge">
                                    <span class="day">{{ !empty($artikel->created_at) ? $artikel->created_at->format('d') : '-' }}</span>
                                    <span class="month">{{ !empty($artikel->created_at) ? $artikel->created_at->format('M') : '-' }}</span>
                                </div>
                            </div>
                            <div class="artikel-body">
                                <span class="badge bg-light text-primary border border-light mb-3 align-self-start fw-bold px-3 py-2">{{ ucfirst($artikel->kategori ?? 'Umum') }}</span>
                                <h4 class="artikel-title">{{ $artikel->judul ?? 'Judul Artikel' }}</h4>
                                <div class="artikel-link mt-auto">
                                    Lanjut Membaca <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                @else
                    <div class="col-12 text-center text-muted">Belum ada artikel yang dipublikasikan.</div>
                @endif
            </div>
        </div>
    </section>

    <section class="testi-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Apa Kata Warga Subang?</h2>
                <p class="section-desc mx-auto text-slate fw-medium">Pengalaman mereka setelah bergabung menjadi bagian dari perubahan.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testi-card h-100 d-flex flex-column justify-content-between">
                        <p class="testi-quote">"Sangat membantu! Selain lingkungan rumah jadi bersih dari botol plastik, saldonya lumayan buat nambah uang belanja bulanan."</p>
                        <div class="testi-user">
                            <img src="https://ui-avatars.com/api/?name=Ibu+Siti&background=10b981&color=fff" class="testi-avatar" alt="User">
                            <div>
                                <h5 class="testi-name">Ibu Siti</h5>
                                <p class="testi-role">Nasabah Pasirkareumbi</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testi-card h-100 d-flex flex-column justify-content-between">
                        <p class="testi-quote">"Sistemnya sangat transparan. Saya bisa ngecek saldo dan history setoran langsung dari HP. Penarikan dananya juga cepat diproses."</p>
                        <div class="testi-user">
                            <img src="https://ui-avatars.com/api/?name=Pak+Budi&background=3b82f6&color=fff" class="testi-avatar" alt="User">
                            <div>
                                <h5 class="testi-name">Budi Santoso</h5>
                                <p class="testi-role">Nasabah Sukadana</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testi-card h-100 d-flex flex-column justify-content-between">
                        <p class="testi-quote">"Edukasi dari Bank Sampah bikin sadar kalau kardus dan kertas koran yang biasa dibakar ternyata punya nilai ekonomi tinggi."</p>
                        <div class="testi-user">
                            <img src="https://ui-avatars.com/api/?name=Andi+W&background=f59e0b&color=fff" class="testi-avatar" alt="User">
                            <div>
                                <h5 class="testi-name">Andi Wijaya</h5>
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
            <div class="row align-items-center g-5">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="section-label">Kunjungi Kami</div>
                    <h2 class="section-title">Kantor Pelayanan Bank Sampah</h2>
                    <p class="section-desc mb-5 text-slate fw-medium">Tim kami siap melayani penimbangan sampah dan pencairan dana Anda setiap hari kerja.</p>

                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="icon-box-large">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Alamat Utama</h5>
                            <p class="text-slate mb-0 fw-medium">Kabupaten Subang, Jawa Barat</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <div class="icon-box-large">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Jam Operasional</h5>
                            <p class="text-slate mb-0 fw-medium">Senin - Jumat: 08:00 - 15:00 WIB</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="map-wrapper">
                        <iframe src="https://maps.google.com/maps?q=Kabupaten%20Subang,%20Jawa%20Barat&t=&z=11&ie=UTF8&iwloc=&output=embed" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div style="background: var(--surface);">
        <div class="container">
            <div class="cta-box" data-aos="zoom-in">
                <h2 class="cta-title fw-bolder mb-3 fs-1">Siap Wujudkan Lingkungan Bersih?</h2>
                <p class="cta-desc mx-auto mb-5 text-white-50 fs-5" style="max-width: 600px;">Pendaftaran gratis. Mulai kelola sampahmu hari ini dan nikmati manfaat ekonominya untuk keluarga.</p>
                <a href="{{ route('admin.login') ?? '#' }}" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-success d-inline-flex align-items-center gap-2 shadow-lg" style="transition: transform 0.3s;">
                    Gabung Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <h4 class="fw-bold d-flex align-items-center gap-3 mb-4 text-white">
                        <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo" style="width:40px; border-radius:10px;">
                        Bank Sampah Subang
                    </h4>
                    <p class="footer-desc mb-4" style="line-height: 1.8;">Sistem informasi pengelolaan bank sampah digital yang transparan, mudah, dan menguntungkan masyarakat Subang.</p>
                    <div class="social-icons">
                        <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h5 class="footer-title">Jelajahi</h5>
                    <a href="#beranda" class="footer-link">Beranda</a>
                    <a href="#cara-kerja" class="footer-link">Cara Kerja</a>
                    <a href="#harga" class="footer-link">Daftar Harga</a>
                    <a href="#artikel" class="footer-link">Pojok Edukasi</a>
                </div>
                <div class="col-lg-2 col-6">
                    <h5 class="footer-title">Layanan</h5>
                    <a href="#" class="footer-link">Setor Sampah</a>
                    <a href="#" class="footer-link">Tarik Saldo</a>
                    <a href="#" class="footer-link">Bantuan (FAQ)</a>
                    <a href="{{ route('admin.login') ?? '#' }}" class="footer-link">Masuk Admin</a>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Berlangganan Info</h5>
                    <p class="footer-desc mb-3">Dapatkan info kenaikan harga sampah dan edukasi terbaru.</p>
                    <div class="d-flex gap-2">
                        <input type="email" class="form-control bg-dark border-secondary text-white shadow-none" placeholder="Alamat email Anda" style="border-radius: 50rem; padding: 0.75rem 1.2rem;">
                        <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Kirim</button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center pt-4 border-top border-secondary border-opacity-25">
                <p class="mb-0 fw-medium opacity-75">© {{ date('Y') }} Bank Sampah Digital Subang. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <div class="chatbot-container">
        <div class="chatbot-tooltip" onclick="document.body.classList.add('show-chatbot'); initChat();">
            Ada pertanyaan? Nura siap bantu! 👋
        </div>
        <button class="chatbot-toggler">
            <svg xmlns="http://www.w3.org/2000/svg" class="chat-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="close-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <div class="chatbot-window">
        <div class="chat-header">
            <img src="{{ asset('image/nura.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=Nura&background=10b981&color=fff'" alt="Nura AI">
            <div>
                <h4 class="chat-title">Nura AI Asisten</h4>
                <div class="chat-status">Online siap membantu</div>
            </div>
        </div>
        <div class="chat-body" id="chat-body">
            <div class="quick-replies" id="quick-replies">
                <button class="quick-btn" onclick="sendQuickReply('Cara Daftar')">Cara Daftar</button>
                <button class="quick-btn" onclick="sendQuickReply('Cek Harga')">Cek Harga</button>
                <button class="quick-btn" onclick="sendQuickReply('Lokasi Bank')">Lokasi Bank</button>
            </div>
            <div class="typing-indicator" id="typing-indicator">
                <span></span><span></span><span></span>
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" id="chat-input" class="chat-input" placeholder="Ketik pesan di sini..." autocomplete="off">
            <button class="send-btn" id="send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z"/></svg>
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 60, duration: 800 });

        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => { nav.classList.toggle('scrolled', window.scrollY > 50); });

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
        });

        document.querySelectorAll('.counter').forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / 40;
                if(count < target && target > 0) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 40);
                } else {
                    counter.innerText = target;
                }
            };
            let observer = new IntersectionObserver(e => { if(e[0].isIntersecting){ updateCount(); observer.disconnect(); } }, { threshold: 0.5 });
            observer.observe(counter);
        });

        /* =======================================
           LOGIKA NURA AI (CHATBOT)
           ======================================= */
        const chatbotToggler = document.querySelector(".chatbot-toggler");
        const chatbotWindow = document.querySelector(".chatbot-window");
        const chatBody = document.getElementById("chat-body");
        const chatInput = document.getElementById("chat-input");
        const sendBtn = document.getElementById("send-btn");
        const typingIndicator = document.getElementById("typing-indicator");
        const quickReplies = document.getElementById("quick-replies");

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
            welcomeMsg.innerHTML = `Halo Kak! Selamat ${getGreetingTime()}! 👋<br>Saya Nura, asisten cerdas dari Bank Sampah Subang. Mau tanya apa nih Kak? Nura siap bantu! ✨`;

            chatBody.insertBefore(welcomeMsg, quickReplies);
            quickReplies.style.display = "flex";
            chatBody.scrollTop = 0;
        };

        chatbotToggler.addEventListener("click", () => {
            const isShowing = document.body.classList.contains("show-chatbot");
            if (isShowing) {
                document.body.classList.remove("show-chatbot");
                setTimeout(initChat, 400);
            } else {
                document.body.classList.add("show-chatbot");
                if (document.querySelectorAll('.message').length === 0) initChat();
            }
        });

        const botDatabase = {
            salam: ["halo", "hai", "hi", "pagi", "siang", "sore", "malam", "assalamualaikum", "nura", "ping"],
            daftar: ["daftar", "gabung", "registrasi", "join", "bikin", "akun", "buat"],
            harga: ["harga", "berapa", "kilo", "duit", "rp", "nilai", "jual", "uang", "harganya"],
            lokasi: ["lokasi", "alamat", "dimana", "tempat", "kantor", "map", "titik", "cabang"],
            tarik: ["tarik", "cair", "uang", "ambil", "withdraw", "saldo", "pencairan"],
            jam: ["jam", "buka", "tutup", "operasional", "kapan", "hari", "kerja"],
            terimakasih: ["terima", "kasih", "thanks", "makasih", "ok", "oke", "sip", "mantap", "paham", "jelas", "baik"]
        };

        const botAnswers = {
            salam: `Halo Kak, Selamat ${getGreetingTime()}! 😊 Gimana kabarnya? Silakan tanya aja apa pun seputar Bank Sampah ya!`,
            daftar: "Wah, mau ikutan nabung sampah ya Kak? Gampang banget! Cukup bawa KTP ke kantor kami, atau Kakak bisa minta bantuan Admin buat daftarin. Nanti Kakak dapat buku tabungan khusus! 🎉",
            harga: "Soal harga, Nura pastikan kita pakai harga update Kak! 💰 Plastik, logam, kardus... Semua ada harganya. Kakak bisa cek selengkapnya di tabel <b>'Daftar Harga Beli'</b> di website ini ya.",
            lokasi: "Titik kumpul dan kantor utama kami berlokasi di <b>Kabupaten Subang, Jawa Barat</b>. Kalau Kakak bingung jalannya, tinggal <i>scroll</i> web ini paling bawah buat lihat rute di Google Maps. 🗺️",
            tarik: "Saldo tabungan sampah Kakak itu beneran uang nyata lho! Bisa ditarik kapan pun pas jam kerja kami. Cukup bawa buku tabungannya aja ke kasir. 💸",
            jam: "Kita buka dari <b>Senin sampai Jumat, mulai jam 08:00 pagi sampai 15:00 sore</b> Kak. Pastiin jangan kesorean datengnya ya! ⏳",
            terimakasih: "Sama-sama Kak! Nura seneng banget bisa bantu. Kalau ada bingung lagi, panggil Nura aja ya. Semangat terus jaga lingkungan kita! 💚🌿"
        };

        const defaultAnswers = [
            "Waduh, Nura kurang paham maksud Kakak 😅. Coba tanya seputar <b>Harga, Cara Daftar, atau Lokasi</b> deh.",
            "Hmm.. Nura masih terus belajar nih 🤖. Kakak bisa tanya soal <b>Jam Buka</b> atau <b>Cara Tarik Saldo</b> ya.",
            "Maaf Kak, Nura belum dapet contekan soal itu 📚. Yuk tanya info layanan Bank Sampah yang lain aja!"
        ];

        const followUps = [
            "<br><br><i>Btw, ada lagi yang mau ditanyain Kak? Nura standby nih!</i>",
            "<br><br><i>Gimana Kak, udah cukup jelas belum infonya?</i>",
            "<br><br><i>Ada info lain yang bisa Nura bantu carikan?</i>"
        ];

        const appendMessage = (text, sender) => {
            const msgDiv = document.createElement("div");
            msgDiv.classList.add("message", sender === "user" ? "user-message" : "bot-message");
            msgDiv.innerHTML = text;
            chatBody.insertBefore(msgDiv, typingIndicator);
            chatBody.scrollTop = chatBody.scrollHeight;
        };

        const generateBotResponse = (userText) => {
            let reply = defaultAnswers[Math.floor(Math.random() * defaultAnswers.length)];
            const lowerText = userText.toLowerCase();
            let matchedCategory = null;

            if(quickReplies) quickReplies.style.display = "none";

            let found = false;
            for (const category in botDatabase) {
                if (botDatabase[category].some(keyword => lowerText.includes(keyword))) {
                    reply = botAnswers[category];
                    matchedCategory = category;
                    found = true;
                    break;
                }
            }

            if (found && Math.random() > 0.6 && matchedCategory !== 'terimakasih' && matchedCategory !== 'salam') {
                 reply += followUps[Math.floor(Math.random() * followUps.length)];
            }

            chatInput.disabled = true;
            typingIndicator.style.display = "flex";
            chatBody.scrollTop = chatBody.scrollHeight;

            setTimeout(() => {
                typingIndicator.style.display = "none";
                chatInput.disabled = false;
                chatInput.focus();
                appendMessage(reply, "bot");
            }, 1200);
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
    </script>
</body>
</html>
