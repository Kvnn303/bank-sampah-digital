<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta name="theme-color" content="#10b981">
    <link rel="icon" type="image/png" href="{{ asset('image/BankSampahlogo.png') }}">
    <title>{{ $artikel->judul }} - Bank Sampah Digital Subang</title>

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

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface);
            color: var(--slate);
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .navbar-custom.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .btn-back {
            color: var(--dark); font-weight: 700; font-size: 0.95rem;
            display: inline-flex; align-items: center; gap: 10px;
            text-decoration: none; padding: 8px 16px; border-radius: 50rem;
            background: #f1f5f9; transition: all 0.3s;
        }
        .btn-back:hover { background: var(--primary-dark); color: white; transform: translateX(-3px); }

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
            font-size: clamp(2rem, 4vw, 3.5rem);
            line-height: 1.2; letter-spacing: -1px; margin-bottom: 1.5rem;
        }
        .article-meta {
            display: flex; align-items: center; justify-content: center; gap: 15px;
            color: var(--slate-light); font-size: 0.95rem; font-weight: 500;
        }
        .article-meta-divider { width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1; }

        /* ===== COVER IMAGE ===== */
        .main-image-wrapper { position: relative; z-index: 10; margin-top: -80px; text-align: center; }
        .main-image {
            width: 100%; max-width: 1000px; height: 500px; object-fit: cover;
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

        /* ===== SHARE SECTION ===== */
        .share-section { max-width: 760px; margin: 3rem auto 0; padding-top: 2rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .share-text { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: var(--dark); }
        .social-btn { width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: var(--slate); transition: 0.3s; text-decoration: none; }
        .social-btn:hover { background: var(--primary); color: white; transform: translateY(-3px); }

        /* ===== BACA JUGA ===== */
        .related-section { padding: 80px 0; background: var(--surface); }
        .related-title { font-weight: 800; color: var(--dark); margin-bottom: 2rem; font-size: 1.8rem; }
        .artikel-card { border: none; border-radius: 20px; overflow: hidden; background: white; text-decoration: none; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: 0.4s; display: flex; flex-direction: column; height: 100%; border: 1px solid #f1f5f9;}
        .artikel-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(16, 185, 129, 0.1); border-color: #d1fae5; }
        .artikel-img-wrapper { height: 200px; overflow: hidden; position: relative;}
        .artikel-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .artikel-card:hover .artikel-img { transform: scale(1.05); }
        .artikel-body { padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1; }
        .artikel-body h5 { font-weight: 700; color: var(--dark); font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.4; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .article-header { padding: 100px 0 60px; }
            .main-image-wrapper { margin-top: -40px; padding: 0 15px; }
            .main-image { height: 250px; border-width: 4px; border-radius: 16px; }
            .article-body { padding: 3rem 1rem; border-radius: 20px; }
            .article-content { font-size: 1.05rem; }
            .share-section { flex-direction: column; gap: 15px; align-items: flex-start; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top navbar-custom" id="mainNav">
        <div class="container">
            <a class="btn-back" href="{{ route('beranda') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Beranda
            </a>
            <div class="ms-auto d-none d-sm-block">
                <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo" style="width: 35px; border-radius: 8px;">
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
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="pb-5">
        <div class="container">
            <div class="main-image-wrapper" data-aos="zoom-in" data-aos-delay="100">
                <img src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop' }}"
                     onerror="this.src='https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop'"
                     class="main-image" alt="Cover Image">
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="article-body" data-aos="fade-up" data-aos-delay="200">
                        <div class="article-content">
                            {!! $artikel->konten !!}
                        </div>

                        <div class="share-section">
                            <span class="share-text">Bagikan artikel ini:</span>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ request()->url() }}" target="_blank" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
                                <a href="https://twitter.com/intent/tweet?url={{ request()->url() }}&text={{ $artikel->judul }}" target="_blank" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg></a>
                                <a href="https://wa.me/?text={{ $artikel->judul }} %0A{{ request()->url() }}" target="_blank" class="social-btn"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></a>
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
                                 onerror="this.src='https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?q=80&w=2070&auto=format&fit=crop'"
                                 class="artikel-img" alt="{{ $item->judul }}">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 800 });

        // Navbar effect on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            if (window.scrollY > 10) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
