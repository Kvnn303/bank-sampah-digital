<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#10b981">
    <meta name="description" content="Detail spesifikasi dan ketersediaan material daur ulang {{ $stok->jenisSampah->nama ?? 'Sampah' }} di Bank Sampah Digital Subang">
    <link rel="icon" type="image/png" href="{{ asset('image/BankSampahlogo.png') }}">
    <title>{{ $stok->jenisSampah->nama ?? 'Detail Stok Material' }} — Bank Sampah Digital Subang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --primary: #10b981; 
            --primary-dark: #059669; 
            --primary-light: #dcfce7;
            --dark: #0f172a; 
            --slate: #475569; 
            --surface: #f8fafc; 
            --border: #e2e8f0;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--slate); background: var(--surface); -webkit-font-smoothing: antialiased; }
        
        /* Navbar */
        .navbar-brand-custom { font-weight: 800; font-size: 1.2rem; color: var(--dark) !important; text-decoration: none; }
        .btn-masuk { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white !important; border-radius: 50rem; padding: 0.6rem 1.8rem; font-weight: 700; text-decoration: none; transition: all 0.3s; border: none; box-shadow: 0 4px 12px rgba(16,185,129,0.2); }
        .btn-masuk:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,185,129,0.35); color: white; }
        
        /* Badges */
        .badge-press { background: rgba(139,92,246,0.12); color: #7c3aed; border: 1px solid rgba(139,92,246,0.25); font-weight: 700; letter-spacing: 0.5px;}
        .badge-tersedia { background: rgba(16,185,129,0.12); color: #059669; border: 1px solid rgba(16,185,129,0.25); font-weight: 700; letter-spacing: 0.5px;}
        .badge-eco { background: rgba(14,165,233,0.12); color: #0284c7; border: 1px solid rgba(14,165,233,0.25); font-weight: 700; }
        
        /* Cards */
        .stok-card { background: white; border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); height: 100%; display: flex; flex-direction: column;}
        .stok-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); }
        .detail-card { background: white; border-radius: 24px; box-shadow: 0 12px 40px rgba(0,0,0,0.03); border: 1px solid var(--border); }
        
        /* Info Box */
        .info-box { background: var(--surface); border-radius: 16px; padding: 1.25rem; border: 1px solid var(--border); transition: border-color 0.2s; }
        .info-box:hover { border-color: #cbd5e1; }
        
        /* Hero Section */
        .hero-section { position: relative; background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%); padding: 70px 0 60px; color: white; overflow: hidden; }
        .hero-decoration { position: absolute; top: -80px; right: -80px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(16,185,129,0.15) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none; }
        
        /* Progress & Utility */
        .progress-bar-custom { background: linear-gradient(90deg, #10b981, #34d399); border-radius: 99px; transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .img-cover { object-fit: cover; width: 100%; border-radius: 20px; border: 1px solid rgba(0,0,0,0.05); }
        
        /* Kalkulator Box */
        .calc-input-group { border: 2px solid var(--border); border-radius: 14px; overflow: hidden; transition: all 0.2s; background: #fff; }
        .calc-input-group:focus-within { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(16,185,129,0.15); }
        .calc-input { border: none; font-weight: 700; font-size: 1.25rem; color: var(--dark); text-align: center; width: 100%; padding: 10px; outline: none; }
        .calc-btn { background: var(--surface); border: none; font-weight: 700; color: var(--slate); width: 48px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; transition: all 0.2s; }
        .calc-btn:hover { background: #e2e8f0; color: var(--dark); }

        .eco-impact-box { background: linear-gradient(135deg, #f0fdf4, #ecfeff); border: 1px solid #a7f3d0; border-radius: 16px; padding: 16px; }
        
        @media (max-width: 768px) { 
            .hero-section { padding: 50px 0 40px; } 
            .img-cover { height: 280px !important; }
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
            <a href="{{ route('publik.stok') ?? '#' }}" class="btn btn-light rounded-pill fw-semibold border text-success px-4 d-inline-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                Kembali ke Katalog
            </a>
            <a href="{{ route('admin.login') ?? '#' }}" class="btn-masuk d-none d-sm-block">Masuk Admin</a>
        </div>
    </div>
</nav>

{{-- HERO / BREADCRUMB --}}
<section class="hero-section">
    <div class="hero-decoration"></div>
    <div class="container position-relative z-1">
        <nav style="--bs-breadcrumb-divider: '›'; font-size: 0.88rem;" aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="/" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('publik.stok') ?? '#' }}" class="text-white-50 text-decoration-none">Stok Tersedia</a></li>
                <li class="breadcrumb-item text-white fw-semibold" aria-current="page">{{ $stok->jenisSampah->nama ?? 'Material' }}</li>
            </ol>
        </nav>
        
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            @if($stok->is_pres ?? false)
            <span class="badge badge-press px-3 py-2 rounded-pill d-inline-flex align-items-center" style="font-size: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                STOK TER-PRESS (READY BALES)
            </span>
            @else
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill border fw-bold" style="font-size: 0.75rem;">
                MATERIAL CURAH / STANDAR
            </span>
            @endif

            <span class="badge badge-eco px-3 py-2 rounded-pill d-inline-flex align-items-center" style="font-size: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="me-2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                KUALITAS TERPILIH
            </span>
        </div>
        
        <h1 class="fw-800 text-white mb-3" style="font-size: clamp(2.2rem, 5vw, 3.2rem); letter-spacing: -0.8px;">{{ $stok->jenisSampah->nama ?? 'Detail Material' }}</h1>
        
        <div class="d-flex flex-wrap align-items-center gap-4 text-white-50 fs-6">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                <span>Kategori: <strong class="text-white">{{ $stok->jenisSampah->kategori ?? 'Daur Ulang' }}</strong></span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-info" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Update Terakhir: <strong class="text-white">{{ !empty($stok->updated_at) ? \Carbon\Carbon::parse($stok->updated_at)->locale('id')->diffForHumans() : 'Baru saja' }}</strong></span>
            </div>
        </div>
    </div>
</section>

{{-- DETAIL STOK & KALKULATOR INTERAKTIF --}}
<section style="padding: 60px 0; margin-top: -30px;">
    <div class="container">
        <div class="row g-4 g-lg-5">
            
            {{-- Kolom Kiri: Visual & Spesifikasi Teknis --}}
            <div class="col-lg-7">
                
                {{-- Gambar Material --}}
                @if(!empty($stok->gambar))
                <div class="mb-4 shadow-sm position-relative" style="border-radius: 24px; overflow: hidden; background: white; padding: 12px;">
                    <img src="{{ asset('storage/' . $stok->gambar) }}" alt="Foto {{ $stok->jenisSampah->nama ?? 'Material' }}" class="img-cover" style="height: 440px;">
                    <div class="position-absolute bottom-0 start-0 m-4 px-3 py-2 rounded-pill bg-dark bg-opacity-75 text-white small fw-semibold backdrop-blur">
                        📸 Foto Asli Gudang Bank Sampah Subang
                    </div>
                </div>
                @endif

                {{-- Card Spesifikasi Utama --}}
                <div class="detail-card p-4 p-md-5 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-4 border-bottom">
                        <div>
                            <h3 class="fw-bold text-dark mb-1 fs-4">Ketersediaan & Spesifikasi</h3>
                            <p class="text-muted mb-0 small">Transparansi volume material dan riwayat perputaran stok.</p>
                        </div>
                        <div class="text-md-end bg-light rounded-4 px-4 py-3 border">
                            <div class="text-muted small fw-semibold text-uppercase mb-1">Status Gudang</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-block bg-success rounded-circle" style="width: 10px; height: 10px; box-shadow: 0 0 0 4px rgba(16,185,129,0.2);"></span>
                                <span class="fw-bold text-success">Siap Diangkut / Ready Stock</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Volume --}}
                    <div class="info-box mb-4 bg-white shadow-sm border">
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <span class="d-block fw-semibold text-muted small text-uppercase mb-1">Volume Tersedia Saat Ini</span>
                                <span class="fw-bold text-dark fs-2">{{ number_format($stok->stok_tersisa_kg ?? 0, 2, ',', '.') }} <span class="fs-5 text-muted fw-normal">kg</span></span>
                            </div>
                            <div class="text-end">
                                @php 
                                    $persen = (isset($stok->stok_masuk_kg) && $stok->stok_masuk_kg > 0) 
                                                ? min(($stok->stok_tersisa_kg / $stok->stok_masuk_kg) * 100, 100) 
                                                : 0; 
                                @endphp
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fs-6">
                                    {{ number_format($persen, 0) }}% Kapasitas Tersisa
                                </span>
                            </div>
                        </div>
                        
                        <div style="height: 16px; background: #f1f5f9; border-radius: 99px; overflow: hidden; box-shadow: inset 0 1px 3px rgba(0,0,0,0.06);">
                            <div class="progress-bar-custom" style="height: 100%; width: {{ $persen }}%;"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top small">
                            <div class="text-muted">Total Akumulasi Masuk: <strong class="text-dark">{{ number_format($stok->stok_masuk_kg ?? 0, 2, ',', '.') }} kg</strong></div>
                            <div class="text-muted">Total Telah Terjual/Terolah: <strong class="text-dark">{{ number_format($stok->stok_terjual_kg ?? 0, 2, ',', '.') }} kg</strong></div>
                        </div>
                    </div>

                    {{-- Info Grid --}}
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="info-box d-flex align-items-center gap-3 h-100">
                                <div class="bg-white p-3 rounded-4 border shadow-sm flex-shrink-0 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <div>
                                    <div class="text-muted small fw-semibold text-uppercase mb-1">Tanggal Registrasi</div>
                                    <div class="fw-bold text-dark">
                                        {{ !empty($stok->tanggal_masuk) ? \Carbon\Carbon::parse($stok->tanggal_masuk)->locale('id')->translatedFormat('d F Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box d-flex align-items-center gap-3 h-100">
                                <div class="bg-white p-3 rounded-4 border shadow-sm flex-shrink-0 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                </div>
                                <div>
                                    <div class="text-muted small fw-semibold text-uppercase mb-1">Kondisi Pengemasan</div>
                                    <div class="fw-bold text-dark">{{ !empty($stok->is_pres) ? 'Padat Di-Press (Bales)' : 'Curah / Standar Sortir' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Estimasi Dampak Lingkungan (Eco-Impact UI/UX Pro Max) --}}
                    @php
                        $emisiHemat = ($stok->stok_tersisa_kg ?? 0) * 1.85; // Estimasi 1.85 kg CO2 per kg material
                    @endphp
                    <div class="eco-impact-box mb-4 d-flex align-items-center gap-3">
                        <div class="bg-success text-white rounded-circle p-3 flex-shrink-0 d-flex align-items-center justify-content-center shadow-sm" style="width:52px;height:52px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                        </div>
                        <div>
                            <div class="fw-bold text-dark mb-1">Potensi Penyelamatan Lingkungan 🌱</div>
                            <p class="mb-0 text-slate small lh-sm">
                                Memanfaatkan <strong class="text-success">{{ number_format($stok->stok_tersisa_kg ?? 0, 1, ',', '.') }} kg</strong> material ini dapat mencegah sekitar <strong class="text-success">{{ number_format($emisiHemat, 1, ',', '.') }} kg emisi CO₂</strong> serta mengurangi jejak limbah TPA di Kabupaten Subang.
                            </p>
                        </div>
                    </div>
                    
                    @if(!empty($stok->keterangan))
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-2">Catatan & Keterangan Pengelola</h6>
                        <div class="info-box bg-white">
                            <p class="mb-0 text-slate lh-base">{{ $stok->keterangan }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Kalkulator Pemesanan Interaktif (Sticky Right) --}}
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 100px; z-index: 1020;">
                    
                    <div class="detail-card p-4 p-md-5 mb-4 border-top border-4 border-success">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold">KALKULATOR PEMESANAN</span>
                            <span class="small text-muted fw-semibold">Harga Resmi BSU</span>
                        </div>

                        <div class="mb-4">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Harga Satuan Material</div>
                            <div class="display-5 fw-800 text-success mb-0" style="letter-spacing: -1px;">Rp {{ number_format($stok->harga_jual_per_kg ?? 0, 0, ',', '.') }}</div>
                            <div class="text-muted small">per Kilogram (Kg)</div>
                        </div>

                        <hr class="my-4 opacity-10">

                        {{-- Form Simulasi Input Kg --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase d-flex justify-content-between">
                                <span>Tentukan Bobot Pengambilan (Kg)</span>
                                <span class="text-muted fw-normal">Max: {{ number_format($stok->stok_tersisa_kg ?? 0, 1, ',', '.') }} kg</span>
                            </label>
                            
                            <div class="d-flex align-items-stretch calc-input-group mb-2 shadow-sm">
                                <button type="button" class="calc-btn" onclick="adjustKg(-10)" title="Kurangi 10 Kg">−</button>
                                <input type="number" id="inputKg" class="calc-input" 
                                       min="1" max="{{ $stok->stok_tersisa_kg ?? 0 }}" 
                                       value="{{ min(10, $stok->stok_tersisa_kg ?? 1) }}" 
                                       oninput="calculatePrice()">
                                <button type="button" class="calc-btn" onclick="adjustKg(10)" title="Tambah 10 Kg">+</button>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center px-1">
                                <small class="text-muted" id="calcStatus">Minimal pengambilan disarankan 1 Kg</small>
                                <a href="javascript:void(0)" onclick="setMaxKg({{ $stok->stok_tersisa_kg ?? 0 }})" class="small fw-bold text-success text-decoration-none">Ambil Semua Stok</a>
                            </div>
                        </div>

                        {{-- Total Estimasi Biaya --}}
                        <div class="bg-light p-4 rounded-4 border mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-medium">Total Bobot Dipilih</span>
                                <span class="fw-bold text-dark" id="displayKg">10 Kg</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small fw-medium">Harga per Kg</span>
                                <span class="fw-medium text-dark">Rp {{ number_format($stok->harga_jual_per_kg ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">Estimasi Total Biaya</span>
                                <span class="fs-4 fw-800 text-success" id="displayTotalRp">Rp 0</span>
                            </div>
                        </div>

                        {{-- Tombol CTA WhatsApp Dinamis --}}
                        <a id="waButton" href="#" target="_blank" 
                           class="btn btn-success w-100 fw-bold rounded-pill py-3 text-white shadow-lg d-flex align-items-center justify-content-center gap-2" 
                           style="background: linear-gradient(135deg, #10b981, #059669); border: none; font-size: 1.05rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            Ajukan Pemesanan via WhatsApp
                        </a>
                        <p class="text-center text-muted small mt-3 mb-0">🔒 Harga & jadwal pengambilan akan diverifikasi langsung bersama pengelola gudang BSU.</p>
                    </div>

                    {{-- Card Bantuan & Keamanan --}}
                    <div class="detail-card p-4 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Jaminan Kualitas Material</h6>
                                <p class="text-muted small mb-0 lh-sm">Material telah ditimbang dan disortir dengan standar higienis di fasilitas Bank Sampah Subang.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- REKOMENDASI STOK LAINNYA --}}
@if(isset($stokLain) && $stokLain->count() > 0)
<section style="padding: 80px 0; background: #fff; border-top: 1px solid var(--border);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h3 class="fw-bold text-dark mb-1">Rekomendasi Material Serupa</h3>
                <p class="text-muted mb-0">Jelajahi stok sampah daur ulang lain yang siap diangkut.</p>
            </div>
            <a href="{{ route('publik.stok') ?? '#' }}" class="btn btn-outline-success rounded-pill px-4 fw-semibold d-none d-sm-inline-block">Lihat Semua Katalog</a>
        </div>
        
        <div class="row g-4">
            @foreach($stokLain as $item)
            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('publik.stok.detail', $item->slug ?? '') }}" class="text-decoration-none h-100 d-block">
                    <div class="stok-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $item->jenisSampah->nama ?? '-' }}</h5>
                                <span class="badge bg-light text-muted border rounded-pill small">{{ $item->jenisSampah->kategori ?? 'Daur Ulang' }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold fs-5 text-success">Rp {{ number_format($item->harga_jual_per_kg ?? 0, 0, ',', '.') }}</div>
                                <div class="small text-muted">/kg</div>
                            </div>
                        </div>
                        
                        @if(!empty($item->gambar))
                        <div class="mb-4 mt-auto rounded-4 overflow-hidden shadow-sm border border-light" style="height: 160px;">
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="Foto" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        @else
                        <div class="mb-4 mt-auto rounded-4 overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 160px; border: 1px dashed #cbd5e1;">
                            <span class="text-muted small">Tanpa Gambar</span>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                            <span class="text-dark fw-semibold small">{{ number_format($item->stok_tersisa_kg ?? 0, 1, ',', '.') }} kg tersedia</span>
                            <span class="badge badge-tersedia rounded-pill" style="font-size: 0.7rem;">READY STOCK</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5 d-block d-sm-none">
            <a href="{{ route('publik.stok') ?? '#' }}" class="btn btn-outline-success rounded-pill px-4 fw-bold w-100">
                Lihat Semua Katalog
            </a>
        </div>
    </div>
</section>
@endif

{{-- FOOTER --}}
<footer style="background: #020617; color: rgba(255,255,255,0.7); padding: 45px 0; text-align: center; font-size: 0.9rem;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <img src="{{ asset('image/BankSampahlogo.png') }}" alt="Logo" style="width:28px;height:28px;border-radius:6px;">
            <span class="fw-bold tracking-wide text-uppercase text-white" style="letter-spacing: 1px;">Bank Sampah Digital Subang</span>
        </div>
        <p class="small opacity-75 mb-3 max-w-md mx-auto" style="max-width: 500px;">
            Sistem Informasi Terpadu Pelayanan Daur Ulang dan Pemberdayaan Ekonomi Lingkungan Masyarakat Kabupaten Subang.
        </p>
        &copy; {{ date('Y') }} Hak Cipta Dilindungi Pemerintah Kabupaten Subang.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- SCRIPT KALKULATOR INTERAKTIF --}}
<script>
    const maxStokKg = {{ $stok->stok_tersisa_kg ?? 0 }};
    const hargaPerKg = {{ $stok->harga_jual_per_kg ?? 0 }};
    const namaMaterial = @json($stok->jenisSampah->nama ?? 'Material Sampah');
    const waNomor = "6281234567890"; // Nomor WhatsApp Pengelola

    function adjustKg(delta) {
        const input = document.getElementById('inputKg');
        let current = parseFloat(input.value) || 0;
        let result = current + delta;
        if (result < 1) result = 1;
        if (result > maxStokKg) result = maxStokKg;
        input.value = result;
        calculatePrice();
    }

    function setMaxKg(val) {
        document.getElementById('inputKg').value = val;
        calculatePrice();
    }

    function calculatePrice() {
        const input = document.getElementById('inputKg');
        let kg = parseFloat(input.value) || 0;
        
        if (kg > maxStokKg) {
            kg = maxStokKg;
            input.value = kg;
        } else if (kg < 0) {
            kg = 1;
            input.value = kg;
        }

        const totalRp = kg * hargaPerKg;

        // Format Rupiah Indonesia
        const formattedRp = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(totalRp);

        document.getElementById('displayKg').innerText = kg + " Kg";
        document.getElementById('displayTotalRp').innerText = formattedRp;

        // Update Pesan WhatsApp Dinamis
        const waText = `Halo Admin Bank Sampah Subang, saya tertarik untuk memesan/mengambil stok material:\n\n` +
                       `📦 *Material:* ${namaMaterial}\n` +
                       `⚖️ *Volume Pesanan:* ${kg} Kg\n` +
                       `💰 *Estimasi Biaya:* ${formattedRp}\n\n` +
                       `Mohon informasi konfirmasi ketersediaan dan jadwal pengambilannya di gudang. Terima kasih!`;
                       
        document.getElementById('waButton').href = `https://wa.me/${waNomor}?text=${encodeURIComponent(waText)}`;
    }

    // Inisialisasi awal saat load
    document.addEventListener('DOMContentLoaded', () => {
        calculatePrice();
    });
</script>
</body>
</html>