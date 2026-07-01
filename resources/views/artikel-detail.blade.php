<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta name="theme-color" content="#10b981">
    <link rel="icon" type="image/png" href="{{ asset('image/BankSampahlogo.png') }}">
    <title>{{ $artikel->judul }} - Bank Sampah Digital Subang</title>

    {{-- SEO & Social preview --}}
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($artikel->konten), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $artikel->judul }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($artikel->konten), 160) }}">
    <meta property="og:image" content="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('image/BankSampahlogo.png') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --dark: #0f172a;
            --slate: #475569;
            --slate-light: #94a3b8;
            --surface: #f8fafc;
        }

        * { -webkit-tap-highlight-color: transparent; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface);
            color: var(--slate);
            overflow-x: hidden;
        }

        a:focus-visible,
        button:focus-visible,
        .gallery-item:focus-visible {
            outline: 3px solid var(--primary-dark);
            outline-offset: 2px;
        }

        /* ===== PROGRESS BAR BACA ===== */
        .reading-progress {
            position: fixed; top: 0; left: 0; height: 3px; width: 0%;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            z-index: 1100; transition: width 0.1s linear;
        }

        /* ===== NAVBAR ===== */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            padding-top: env(safe-area-inset-top, 0);
        }
        .navbar-custom.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .btn-back {
            color: var(--dark); font-weight: 700; font-size: 0.95rem;
            display: inline-flex; align-items: center; gap: 10px;
            text-decoration: none; padding: 8px 16px; border-radius: 50rem;
            background: #f1f5f9; transition: all 0.3s;
            white-space: nowrap;
        }
        .btn-back:hover { background: var(--primary-dark); color: white; transform: translateX(-3px); }
        .btn-back svg { flex-shrink: 0; }

        /* ===== HEADER ARTIKEL ===== */
        .article-header {
            padding: 140px 0 100px;
            background: linear-gradient(180deg, #ffffff 0%, var(--surface) 100%);
            position: relative;
        }
        .article-badge {
            background: rgba(16, 185, 129, 0.1); color: var(--primary-dark);
            padding: 8px 16px; border-radius: 50rem; font-weight: 700;
            font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;
            display: inline-block; margin-bottom: 1.5rem;
        }
        .article-title {
            font-weight: 800; color: var(--dark);
            font-size: clamp(1.75rem, 4vw, 3.5rem);
            line-height: 1.2; letter-spacing: -1px; margin-bottom: 1.5rem;
            word-break: break-word;
        }
        .article-meta {
            display: flex; align-items: center; justify-content: center; gap: 12px;
            color: var(--slate-light); font-size: 0.95rem; font-weight: 500;
            flex-wrap: wrap; row-gap: 6px;
        }
        .article-meta-divider { width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1; flex-shrink: 0; }

        /* ===== COVER IMAGE ===== */
        .main-image-wrapper { position: relative; z-index: 10; margin-top: -80px; text-align: center; padding: 0 15px; }
        .main-image {
            width: 100%; max-width: 1000px; height: clamp(220px, 45vw, 500px); object-fit: cover;
            border-radius: 24px; box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border: 8px solid white; background: white;
        }

        /* ===== KONTEN ARTIKEL ===== */
        .article-body {
            background: white; border-radius: 32px; padding: 4rem 2rem;
            margin-top: -40px; padding-top: 5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .article-content {
            font-family: 'Lora', serif; /* Font serif sangat nyaman untuk bacaan panjang */
            font-size: 1.15rem; line-height: 1.9; color: #334155;
            max-width: 760px; margin: 0 auto;
            overflow-wrap: break-word;
        }

        /* Auto styling untuk konten dari Text Editor */
        .article-content p { margin-bottom: 1.8rem; }
        .article-content h2, .article-content h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--dark); margin: 2.5rem 0 1rem; line-height: 1.3;}
        .article-content img { max-width: 100%; height: auto; border-radius: 16px; margin: 2rem 0; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .article-content blockquote { border-left: 4px solid var(--primary); padding-left: 1.5rem; font-style: italic; color: var(--slate); margin: 2rem 0; font-size: 1.25rem; }
        .article-content ul, .article-content ol { margin-bottom: 1.8rem; padding-left: 1.5rem; }
        .article-content li { margin-bottom: 0.5rem; }
        .article-content a { color: var(--primary); font-weight: 600; text-decoration: none; border-bottom: 1px solid transparent; transition: 0.3s; }
        .article-content a:hover { border-color: var(--primary); }
        .article-content table { width: 100%; display: block; overflow-x: auto; border-collapse: collapse; }

        /* ===== GALERI ARTIKEL ===== */
        .article-gallery { max-width: 1000px; margin: 3rem auto 0; padding: 0 2rem; }
        .gallery-title { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--dark); font-size: 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .gallery-title-badge { background: rgba(16, 185, 129, 0.1); color: var(--primary-dark); padding: 4px 12px; border-radius: 50rem; font-size: 0.8rem; font-weight: 700; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; }
        .gallery-item {
            position: relative; border-radius: 18px; overflow: hidden; background: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; cursor: pointer;
            aspect-ratio: 4/3; transition: 0.3s; padding: 0; border-style: solid;
        }
        .gallery-item:hover, .gallery-item:focus-visible { transform: translateY(-4px); box-shadow: 0 16px 32px rgba(16, 185, 129, 0.12); border-color: #d1fae5; }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease; }
        .gallery-item:hover img { transform: scale(1.06); }
        .gallery-caption { position: absolute; left: 0; right: 0; bottom: 0; padding: 0.9rem 1rem; background: linear-gradient(0deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0) 100%); color: white; font-size: 0.85rem; font-weight: 600; line-height: 1.3; opacity: 0; transition: opacity 0.3s; }
        .gallery-item:hover .gallery-caption, .gallery-item:focus-visible .gallery-caption { opacity: 1; }

        /* ===== LIGHTBOX ===== */
        .gallery-lightbox {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.92); z-index: 9999;
            display: none; align-items: center; justify-content: center; padding: 2rem;
            backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            padding-top: max(2rem, env(safe-area-inset-top));
            padding-bottom: max(2rem, env(safe-area-inset-bottom));
        }
        .gallery-lightbox.show { display: flex; }
        .gallery-lightbox-img { max-width: 95vw; max-height: 88vh; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
        .gallery-lightbox-close { position: absolute; top: max(20px, env(safe-area-inset-top)); right: 20px; width: 44px; height: 44px; border-radius: 50%; background: rgba(255,255,255,0.15); color: white; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; }
        .gallery-lightbox-close:hover { background: rgba(255,255,255,0.3); transform: rotate(90deg); }
        .gallery-lightbox-caption { position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%); color: white; background: rgba(15,23,42,0.6); padding: 0.6rem 1.2rem; border-radius: 50rem; font-size: 0.9rem; max-width: 90vw; text-align: center; }
        .gallery-lightbox-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 44px; height: 44px; border-radius: 50%; background: rgba(255,255,255,0.15);
            color: white; border: none; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: 0.2s;
        }
        .gallery-lightbox-nav:hover { background: rgba(255,255,255,0.3); }
        .gallery-lightbox-nav.prev { left: 16px; }
        .gallery-lightbox-nav.next { right: 16px; }

        /* ===== SHARE SECTION ===== */
        .share-section { max-width: 760px; margin: 3rem auto 0; padding-top: 2rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; }
        .share-text { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: var(--dark); }
        .social-btn { width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: var(--slate); transition: 0.3s; text-decoration: none; }
        .social-btn:hover { background: var(--primary); color: white; transform: translateY(-3px); }

        /* ===== BACA JUGA ===== */
        .related-section { padding: 80px 0; background: var(--surface); }
        .related-title { font-weight: 800; color: var(--dark); margin-bottom: 2rem; font-size: 1.8rem; }
        .artikel-card { border: none; border-radius: 20px; overflow: hidden; background: white; text-decoration: none; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: 0.4s; display: flex; flex-direction: column; height: 100%; border: 1px solid #f1f5f9;}
        .artikel-card:hover, .artikel-card:focus-visible { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(16, 185, 129, 0.1); border-color: #d1fae5; }
        .artikel-img-wrapper { height: 200px; overflow: hidden; position: relative;}
        .artikel-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .artikel-card:hover .artikel-img { transform: scale(1.05); }
        .artikel-body { padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1; }
        .artikel-body h5 { font-weight: 700; color: var(--dark); font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.4; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* ===== BACK TO TOP ===== */
        .back-to-top {
            position: fixed; right: 20px; bottom: max(20px, env(safe-area-inset-bottom));
            width: 46px; height: 46px; border-radius: 50%; background: var(--dark); color: white;
            border: none; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 25px rgba(15,23,42,0.25); cursor: pointer;
            opacity: 0; visibility: hidden; transform: translateY(10px);
            transition: opacity 0.3s, transform 0.3s, visibility 0.3s, background 0.3s;
            z-index: 1000;
        }
        .back-to-top.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .back-to-top:hover { background: var(--primary-dark); }

        /* ===== TOAST SALIN LINK ===== */
        .copy-toast {
            position: fixed; left: 50%; bottom: 30px; transform: translateX(-50%) translateY(20px);
            background: var(--dark); color: white; padding: 10px 20px; border-radius: 50rem;
            font-size: 0.9rem; font-weight: 600; opacity: 0; pointer-events: none;
            transition: opacity 0.3s, transform 0.3s; z-index: 1200;
        }
        .copy-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .article-header { padding: 100px 0 60px; }
            .main-image-wrapper { margin-top: -40px; }
            .main-image { border-width: 4px; border-radius: 16px; }
            .article-body { padding: 3rem 1.25rem; border-radius: 20px; }
            .article-content { font-size: 1.05rem; }
            .article-gallery { padding: 0 1.25rem; }
            .gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
            .gallery-item { border-radius: 12px; }
            .gallery-title { font-size: 1.2rem; }
        }

        @media (max-width: 400px) {
            .gallery-grid { grid-template-columns: 1fr; }
            .back-to-top { width: 42px; height: 42px; right: 14px; }
        }

        @media (max-width: 360px) {
            .btn-back span { display: none; }
            .btn-back { padding: 10px; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
        }
    </style>
</head>
<body>

    <div class="reading-progress" id="readingProgress"></div>

    <nav class="navbar navbar-expand-lg fixed-top navbar-custom" id="mainNav">
        <div class="container">
            <a class="btn-back" href="{{ route('beranda') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>Kembali ke Beranda</span>
            </a>
            <div class="ms-auto d-none d-sm-block">
                <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo Bank Sampah Digital Subang" style="width: 35px; border-radius: 8px;">
            </div>
        </div>
    </nav>

    <header class="article-header text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up">
                    <span class="article-badge">{{ ucfirst($artikel->kategori) }}</span>
                    <h1 class="article-title">{{ $artikel->judul }}</h1>
                    <div class="article-meta">
                        <span>Oleh <b>Admin Bank Sampah</b></span>
                        <span class="article-meta-divider"></span>
                        <span>{{ $artikel->created_at->format('d F Y') }}</span>
                        <span class="article-meta-divider"></span>
                        <span>
                            @php
                                $jumlahKata = str_word_count(strip_tags($artikel->konten));
                                $estimasiMenit = max(1, (int) ceil($jumlahKata / 200));
                            @endphp
                            {{ $estimasiMenit }} menit baca
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="pb-5">
        <div class="container">
            <div class="main-image-wrapper" data-aos="zoom-in" data-aos-delay="100">
                <img src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop' }}"
                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop'"
                     class="main-image" alt="Gambar sampul artikel: {{ $artikel->judul }}">
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    {{-- Galeri Foto Artikel (semua foto tambahan) --}}
                    @if($artikel->galeri && $artikel->galeri->count() > 0)
                    <section class="article-gallery" data-aos="fade-up" data-aos-delay="150">
                        <h3 class="gallery-title">
                            Galeri Foto
                            <span class="gallery-title-badge">{{ $artikel->galeri->count() }} foto</span>
                        </h3>
                        <div class="gallery-grid" id="galleryGrid">
                            @foreach($artikel->galeri as $foto)
                            <div class="gallery-item" tabindex="0" role="button"
                                 aria-label="Buka foto {{ $loop->iteration }} dari galeri{{ !empty($foto->keterangan) ? ': '.$foto->keterangan : '' }}"
                                 data-src="{{ asset('storage/' . $foto->gambar) }}"
                                 data-caption="{{ $foto->keterangan ?? '' }}"
                                 onclick="openLightbox({{ $loop->index }})"
                                 onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openLightbox({{ $loop->index }});}">
                                <img src="{{ asset('storage/' . $foto->gambar) }}"
                                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop'"
                                     alt="Galeri {{ $loop->iteration }} dari {{ $artikel->judul }}"
                                     loading="lazy">
                                @if(!empty($foto->keterangan))
                                    <div class="gallery-caption">{{ $foto->keterangan }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </section>
                    @endif

                    <div class="article-body" data-aos="fade-up" data-aos-delay="200">
                        <div class="article-content">
                            {!! $artikel->konten !!}
                        </div>

                        <div class="share-section">
                            <span class="share-text">Bagikan artikel ini:</span>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener" class="social-btn" aria-label="Bagikan ke Facebook" title="Bagikan ke Facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($artikel->judul) }}" target="_blank" rel="noopener" class="social-btn" aria-label="Bagikan ke X / Twitter" title="Bagikan ke X / Twitter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($artikel->judul . "\n" . request()->url()) }}" target="_blank" rel="noopener" class="social-btn" aria-label="Bagikan ke WhatsApp" title="Bagikan ke WhatsApp">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                </a>
                                <button type="button" class="social-btn" style="border: none;" aria-label="Salin tautan artikel" title="Salin tautan" onclick="copyArticleLink()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(isset($artikelLain) && $artikelLain->count() > 0)
    <section class="related-section border-top">
        <div class="container">
            <h3 class="related-title text-center" data-aos="fade-up">Baca Artikel Lainnya</h3>
            <div class="row justify-content-center g-4 mt-2">
                @foreach($artikelLain as $index => $item)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                    <a href="{{ route('publik.artikel.baca', $item->slug) }}" class="artikel-card">
                        <div class="artikel-img-wrapper">
                            <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop' }}"
                                 onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop'"
                                 class="artikel-img" alt="{{ $item->judul }}" loading="lazy">
                        </div>
                        <div class="artikel-body">
                            <h5>{{ $item->judul }}</h5>
                            <div class="mt-auto pt-3 text-primary fw-bold" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem;">
                                Lanjut Membaca →
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <footer class="bg-dark text-white py-4 text-center">
        <div class="container">
            <p class="mb-0 text-white-50 small">&copy; {{ date('Y') }} Bank Sampah Digital Subang.</p>
        </div>
    </footer>

    {{-- Tombol kembali ke atas --}}
    <button type="button" class="back-to-top" id="backToTop" aria-label="Kembali ke atas halaman">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
    </button>

    {{-- Toast salin link --}}
    <div class="copy-toast" id="copyToast">Tautan disalin ke clipboard</div>

    {{-- Lightbox untuk galeri artikel --}}
    <div id="galleryLightbox" class="gallery-lightbox" onclick="closeLightbox(event)">
        <button type="button" class="gallery-lightbox-close" onclick="closeLightbox(event, true)" aria-label="Tutup galeri">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <button type="button" class="gallery-lightbox-nav prev" onclick="navigateLightbox(event, -1)" aria-label="Foto sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <img id="lightboxImg" class="gallery-lightbox-img" src="" alt="">
        <button type="button" class="gallery-lightbox-nav next" onclick="navigateLightbox(event, 1)" aria-label="Foto berikutnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
        <div id="lightboxCaption" class="gallery-lightbox-caption" style="display:none;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        AOS.init({ once: true, offset: 50, duration: prefersReducedMotion ? 0 : 800, disable: prefersReducedMotion });

        const mainNav = document.getElementById('mainNav');
        const readingProgress = document.getElementById('readingProgress');
        const backToTop = document.getElementById('backToTop');

        function onScroll() {
            // Navbar shadow saat scroll
            if (window.scrollY > 10) {
                mainNav.classList.add('scrolled');
            } else {
                mainNav.classList.remove('scrolled');
            }

            // Progress bar baca
            const doc = document.documentElement;
            const scrollTop = window.scrollY || doc.scrollTop;
            const scrollHeight = (doc.scrollHeight - doc.clientHeight) || 1;
            const progress = Math.min(100, Math.max(0, (scrollTop / scrollHeight) * 100));
            readingProgress.style.width = progress + '%';

            // Tombol kembali ke atas
            if (scrollTop > 500) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
        });

        // Salin tautan artikel
        function copyArticleLink() {
            const url = window.location.href;
            const toast = document.getElementById('copyToast');

            const showToast = () => {
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 2000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(showToast).catch(() => fallbackCopy(url, showToast));
            } else {
                fallbackCopy(url, showToast);
            }
        }

        function fallbackCopy(text, cb) {
            const el = document.createElement('textarea');
            el.value = text;
            el.style.position = 'fixed';
            el.style.opacity = '0';
            document.body.appendChild(el);
            el.select();
            try { document.execCommand('copy'); } catch (e) {}
            document.body.removeChild(el);
            cb();
        }
    </script>

    <script>
        // ===== Galeri Lightbox (dengan navigasi prev/next) =====
        let galleryItems = [];
        let currentGalleryIndex = 0;

        document.addEventListener('DOMContentLoaded', function () {
            galleryItems = Array.from(document.querySelectorAll('#galleryGrid .gallery-item'));
        });

        function openLightbox(idx) {
            if (!galleryItems.length) {
                galleryItems = Array.from(document.querySelectorAll('#galleryGrid .gallery-item'));
            }
            currentGalleryIndex = idx;
            renderLightbox();

            document.getElementById('galleryLightbox').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function renderLightbox() {
            const item = galleryItems[currentGalleryIndex];
            if (!item) return;

            document.getElementById('lightboxImg').src = item.dataset.src;
            document.getElementById('lightboxImg').alt = item.dataset.caption || 'Foto galeri';

            const capEl = document.getElementById('lightboxCaption');
            const caption = item.dataset.caption;
            if (caption) {
                capEl.textContent = caption;
                capEl.style.display = 'block';
            } else {
                capEl.style.display = 'none';
            }
        }

        function navigateLightbox(event, direction) {
            event.stopPropagation();
            if (!galleryItems.length) return;
            currentGalleryIndex = (currentGalleryIndex + direction + galleryItems.length) % galleryItems.length;
            renderLightbox();
        }

        function closeLightbox(event, force) {
            if (force || (event && event.target === event.currentTarget)) {
                document.getElementById('galleryLightbox').classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', function (e) {
            const lb = document.getElementById('galleryLightbox');
            if (!lb.classList.contains('show')) return;

            if (e.key === 'Escape') closeLightbox(null, true);
            if (e.key === 'ArrowLeft') navigateLightbox(e, -1);
            if (e.key === 'ArrowRight') navigateLightbox(e, 1);
        });
    </script>
</body>
</html>
