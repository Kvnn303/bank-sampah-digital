<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#10b981">
    <link rel="icon" type="image/png" href="{{ asset('image/BankSampahlogo.png') }}">
    <title>Stok Tersedia - Bank Sampah Digital Subang</title>
    
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
        
        /* Hero Section */
        .hero-stok { position: relative; background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%); padding: 90px 0 70px; color: white; overflow: hidden; }
        .hero-decoration { position: absolute; top: -50px; right: -50px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; }
        .stat-stok { background: rgba(255,255,255,0.05); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 1.5rem 2rem; text-align: center; transition: transform 0.3s; }
        .stat-stok:hover { transform: translateY(-3px); background: rgba(255,255,255,0.08); }
        
        /* Badges */
        .badge-press { background: rgba(139,92,246,0.1); color: #7c3aed; border: 1px solid rgba(139,92,246,0.2); font-weight: 700; letter-spacing: 0.5px;}
        .badge-tersedia { background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.2); font-weight: 700; letter-spacing: 0.5px;}
        
        /* Cards */
        .stok-card { background: white; border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); height: 100%; display: flex; flex-direction: column;}
        .stok-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.2); }
        
        /* Utility */
        .progress-stok { height: 10px; border-radius: 99px; background: #e2e8f0; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
        .progress-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--primary), #34d399); transition: width 1s ease; }
        
        @media (max-width: 768px) { .hero-stok { padding: 60px 0 40px; } }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand-custom d-flex align-items-center gap-3" href="/">
            <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo" style="width:42px;height:42px;border-radius:12px;box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <span class="d-none d-sm-block">Bank Sampah Subang</span>
        </a>
        <div class="d-flex gap-2 align-items-center">
            <a href="/" class="btn btn-light rounded-pill fw-semibold border d-none d-md-block px-4">Beranda</a>
            <a href="{{ route('admin.login') }}" class="btn-masuk d-none d-sm-block">Masuk Admin</a>
        </div>
    </div>
</nav>

{{-- HERO --}}
<section class="hero-stok">
    <div class="hero-decoration"></div>
    <div class="container position-relative z-1">
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center badge badge-press px-3 py-2 rounded-pill mb-3" style="font-size: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                MATERIAL TER-PRESS
            </div>
            <h1 class="fw-800 text-white mb-3" style="font-size: clamp(2rem, 5vw, 3.5rem); letter-spacing: -1px;">Stok Material Siap Beli</h1>
            <p class="text-white-50 mx-auto mb-0" style="font-size: 1.1rem; max-width: 600px;">
                Daftar sampah yang telah di-press dan dikelompokkan. Ter-update secara realtime dari sistem gudang kami.
            </p>
        </div>
        
        <div class="row g-3 justify-content-center mt-2">
            <div class="col-6 col-md-auto">
                <div class="stat-stok">
                    <div class="h2 fw-800 text-white mb-1">{{ number_format($totalBerat ?? 0, 1, ',', '.') }} <span style="font-size:1rem; opacity:0.7;">kg</span></div>
                    <div class="small text-white-50 fw-medium">Total Volume</div>
                </div>
            </div>
            <div class="col-6 col-md-auto">
                <div class="stat-stok">
                    <div class="h2 fw-800 text-white mb-1">{{ $stokList->count() }}</div>
                    <div class="small text-white-50 fw-medium">Jenis Material</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- DAFTAR STOK --}}
<section style="padding: 80px 0; min-height: 50vh;">
    <div class="container">
        @if($stokList->isEmpty())
        <div class="text-center py-5 my-5">
            <img src="{{ asset('image/empty-stok.svg') }}" onerror="this.style.display='none'" alt="" style="width:140px;margin-bottom:1.5rem;opacity:0.3;">
            <h4 class="text-dark fw-bold mb-2">Belum Ada Stok Tersedia</h4>
            <p class="text-muted mb-4">Stok material yang sudah di-press dan siap dijual akan muncul di halaman ini.</p>
            <a href="/" class="btn btn-outline-success rounded-pill px-4 fw-semibold border-2">Kembali ke Beranda</a>
        </div>
        @else
        <div class="row g-4 g-xl-5">
            @foreach($stokList as $stok)
            <div class="col-md-6 col-lg-4 d-flex">
                <a href="{{ route('publik.stok.detail', $stok->slug) }}" class="text-decoration-none w-100 d-block">
                    <div class="stok-card">
                        
                        {{-- Header Card --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $stok->jenisSampah->nama ?? '-' }}</h5>
                                <span class="badge bg-light text-muted border rounded-pill small">{{ $stok->jenisSampah->kategori ?? '' }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold fs-5 text-success">Rp {{ number_format($stok->harga_jual_per_kg, 0, ',', '.') }}</div>
                                <div class="small text-muted">/kg</div>
                            </div>
                        </div>

                        {{-- Foto --}}
                        @if($stok->gambar)
                        <div class="mb-4 rounded-4 overflow-hidden shadow-sm border border-light" style="height: 160px;">
                            <img src="{{ asset('storage/' . $stok->gambar) }}" alt="Foto {{ $stok->jenisSampah->nama }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        @else
                        <div class="mb-4 rounded-4 overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 160px; border: 1px dashed #cbd5e1;">
                            <span class="text-muted small fw-medium">Tanpa Gambar</span>
                        </div>
                        @endif

                        {{-- Info Berat & Progress --}}
                        <div class="bg-light rounded-4 p-3 mb-4 mt-auto">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span class="small text-muted fw-semibold text-uppercase" style="letter-spacing: 0.5px;">Tersedia</span>
                                <span class="fw-bold text-dark fs-5">{{ number_format($stok->stok_tersisa_kg, 1, ',', '.') }} <span class="fs-6 text-muted">kg</span></span>
                            </div>
                            <div class="progress-stok mb-2">
                                @php $persen = $stok->stok_masuk_kg > 0 ? ($stok->stok_tersisa_kg / $stok->stok_masuk_kg) * 100 : 0; @endphp
                                <div class="progress-fill" style="width: {{ $persen }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span class="small text-muted fw-medium">Terjual: {{ number_format($stok->stok_terjual_kg, 1, ',', '.') }} kg</span>
                                <span class="badge badge-tersedia rounded-pill" style="font-size: 0.65rem;">TERSEDIA</span>
                            </div>
                        </div>

                        {{-- Footer Card --}}
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <div class="small text-muted fw-medium d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                {{ \Carbon\Carbon::parse($stok->tanggal_masuk)->locale('id')->translatedFormat('d M Y') }}
                            </div>
                            @if($stok->keterangan)
                            <div class="small text-muted bg-white border px-2 py-1 rounded text-truncate" style="max-width: 120px;" title="{{ $stok->keterangan }}">
                                {{ $stok->keterangan }}
                            </div>
                            @endif
                        </div>
                        
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section style="padding: 80px 0; background: white; border-top: 1px solid rgba(0,0,0,0.05);">
    <div class="container">
        <div class="bg-light rounded-4 p-5 text-center border">
            <h3 class="fw-bold text-dark mb-2">Ingin Bekerja Sama Sebagai Pengepul?</h3>
            <p class="text-muted mb-4 max-w-lg mx-auto">Kami membuka peluang kemitraan untuk pengambilan material sampah secara rutin dengan harga dan kualitas terbaik.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success rounded-pill px-5 fw-bold py-3 shadow-sm d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Hubungi Kami
                </a>
                <a href="/" class="btn btn-outline-secondary rounded-pill px-5 fw-bold py-3 border-2">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer style="background: #020617; color: rgba(255,255,255,0.7); padding: 40px 0; text-align: center; font-size: 0.9rem;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo" style="width:24px;height:24px;border-radius:6px; filter: grayscale(100%) opacity(0.7);">
            <span class="fw-semibold text-uppercase" style="letter-spacing: 1px;">Bank Sampah Subang</span>
        </div>
        &copy; {{ date('Y') }} Hak Cipta Dilindungi.<br>
        <span class="small opacity-50">Sistem Informasi Pengelolaan Sampah Terpadu</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>