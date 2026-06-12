<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#10b981">
    <meta name="description" content="Detail stok material sampah daur ulang di Bank Sampah Digital Subang">
    <link rel="icon" type="image/png" href="{{ asset('image/BankSampahlogo.png') }}">
    <title>{{ $stok->jenisSampah->nama ?? 'Detail Stok' }} - Bank Sampah Digital Subang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --primary: #10b981; 
            --primary-dark: #059669; 
            --dark: #0f172a; 
            --slate: #475569; 
            --surface: #f8fafc; 
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--slate); background: var(--surface); }
        
        /* Navbar */
        .navbar-brand-custom { font-weight: 800; font-size: 1.2rem; color: var(--dark) !important; text-decoration: none; }
        .btn-masuk { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white !important; border-radius: 50rem; padding: 0.6rem 1.8rem; font-weight: 700; text-decoration: none; transition: all 0.3s; border: none; }
        .btn-masuk:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,185,129,0.3); color: white; }
        
        /* Badges */
        .badge-press { background: rgba(139,92,246,0.1); color: #7c3aed; border: 1px solid rgba(139,92,246,0.2); font-weight: 700; letter-spacing: 0.5px;}
        .badge-tersedia { background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.2); font-weight: 700; letter-spacing: 0.5px;}
        
        /* Cards */
        .stok-card { background: white; border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); height: 100%; display: flex; flex-direction: column;}
        .stok-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.2); }
        .detail-card { background: white; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.03); }
        
        /* Info Box */
        .info-box { background: var(--surface); border-radius: 16px; padding: 1.25rem; border: 1px solid rgba(0,0,0,0.04); }
        
        /* Hero Section */
        .hero-section { position: relative; background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%); padding: 70px 0 50px; color: white; overflow: hidden; }
        .hero-decoration { position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; }
        
        /* Utility */
        .progress-bar-custom { background: linear-gradient(90deg, #10b981, #34d399); border-radius: 99px; transition: width 0.8s ease-in-out; }
        .img-cover { object-fit: cover; width: 100%; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05); }
        
        @media (max-width: 768px) { 
            .hero-section { padding: 50px 0 40px; } 
            .img-cover { height: 250px !important; }
            .detail-card { padding: 1.5rem !important; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm py-3" style="z-index: 1030;">
    <div class="container">
        <a class="navbar-brand-custom d-flex align-items-center gap-3" href="/">
            <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo" style="width:42px;height:42px;border-radius:12px;box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <span class="d-none d-sm-block">Bank Sampah Subang</span>
        </a>
        <div class="d-flex gap-2 align-items-center">
            <a href="/" class="btn btn-light rounded-pill fw-semibold border d-none d-md-block px-4">Beranda</a>
            <a href="{{ route('publik.stok') ?? '#' }}" class="btn btn-light rounded-pill fw-semibold border text-success px-4">Kembali</a>
            <a href="{{ route('admin.login') ?? '#' }}" class="btn-masuk d-none d-sm-block">Masuk Admin</a>
        </div>
    </div>
</nav>

{{-- HERO / BREADCRUMB --}}
<section class="hero-section">
    <div class="hero-decoration"></div>
    <div class="container position-relative z-1">
        <nav style="--bs-breadcrumb-divider: '›'; font-size: 0.9rem;" aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="/" class="text-white-50 text-decoration-none hover-white">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('publik.stok') ?? '#' }}" class="text-white-50 text-decoration-none hover-white">Stok Tersedia</a></li>
                <li class="breadcrumb-item text-white fw-semibold" aria-current="page">{{ $stok->jenisSampah->nama ?? 'Material' }}</li>
            </ol>
        </nav>
        
        @if($stok->is_pres ?? false)
        <div class="d-inline-flex align-items-center badge badge-press px-3 py-2 rounded-pill mb-3" style="font-size: 0.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            STOK TER-PRESS
        </div>
        @endif
        
        <h1 class="fw-800 text-white mb-2" style="font-size: clamp(2rem, 5vw, 3rem); letter-spacing: -0.5px;">{{ $stok->jenisSampah->nama ?? 'Detail Material' }}</h1>
        <p class="text-white-50 mb-0 fs-5 d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
            Kategori: {{ $stok->jenisSampah->kategori ?? 'Umum' }}
        </p>
    </div>
</section>

{{-- DETAIL STOK --}}
<section style="padding: 60px 0; margin-top: -30px;">
    <div class="container">
        <div class="row g-4 g-lg-5">
            {{-- Kolom Utama --}}
            <div class="col-lg-8">
                
                {{-- Gambar --}}
                @if(!empty($stok->gambar))
                <div class="mb-4 shadow-sm" style="border-radius: 20px; overflow: hidden; background: white; padding: 10px;">
                    <img src="{{ asset('storage/' . $stok->gambar) }}" alt="Foto {{ $stok->jenisSampah->nama ?? 'Material' }}" class="img-cover" style="height: 450px;">
                </div>
                @endif

                {{-- Info Detail Card --}}
                <div class="detail-card p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-4 border-bottom">
                        <div>
                            <h3 class="fw-bold text-dark mb-2 fs-4">Informasi Material</h3>
                            <p class="text-muted mb-0 small">Detail spesifikasi stok sampah yang tersedia.</p>
                        </div>
                        <div class="text-md-end bg-light rounded-4 px-4 py-3 border">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Status Ketersediaan</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-block bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                                <span class="fw-bold text-success">Siap Angkut</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Stok --}}
                    <div class="info-box mb-4 bg-white shadow-sm">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <span class="d-block fw-semibold text-muted small text-uppercase letter-spacing-1 mb-1">Volume Tersedia</span>
                                <span class="fw-bold text-dark fs-3">{{ number_format($stok->stok_tersisa_kg ?? 0, 2, ',', '.') }} <span class="fs-6 text-muted">kg</span></span>
                            </div>
                            <div class="text-end">
                                {{-- PERBAIKAN: Membatasi maksimal persentase agar UI bar tidak jebol melebihi 100% --}}
                                @php 
                                    $persen = (isset($stok->stok_masuk_kg) && $stok->stok_masuk_kg > 0) 
                                                ? min(($stok->stok_tersisa_kg / $stok->stok_masuk_kg) * 100, 100) 
                                                : 0; 
                                @endphp
                                <span class="badge bg-light text-dark border px-2 py-1">{{ number_format($persen, 0) }}% Tersisa</span>
                            </div>
                        </div>
                        
                        <div style="height: 14px; background: #e2e8f0; border-radius: 99px; overflow: hidden; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);">
                            <div class="progress-bar-custom" style="height: 100%; width: {{ $persen }}%;"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top small">
                            <div class="text-muted"><strong class="text-dark">{{ number_format($stok->stok_masuk_kg ?? 0, 2, ',', '.') }} kg</strong> Total Masuk</div>
                            <div class="text-muted"><strong class="text-dark">{{ number_format($stok->stok_terjual_kg ?? 0, 2, ',', '.') }} kg</strong> Terjual</div>
                        </div>
                    </div>

                    {{-- Info Grid --}}
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="info-box d-flex align-items-center gap-3">
                                <div class="bg-white p-3 rounded-circle border shadow-sm flex-shrink-0 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <div>
                                    <div class="text-muted small fw-semibold text-uppercase mb-1">Tgl. Registrasi</div>
                                    <div class="fw-bold text-dark">
                                        {{ !empty($stok->tanggal_masuk) ? \Carbon\Carbon::parse($stok->tanggal_masuk)->locale('id')->translatedFormat('d F Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box d-flex align-items-center gap-3">
                                <div class="bg-white p-3 rounded-circle border shadow-sm flex-shrink-0 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </div>
                                <div>
                                    <div class="text-muted small fw-semibold text-uppercase mb-1">Kualitas</div>
                                    <div class="fw-bold text-dark">{{ !empty($stok->is_pres) ? 'Telah Di-Press' : 'Standar (Non-Press)' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($stok->keterangan))
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-dark mb-2">Keterangan Tambahan</h6>
                            <div class="info-box bg-white">
                                <p class="mb-0 text-slate lh-base">{{ $stok->keterangan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Samping (Sticky Right) --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 1020;">
                    
                    {{-- Card Harga --}}
                    <div class="detail-card p-4 mb-4 text-center border-top border-4 border-success">
                        <div class="text-muted fw-semibold text-uppercase small mb-2 letter-spacing-1">Estimasi Harga Jual</div>
                        <div class="display-5 fw-800 text-success mb-0" style="letter-spacing: -1px;">Rp {{ number_format($stok->harga_jual_per_kg ?? 0, 0, ',', '.') }}</div>
                        <div class="text-muted">per Kilogram</div>
                        
                        <hr class="my-4 opacity-10">
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 text-start">
                            <span class="text-muted fw-medium">Minimal Pengambilan</span>
                            <span class="fw-bold text-dark bg-light px-3 py-1 rounded-pill">1.00 kg</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-start">
                            <span class="text-muted fw-medium">Estimasi Total Nilai</span>
                            <span class="fw-bold text-dark">Rp {{ number_format(($stok->stok_tersisa_kg ?? 0) * ($stok->harga_jual_per_kg ?? 0), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Card CTA --}}
                    <div class="detail-card p-4 text-white text-center" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark));">
                        <div class="bg-white text-primary rounded-circle d-inline-flex p-3 mb-3 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <h4 class="fw-bold mb-2">Tertarik Mengambil?</h4>
                        <p class="mb-4 opacity-75 small lh-base">Hubungi pengelola Bank Sampah Digital Subang untuk negosiasi dan jadwal pengambilan.</p>
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Bank%20Sampah,%20saya%20tertarik%20dengan%20stok%20{{ urlencode($stok->jenisSampah->nama ?? 'Material') }}" target="_blank" class="btn btn-light w-100 fw-bold rounded-pill py-3 text-success shadow-sm d-flex align-items-center justify-content-center gap-2" style="transition: transform 0.2s;">
                            Chat via WhatsApp
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STOK LAINNYA --}}
{{-- PERBAIKAN: Memastikan variabel $stokLain ada sebelum dihitung --}}
@if(isset($stokLain) && $stokLain->count() > 0)
<section style="padding: 80px 0; background: #fff; border-top: 1px solid rgba(0,0,0,0.05);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h3 class="fw-bold text-dark mb-1">Rekomendasi Material Lain</h3>
                <p class="text-muted mb-0">Temukan stok sampah serupa yang tersedia saat ini.</p>
            </div>
            <a href="{{ route('publik.stok') ?? '#' }}" class="btn btn-outline-success rounded-pill px-4 fw-semibold d-none d-sm-inline-block">Lihat Semua</a>
        </div>
        
        <div class="row g-4">
            @foreach($stokLain as $item)
            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('publik.stok.detail', $item->slug ?? '') }}" class="text-decoration-none h-100 d-block">
                    <div class="stok-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $item->jenisSampah->nama ?? '-' }}</h5>
                                <span class="badge bg-light text-muted border rounded-pill small">{{ $item->jenisSampah->kategori ?? '' }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold fs-5 text-success">Rp {{ number_format($item->harga_jual_per_kg ?? 0, 0, ',', '.') }}</div>
                                <div class="small text-muted">/kg</div>
                            </div>
                        </div>
                        
                        @if(!empty($item->gambar))
                        <div class="mb-4 mt-auto rounded-4 overflow-hidden shadow-sm border border-light" style="height: 140px;">
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="Foto" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        @else
                        <div class="mb-4 mt-auto rounded-4 overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 140px; border: 1px dashed #cbd5e1;">
                            <span class="text-muted small">Tanpa Gambar</span>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                            <span class="text-dark fw-semibold small">{{ number_format($item->stok_tersisa_kg ?? 0, 1, ',', '.') }} kg tersedia</span>
                            <span class="badge badge-tersedia rounded-pill" style="font-size: 0.7rem;">TERSEDIA</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5 d-block d-sm-none">
            <a href="{{ route('publik.stok') ?? '#' }}" class="btn btn-outline-success rounded-pill px-4 fw-bold w-100">
                Lihat Semua Stok
            </a>
        </div>
    </div>
</section>
@endif

{{-- FOOTER --}}
<footer style="background: #020617; color: rgba(255,255,255,0.7); padding: 40px 0; text-align: center; font-size: 0.9rem;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo" style="width:24px;height:24px;border-radius:6px; filter: grayscale(100%) opacity(0.7);">
            <span class="fw-semibold tracking-wide text-uppercase" style="letter-spacing: 1px;">Bank Sampah Subang</span>
        </div>
        &copy; {{ date('Y') }} Hak Cipta Dilindungi.<br>
        <span class="small opacity-50">Sistem Informasi Pengelolaan Sampah Terpadu</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>