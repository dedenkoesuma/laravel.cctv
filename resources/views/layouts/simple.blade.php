<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-18137062204"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'AW-18137062204');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Title Dinamis -->
    <title>@yield('title', 'TechStore - Solusi Pengadaan & Pemasangan CCTV Profesional')</title>
    
    <!-- Meta Description Dinamis -->
    <meta name="description" content="@yield('meta_description', 'PT. MJA TEKNOLOGI - Layanan pengadaan paket CCTV, WiFi Cam, dan Akses Kontrol bergaransi resmi 2 tahun untuk rumah, kantor, dan industri.')">
    <meta name="keywords" content="@yield('meta_keywords', 'CCTV, pasang cctv jakarta, paket cctv, hikvision, dahua, ruijie, access control, ezviz, imou, hilook, jasa instalasi cctv')">
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --ts-slate-900: #0f172a;
            --ts-slate-800: #1e293b;
            --ts-slate-700: #334155;
            --ts-slate-600: #475569;
            --ts-slate-500: #64748b;
            --ts-slate-200: #e2e8f0;
            --ts-slate-100: #f1f5f9;
            --ts-slate-50:  #f8fafc;
            
            --ts-primary: #dc2626;
            --ts-primary-dark: #b91c1c;
            --ts-primary-light: #fef2f2;
            
            --ts-green: #059669;
            --ts-green-dark: #047857;
            
            --ts-font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: var(--ts-font);
            color: var(--ts-slate-800);
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: var(--ts-slate-900);
            letter-spacing: -0.02em;
        }

        main {
            flex: 1;
        }

        /* ===== TOP CORPORATE BAR ===== */
        .top-corp-bar {
            background-color: #0b1120;
            color: #94a3b8;
            font-size: 12.5px;
            padding: 7px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .top-corp-bar a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .top-corp-bar a:hover {
            color: #ffffff;
        }

        /* ===== PROFESSIONAL NAVBAR ===== */
        .techstore-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--ts-slate-200);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .navbar-container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 74px;
        }

        /* Brand Logo */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid var(--ts-slate-200);
            overflow: hidden;
            padding: 4px;
        }

        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-info {
            display: flex;
            flex-direction: column;
        }

        .brand-text {
            font-size: 20px;
            font-weight: 800;
            color: var(--ts-slate-900);
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .brand-text span {
            color: var(--ts-primary);
        }

        .brand-subtitle {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.6px;
            color: var(--ts-slate-500);
            text-transform: uppercase;
        }

        /* Navigation Menu */
        .navbar-menu {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 2px;
            margin: 0;
            padding: 0;
        }

        .navbar-item {
            position: relative;
        }

        .navbar-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--ts-slate-700);
            text-decoration: none;
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .navbar-link i {
            font-size: 14px;
            color: var(--ts-slate-500);
            transition: color 0.2s ease;
        }

        .navbar-link:hover {
            color: var(--ts-slate-900);
            background-color: var(--ts-slate-100);
        }

        .navbar-link:hover i {
            color: var(--ts-primary);
        }

        .navbar-link.active {
            color: var(--ts-primary);
            background-color: var(--ts-primary-light);
            font-weight: 700;
        }

        .navbar-link.active i {
            color: var(--ts-primary);
        }

        .dropdown-arrow {
            font-size: 10px;
            transition: transform 0.2s ease;
            margin-left: 2px;
        }

        .navbar-item.show .dropdown-arrow,
        .navbar-item:hover .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Professional Dropdown */
        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: #ffffff;
            min-width: 230px;
            border-radius: 10px;
            padding: 6px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: all 0.2s ease;
            z-index: 1000;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
            border: 1px solid var(--ts-slate-200);
            pointer-events: none;
        }

        @media (min-width: 993px) {
            .navbar-item:hover .dropdown-menu-custom {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
                pointer-events: auto;
            }
        }

        .navbar-item.show .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 12px;
            color: var(--ts-slate-700);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.15s ease;
        }

        .dropdown-item-custom:hover {
            background-color: var(--ts-slate-100);
            color: var(--ts-primary);
        }

        .dropdown-item-custom i {
            font-size: 11px;
            opacity: 0.4;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .dropdown-item-custom:hover i {
            opacity: 1;
            transform: translateX(2px);
        }

        /* Navbar CTA Button */
        .nav-btn-wa {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--ts-primary);
            color: #ffffff;
            text-decoration: none;
            padding: 10px 18px;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-btn-wa:hover {
            color: #ffffff;
            background-color: var(--ts-primary-dark);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        }

        /* Mobile Menu Toggle Button */
        .mobile-menu-toggle {
            display: none;
            background: #ffffff;
            border: 1px solid var(--ts-slate-200);
            color: var(--ts-slate-800);
            border-radius: 6px;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .mobile-menu-toggle {
                display: flex;
            }

            .navbar-container {
                height: 66px;
                padding: 0 16px;
            }

            .navbar-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #ffffff;
                flex-direction: column;
                align-items: stretch;
                padding: 14px 16px;
                gap: 2px;
                display: none;
                border-bottom: 1px solid var(--ts-slate-200);
                box-shadow: 0 16px 24px rgba(0, 0, 0, 0.08);
            }

            .navbar-menu.show {
                display: flex;
            }

            .navbar-link {
                padding: 11px 14px;
                font-size: 14.5px;
                justify-content: space-between;
            }

            .dropdown-menu-custom {
                position: static;
                transform: none !important;
                box-shadow: none;
                background: var(--ts-slate-50);
                margin: 4px 0 6px 12px;
                max-height: 0;
                padding: 0;
                overflow: hidden;
                transition: max-height 0.25s ease;
                border: 1px solid var(--ts-slate-200);
            }

            .navbar-item.show .dropdown-menu-custom {
                max-height: 400px;
                padding: 6px;
            }

            .nav-btn-wa-desktop {
                display: none;
            }
        }

        /* ===== FLOATING WHATSAPP BUTTON (BOTTOM LEFT) ===== */
        .floating-wa-btn {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: 998;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #25d366;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 9999px;
            font-size: 13.5px;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(37, 211, 102, 0.35);
            transition: all 0.25s ease;
        }

        .floating-wa-btn i {
            font-size: 18px;
        }

        .floating-wa-btn:hover {
            color: #ffffff;
            background-color: #20ba5a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.45);
        }

        @media (max-width: 576px) {
            .floating-wa-btn {
                padding: 11px;
                width: 44px;
                height: 44px;
                justify-content: center;
                border-radius: 50%;
                bottom: 20px;
                left: 16px;
            }
            .floating-wa-btn .wa-text {
                display: none;
            }
        }

        /* ===== CORPORATE FOOTER ===== */
        .techstore-footer {
            background-color: #0b1120;
            color: #94a3b8;
            padding-top: 60px;
            padding-bottom: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: auto;
        }

        .footer-container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-heading {
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .footer-desc {
            font-size: 13.5px;
            line-height: 1.7;
            color: #94a3b8;
            margin-bottom: 18px;
        }

        .footer-corp-meta {
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 13.5px;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .footer-links a:hover {
            color: #ffffff;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
        }

        .footer-contact-item i {
            color: #ef4444;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .footer-divider {
            border-color: rgba(255, 255, 255, 0.08);
            margin: 36px 0 20px;
        }

        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            font-size: 12.5px;
            color: #64748b;
        }

        .btn-back-to-top {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-back-to-top:hover {
            background-color: var(--ts-primary);
            border-color: var(--ts-primary);
            color: #ffffff;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Top Corporate Bar -->
    <div class="top-corp-bar d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center" style="max-width: 1320px;">
            <div class="d-flex align-items-center gap-4">
                <span><i class="bi bi-shield-check text-danger me-1"></i> PT. MJA TEKNOLOGI • Solusi CCTV & Keamanan Terpercaya</span>
                <span><i class="bi bi-clock me-1"></i> Jam Operasional: Senin - Sabtu (08.30 - 17.30 WIB)</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="mailto:techstorecctv@gmail.com"><i class="bi bi-envelope me-1"></i> techstorecctv@gmail.com</a>
                <span class="text-secondary">|</span>
                <a href="https://wa.me/62881025756671" target="_blank"><i class="bi bi-whatsapp text-success me-1"></i> Hotline: +62 881-0257-56671</a>
            </div>
        </div>
    </div>

    <!-- Professional White TechStore Navbar -->
    <nav class="techstore-navbar">
        <div class="navbar-container">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="navbar-brand">
                <div class="brand-icon">
                    <img src="{{ asset('storage/gambar/logo-mja.png') }}" 
                         alt="MJA Tech Logo" 
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23dc2626\'%3E%3Cpath d=\'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5\'/%3E%3C/svg%3E';">
                </div>
                <div class="brand-info">
                    <span class="brand-text">Tech<span>Store</span></span>
                    <span class="brand-subtitle">PT. MJA TEKNOLOGI</span>
                </div>
            </a>

            <!-- Mobile Menu Toggle Button -->
            <button class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Buka Menu Navigasi">
                <i class="bi bi-list"></i>
            </button>

            <!-- Navigation Links -->
            <ul class="navbar-menu" id="mainMenu">
                <li class="navbar-item">
                    <a href="{{ url('/') }}" class="navbar-link {{ request()->is('home') || request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-house"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                
                <!-- Dropdown: Paket CCTV -->
                <li class="navbar-item dropdown-parent">
                    <a href="#" class="navbar-link dropdown-trigger {{ request()->is('products/*') ? 'active' : '' }}">
                        <i class="bi bi-camera-video"></i>
                        <span>Paket CCTV</span>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </a>
                    <div class="dropdown-menu-custom">
                        <a href="{{ url('/products/hikvision') }}" class="dropdown-item-custom">
                            <span>Hikvision</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ url('/products/dahua') }}" class="dropdown-item-custom">
                            <span>Dahua</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ url('/products/hilook') }}" class="dropdown-item-custom">
                            <span>HiLook</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ url('/products/unv') }}" class="dropdown-item-custom">
                            <span>UNV (Uniview)</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ url('/products/hiview') }}" class="dropdown-item-custom">
                            <span>HiView</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </li>

                <!-- WiFi Cam -->
                <li class="navbar-item">
                    <a href="{{ url('/wifi-cam') }}" class="navbar-link {{ request()->is('wifi-cam*') ? 'active' : '' }}">
                        <i class="bi bi-broadcast"></i>
                        <span>WiFi Camera</span>
                    </a>
                </li>

                <!-- Akses Kontrol -->
                <li class="navbar-item">
                    <a href="{{ url('/access-control') }}" class="navbar-link {{ request()->is('access-control*') ? 'active' : '' }}">
                        <i class="bi bi-fingerprint"></i>
                        <span>Akses Kontrol</span>
                    </a>
                </li>

                <!-- Dropdown: Networking -->
                <li class="navbar-item dropdown-parent">
                    <a href="#" class="navbar-link dropdown-trigger">
                        <i class="bi bi-hdd-network"></i>
                        <span>Networking</span>
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </a>
                    <div class="dropdown-menu-custom">
                        <a href="{{ url('/products/ruijie') }}" class="dropdown-item-custom">
                            <span>RUIJIE / REYEE</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ url('/products/foreage') }}" class="dropdown-item-custom">
                            <span>FOREAGES</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </li>

                <!-- Tentang Kami -->
                <li class="navbar-item">
                    <a href="{{ url('/about') }}" class="navbar-link {{ request()->is('about') || request()->is('tentang-kami') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        <span>Tentang Kami</span>
                    </a>
                </li>

                <!-- Kontak -->
                <li class="navbar-item">
                    <a href="{{ url('/contact') }}" class="navbar-link {{ request()->is('contact') ? 'active' : '' }}">
                        <i class="bi bi-headset"></i>
                        <span>Kontak</span>
                    </a>
                </li>
            </ul>

            <!-- Navbar Quick Action: WhatsApp Button -->
            <div class="navbar-cta-group">
                <a href="https://wa.me/62881025756671?text=Halo%20TechStore%2C%20saya%20ingin%20konsultasi%20pemasangan%20CCTV" 
                   target="_blank" 
                   class="nav-btn-wa nav-btn-wa-desktop">
                    <i class="bi bi-whatsapp"></i>
                    <span>Konsultasi & Survey</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content Slot -->
    <main>
        @yield('content')
    </main>

    <!-- Floating WhatsApp Action (Bottom Left) -->
    <a href="https://wa.me/62881025756671?text=Halo%20TechStore%2C%20saya%20tertarik%20dengan%20produk%20dan%20jasa%20CCTV" 
       target="_blank" 
       class="floating-wa-btn" 
       title="Chat WhatsApp Resmi TechStore">
        <i class="bi bi-whatsapp"></i>
        <span class="wa-text">Chat Teknisi WA</span>
    </a>

    <!-- Corporate Footer -->
    <footer class="techstore-footer">
        <div class="footer-container">
            <div class="row g-4 mb-4">
                <!-- Col 1: Profile & Company Legal -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-icon bg-white p-1" style="width: 38px; height: 38px; border-radius: 6px;">
                            <img src="{{ asset('storage/gambar/logo-mja.png') }}" 
                                 alt="MJA Tech Logo" 
                                 style="width: 100%; height: 100%; object-fit: contain;"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23dc2626\'%3E%3Cpath d=\'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5\'/%3E%3C/svg%3E';">
                        </div>
                        <div>
                            <span class="text-white fw-bold fs-5">TechStore</span>
                            <div class="text-secondary" style="font-size: 11px; letter-spacing: 0.5px;">PT. MJA TEKNOLOGI</div>
                        </div>
                    </div>
                    <p class="footer-desc">
                        Perusahaan penyedia pengadaan perlengkapan dan jasa instalasi kamera CCTV, WiFi Cam, Mesin Akses Kontrol, dan solusi IT Network bergaransi resmi untuk perumahan, instansi, dan industri.
                    </p>
                    <div class="footer-corp-meta">
                        <div><i class="bi bi-check-circle-fill text-danger me-1"></i> Distributor & Kontraktor Resmi CCTV</div>
                        <div><i class="bi bi-check-circle-fill text-danger me-1"></i> Garansi Resmi Unit & Jasa Pasang 2 Tahun</div>
                    </div>
                </div>

                <!-- Col 2: Alamat Head Office -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">Kantor Operasional</h5>
                    <div class="footer-contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>
                            Jl. Kalibaru Timur, RT.3/RW.2, Bungur,<br>
                            Kec. Senen, Kota Jakarta Pusat,<br>
                            DKI Jakarta 10460
                        </span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-clock-fill"></i>
                        <span>
                            Senin - Sabtu: 08.30 - 17.30 WIB<br>
                            <span class="text-success fw-semibold">Layanan Konsultasi Online 24 Jam</span>
                        </span>
                    </div>
                </div>

                <!-- Col 3: Link Navigasi -->
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Navigasi</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}"><i class="bi bi-chevron-right text-secondary small"></i> Beranda</a></li>
                        <li><a href="{{ url('/products/hikvision') }}"><i class="bi bi-chevron-right text-secondary small"></i> Paket CCTV</a></li>
                        <li><a href="{{ url('/wifi-cam') }}"><i class="bi bi-chevron-right text-secondary small"></i> WiFi Camera</a></li>
                        <li><a href="{{ url('/access-control') }}"><i class="bi bi-chevron-right text-secondary small"></i> Akses Kontrol</a></li>
                        <li><a href="{{ url('/products/ruijie') }}"><i class="bi bi-chevron-right text-secondary small"></i> Produk Ruijie</a></li>
                        <li><a href="{{ url('/about') }}"><i class="bi bi-chevron-right text-secondary small"></i> Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Col 4: Layanan Pelanggan -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">Kontak & Layanan</h5>
                    <ul class="footer-links">
                        <li>
                            <a href="https://wa.me/62881025756671" target="_blank">
                                <i class="bi bi-whatsapp text-success"></i> +62 881-0257-56671
                            </a>
                        </li>
                        <li>
                            <a href="mailto:techstorecctv@gmail.com">
                                <i class="bi bi-envelope"></i> techstorecctv@gmail.com
                            </a>
                        </li>
                        <li>
                            <a href="tel:0881025756671">
                                <i class="bi bi-telephone"></i> Layanan Konsultasi & Survey
                            </a>
                        </li>
                    </ul>
                    <div class="mt-3 pt-2">
                        <span class="badge bg-secondary bg-opacity-25 text-light px-3 py-2 rounded-2">
                            <i class="bi bi-patch-check-fill text-danger me-1"></i> Authorized System Integrator
                        </span>
                    </div>
                </div>
            </div>

            <hr class="footer-divider">

            <!-- Bottom Row -->
            <div class="footer-bottom">
                <div>
                    &copy; {{ date('Y') }} PT. MJA TEKNOLOGI. Hak Cipta Dilindungi Undang-Undang.
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="d-none d-md-inline text-secondary small">Unit 100% Original • Garansi Resmi • Teknisi Berpengalaman</span>
                    <button onclick="scrollToTop()" class="btn-back-to-top" aria-label="Kembali ke Atas" title="Kembali ke Atas">
                        <i class="bi bi-arrow-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const mainMenu = document.getElementById('mainMenu');

            if (mobileBtn && mainMenu) {
                mobileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mainMenu.classList.toggle('show');
                    const icon = mobileBtn.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('bi-list');
                        icon.classList.toggle('bi-x-lg');
                    }
                });
            }

            const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');
            dropdownTriggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    if (window.innerWidth <= 992) {
                        e.preventDefault();
                        e.stopPropagation();
                        const parent = this.closest('.navbar-item');
                        document.querySelectorAll('.navbar-item').forEach(function(item) {
                            if (item !== parent) item.classList.remove('show');
                        });
                        parent.classList.toggle('show');
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.navbar-container')) {
                    if (mainMenu && mainMenu.classList.contains('show')) {
                        mainMenu.classList.remove('show');
                        const icon = mobileBtn ? mobileBtn.querySelector('i') : null;
                        if (icon) {
                            icon.classList.add('bi-list');
                            icon.classList.remove('bi-x-lg');
                        }
                    }
                    document.querySelectorAll('.navbar-item').forEach(function(item) {
                        item.classList.remove('show');
                    });
                }
            });
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>

    @yield('scripts')
</body>
</html>