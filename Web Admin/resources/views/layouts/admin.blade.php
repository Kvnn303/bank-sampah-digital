<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('image/BankSampahlogo.png') }}">
    <title>@yield('title', 'Dashboard') — Bank Sampah Digital Subang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --primary: #10b981;
            --primary-hover: #059669;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --body-bg: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--text-main);
        }

        .navbar-vertical {
            background: var(--sidebar-bg);
            border-right: none;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            position: relative;
        }

        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.25rem;
            right: 1.25rem;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }

        .logo-wrapper {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #ffffff, #f1f5f9);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 2px;
        }

        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .brand-text {
            margin-left: 12px;
            line-height: 1.2;
        }

        .brand-title {
            display: block;
            color: #f8fafc;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 0.2px;
        }

        .brand-subtitle {
            display: block;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 500;
            margin-top: 2px;
        }

        .nav-item .nav-link {
            display: flex;
            align-items: center;
            color: #94a3b8;
            padding: 12px 16px;
            margin: 6px 16px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nav-item .nav-link:hover {
            color: #ffffff;
            background: var(--sidebar-hover);
            transform: translateX(4px);
        }

        .nav-item .nav-link.active {
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
        }

        .nav-link-icon {
            width: 20px;
            height: 20px;
            margin-right: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.7;
            transition: opacity 0.3s, transform 0.3s;
        }

        .nav-item .nav-link:hover .nav-link-icon {
            opacity: 1;
            transform: scale(1.1);
        }

        .nav-item .nav-link.active .nav-link-icon {
            opacity: 1;
        }

        .nav-item-header {
            padding: 24px 20px 8px;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: #475569;
            font-weight: 700;
        }

        .navbar-top {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 0.875rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .dropdown-menu {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
            padding: 0.5rem;
            animation: dropdownFadeIn 0.2s ease-out;
        }

        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--primary);
        }

        .notif-btn {
            color: #64748b;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
        }

        .notif-btn:hover {
            color: var(--primary);
            background: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        @keyframes ring {
            0% { transform: rotate(0); }
            10% { transform: rotate(15deg); }
            20% { transform: rotate(-10deg); }
            30% { transform: rotate(5deg); }
            40% { transform: rotate(-5deg); }
            50% { transform: rotate(0); }
            100% { transform: rotate(0); }
        }

        .has-notif .notif-icon {
            animation: ring 2s ease-in-out infinite;
            color: var(--primary);
        }

        .notif-badge-indicator {
            font-size: 10px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            border: 2px solid #ffffff;
            font-weight: 700;
            background: #ef4444;
            color: white;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
            top: -2px !important;
            right: -2px !important;
        }

        @keyframes pulse-badge {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .has-notif .notif-badge-indicator {
            animation: pulse-badge 2s infinite;
        }

        .notif-dropdown {
            width: 380px;
            max-width: 95vw;
            padding: 0;
            overflow: hidden;
        }

        .notif-scroll {
            max-height: 350px;
            overflow-y: auto;
        }

        .notif-scroll::-webkit-scrollbar { width: 5px; }
        .notif-scroll::-webkit-scrollbar-track { background: transparent; }
        .notif-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .notif-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .user-profile-btn {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 4px 12px 4px 4px;
            border-radius: 30px;
            transition: all 0.2s;
        }

        .user-profile-btn:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-color: #cbd5e1;
        }

        .page-title {
            letter-spacing: -0.5px;
            color: #0f172a;
        }

        /* PERBAIKAN UNTUK MOBILE */
        @media (max-width: 991.98px) {
            .navbar-vertical {
                position: fixed;
                top: 0;
                left: 0;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                height: 100vh;
                z-index: 1050; /* Di atas navbar top */
                width: 280px;
                display: flex !important;
            }
            .sidebar-show .navbar-vertical {
                transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0,0,0,0.2);
            }
            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(15, 23, 42, 0.5);
                backdrop-filter: blur(4px);
                z-index: 1040;
                display: none;
                opacity: 0;
                transition: opacity 0.3s;
            }
            .sidebar-show .sidebar-backdrop {
                display: block;
                opacity: 1;
            }

            /* Menghentikan body dari scrolling saat sidebar terbuka */
            body.sidebar-open {
                overflow: hidden;
            }

            /* Memastikan judul tidak tumpang tindih dengan notifikasi di HP kecil */
            .navbar-top .page-title {
                font-size: 1.1rem !important;
                max-width: 130px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased">
    <div class="wrapper">
        <div class="sidebar-backdrop" onclick="toggleSidebar()"></div>

        <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark" id="sidebar">
            <div class="container-fluid d-flex flex-column h-100 p-0">

                <div class="d-flex align-items-center justify-content-between w-100">
                    <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
                        <div class="logo-wrapper">
                            <img src="{{ asset('image/BankSampahlogo.jpg') }}" alt="Logo">
                        </div>
                        <div class="brand-text">
                            <span class="brand-title">Bank Sampah</span>
                            <span class="brand-subtitle">Digital Subang</span>
                        </div>
                    </a>
                    <button class="btn btn-ghost-light d-lg-none me-3" onclick="toggleSidebar()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="collapse navbar-collapse flex-grow-1" id="sidebar-menu">
                    <ul class="navbar-nav pt-3">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                                </span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item-header">Menu Utama</li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.nasabah.*') ? 'active' : '' }}" href="{{ route('admin.nasabah.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </span>
                                <span class="nav-link-title">Data Nasabah</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.jenis-sampah.*') ? 'active' : '' }}" href="{{ route('admin.jenis-sampah.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                </span>
                                <span class="nav-link-title">Jenis Sampah</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.artikels.*') ? 'active' : '' }}" href="{{ route('admin.artikels.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                </span>
                                <span class="nav-link-title">Kelola Artikel</span>
                            </a>
                        </li>

                        <li class="nav-item-header">Transaksi</li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.tabungan.*') ? 'active' : '' }}" href="{{ route('admin.tabungan.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><line x1="16" y1="21" x2="16" y2="17"/><line x1="8" y1="21" x2="8" y2="17"/><line x1="2" y1="11" x2="22" y2="11"/></svg>
                                </span>
                                <span class="nav-link-title">Setoran Sampah</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.penarikan.*') ? 'active' : '' }}" href="{{ route('admin.penarikan.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                </span>
                                <span class="nav-link-title">Penarikan Dana</span>
                            </a>
                        </li>

                        <li class="nav-item-header">Laporan</li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}" href="{{ route('admin.laporan') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                </span>
                                <span class="nav-link-title">Rekap Laporan</span>
                            </a>
                        </li>

                        <li class="nav-item-header">Pengaturan</li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kelola-admin.*') ? 'active' : '' }}" href="{{ route('admin.kelola-admin.index') }}">
                                <span class="nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M12 10a3 3 0 1 0 0 -6a3 3 0 0 0 0 6"/><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"/></svg>
                                </span>
                                <span class="nav-link-title">Kelola Admin</span>
                            </a>
                        </li>

                    </ul>
                </div>

                <!-- Bagian Bawah Sidebar (Menampilkan Foto/Inisial Admin) -->
                <div class="p-3 w-100" style="background: rgba(0,0,0,0.25); border-top: 1px solid rgba(255,255,255,0.05);">
                    <div class="d-flex align-items-center">
                        @if(auth()->user()?->foto)
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profil" class="avatar avatar-md rounded-circle shadow-lg" style="width: 42px; height: 42px; object-fit: cover;">
                        @else
                            <div class="avatar avatar-md rounded-circle bg-white text-emerald-600 fw-bold shadow-lg d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; color: var(--primary);">
                                {{ Str::upper(Str::substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                            </div>
                        @endif

                        <div class="ms-3 flex-grow-1 overflow-hidden">
                            <a href="{{ route('admin.profile') }}" class="text-white fw-bold text-truncate d-block text-decoration-none" style="font-size: 0.95rem;">{{ auth()->user()?->name ?? 'Admin' }}</a>
                            <div class="text-slate-400 small text-truncate" style="font-size: 0.75rem;">Administrator</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="page-wrapper">
            <div class="sticky-top">
                <div class="navbar navbar-top">
                    <div class="container-xl">
                        <div class="row align-items-center w-100 m-0">
                            <div class="col-auto d-flex align-items-center p-0">
                                <button class="btn btn-light btn-icon me-3 d-lg-none shadow-sm rounded-3" onclick="toggleSidebar()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                                </button>
                                <h1 class="page-title mb-0 fs-3 fw-bold d-none d-md-block">
                                    @yield('page-title', 'Dashboard')
                                </h1>
                            </div>

                            <div class="col-auto ms-auto d-flex align-items-center p-0">
                                <div class="nav-item dropdown me-4 {{ ($unreadCount ?? 0) > 0 ? 'has-notif' : '' }}">
                                    <a href="#" class="nav-link notif-btn position-relative" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="notif-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                        </svg>
                                        <span id="notif-badge" class="badge position-absolute rounded-pill notif-badge-indicator {{ ($unreadCount ?? 0) > 0 ? '' : 'd-none' }}">
                                            {{ ($unreadCount ?? 0) > 99 ? '99+' : ($unreadCount ?? 0) }}
                                        </span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end notif-dropdown mt-3 border-0">
                                        <div class="card shadow-none border-0">
                                            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                                                <h3 class="card-title fw-bold mb-0 text-dark fs-5">
                                                    Notifikasi
                                                </h3>
                                                @if(($unreadCount ?? 0) > 0)
                                                    <button type="button" onclick="markAllRead()" class="btn btn-link text-emerald-600 p-0 text-decoration-none" style="font-size: 0.8rem; font-weight: 700; color: var(--primary);">
                                                        Tandai dibaca
                                                    </button>
                                                @endif
                                            </div>
                                            <div id="notif-list" class="list-group list-group-flush notif-scroll">
                                                @include('admin.notifikasi._partial-list')
                                            </div>
                                            <div class="card-footer bg-slate-50 text-center py-3 border-top border-slate-100">
                                                <a href="{{ route('admin.notifikasi') }}" class="fw-bold text-decoration-none" style="font-size: 0.85rem; color: var(--primary);">
                                                    Lihat Semua Notifikasi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian Tombol Dropdown Profil Navbar -->
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link d-flex align-items-center p-0 text-decoration-none" data-bs-toggle="dropdown">
                                        <div class="user-profile-btn d-flex align-items-center">

                                            @if(auth()->user()?->foto)
                                                <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profil" class="avatar avatar-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <span class="avatar avatar-sm rounded-circle text-white fw-bold shadow-sm d-flex align-items-center justify-content-center" style="background: var(--primary); width: 32px; height: 32px;">
                                                    {{ Str::upper(Str::substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                                                </span>
                                            @endif

                                            <span class="ms-2 me-1 fw-bold text-dark d-none d-sm-block fs-6">
                                                {{ auth()->user()?->name ?? 'Admin' }}
                                            </span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted ms-1 d-none d-sm-block"><path d="m6 9 6 6 6-6"/></svg>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end mt-3" style="min-width: 240px;">
                                        <div class="dropdown-header px-4 py-3">
                                            <div class="fw-bold text-dark fs-6">{{ auth()->user()?->name ?? 'User' }}</div>
                                            <div class="text-muted small mt-1">{{ auth()->user()?->email ?? '' }}</div>
                                        </div>
                                        <div class="dropdown-divider my-0"></div>
                                        <div class="p-2">
                                            <a class="dropdown-item d-flex align-items-center my-1" href="{{ route('admin.profile') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-3 text-muted"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                Profil Saya
                                            </a>
                                            <form method="POST" action="{{ route('admin.logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center w-100 text-start my-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-3"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                                    Keluar Akun
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-header d-print-none d-md-none mt-4">
                <div class="container-xl">
                    <h2 class="page-title fw-bold fs-2 text-dark">@yield('page-title', 'Dashboard')</h2>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center p-3 mb-4 rounded-3" role="alert" style="background: #ecfdf5; border-left: 4px solid #10b981 !important;">
                            <div class="me-3 text-emerald-500" style="color: #10b981;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div>
                                <h4 class="alert-title mb-1 fw-bold text-dark" style="font-size: 1rem;">Berhasil!</h4>
                                <div class="text-muted" style="font-size: 0.9rem;">{{ session('success') }}</div>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center p-3 mb-4 rounded-3" role="alert" style="background: #fef2f2; border-left: 4px solid #ef4444 !important;">
                            <div class="me-3 text-red-500" style="color: #ef4444;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            </div>
                            <div>
                                <h4 class="alert-title mb-1 fw-bold text-dark" style="font-size: 1rem;">Terjadi Kesalahan</h4>
                                <div class="text-muted" style="font-size: 0.9rem;">{{ session('error') }}</div>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <footer class="footer footer-transparent d-print-none mt-auto py-4">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0 mx-auto">
                            <p class="text-slate-500 fw-medium small mb-0">
                                &copy; {{ date('Y') }} <span class="text-dark fw-bold">Bank Sampah Digital Subang</span>.
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- PERBAIKAN JAVASCRIPT UNTUK MOBILE MENU -->
    <script>
        function toggleSidebar() {
            const wrapper = document.querySelector('.wrapper');
            const body = document.body;

            // Toggle class sidebar-show
            wrapper.classList.toggle('sidebar-show');

            // Atur overflow body agar tidak bisa discroll saat menu terbuka
            if (wrapper.classList.contains('sidebar-show')) {
                body.classList.add('sidebar-open');
            } else {
                body.classList.remove('sidebar-open');
            }
        }

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content || '';
        }

        function markRead(id, event) {
            if (event) event.preventDefault();
            fetch(`{{ route('admin.notifikasi.mark-read', ':id') }}`.replace(':id', id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    const target = event?.target?.closest('a')?.getAttribute('href');
                    if (target && target !== '#') {
                        setTimeout(() => { window.location.href = target; }, 300);
                    }
                }
            })
            .catch(() => {});
        }

        function markAllRead() {
            fetch('{{ route("admin.notifikasi.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) loadNotifications();
            })
            .catch(() => {});
        }

        function loadNotifications() {
            fetch('{{ route("admin.notifikasi.fetch") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                const listEl = document.getElementById('notif-list');
                if (listEl) listEl.innerHTML = data.html;

                const badge = document.getElementById('notif-badge');
                const notifContainer = document.querySelector('.nav-item.dropdown');

                if (badge && notifContainer) {
                    const count = data.unread_count || 0;
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.classList.remove('d-none');
                        notifContainer.classList.add('has-notif');
                    } else {
                        badge.classList.add('d-none');
                        notifContainer.classList.remove('has-notif');
                    }
                }
            })
            .catch(() => {});
        }

        setInterval(loadNotifications, 30000);
    </script>
    @stack('scripts')
</body>
</html>
