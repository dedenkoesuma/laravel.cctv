<!DOCTYPE html>
<html lang="en">
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
   <!-- Title Dinamis (Default jika tidak diisi) -->
    <title>@yield('title', 'TechStore - Solusi CCTV & IT Security Terpercaya')</title>
    
    <!-- Meta Description Dinamis -->
    <meta name="description" content="@yield('meta_description', 'Pusat penjualan dan jasa instalasi perlengkapan CCTV, Ruijie, dan Access Control dengan harga terbaik.')">
    
    <!-- Meta Keywords (Opsional, tapi bagus ditambahkan) -->
    <meta name="keywords" content="@yield('meta_keywords', 'CCTV, pasang cctv, hikvision, dahua, ruijie, access control')">
    <link rel="icon" href="/storage/gambar/logo-mja.png" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        main {
            flex: 1;
        }

        /* ===== MODERN NAVBAR ===== */
        .techstore-navbar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
            position: sticky;
            top: 0;
            z-index: 9999;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        /* Brand Logo */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .brand-icon {
            width: 45px; 
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
            padding: 5px;
        }

        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .navbar-brand:hover {
            color: #ff3333;
            transform: translateY(-2px);
        }

        .navbar-brand:hover .brand-icon {
            transform: scale(1.1);
            box-shadow: 0 8px 30px rgba(255, 0, 0, 0.4);
        }

        /* Warna Teks Brand Disesuaikan dengan Logo Merah */
        .brand-text {
            background: linear-gradient(135deg, #f50000 0%, #e2e1ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            filter: drop-shadow(0 2px 4px rgba(255, 0, 0, 0.2));
        }

        /* Navigation Menu */
        .navbar-menu {
            display: flex;
            list-style: none;
            gap: 4px;
            margin: 0;
            padding: 0;
        }

        .navbar-item {
            position: relative;
        }

        .navbar-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 15px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .navbar-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 0, 0, 0.1), rgba(168, 0, 0, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 10px;
        }

        .navbar-link:hover::before {
            opacity: 1;
        }

        .navbar-link:hover {
            color: white;
            transform: translateY(-2px);
        }

        .navbar-link.active {
            color: white;
            background: linear-gradient(135deg, rgba(255, 0, 0, 0.15), rgba(168, 0, 0, 0.15));
            box-shadow: 0 4px 15px rgba(255, 0, 0, 0.2);
        }

        .navbar-link svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
        }

        .navbar-link:hover svg {
            transform: scale(1.1);
        }

        .dropdown-arrow {
            width: 14px;
            height: 14px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-item.show .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Dropdown Menu */
        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            min-width: 260px;
            border-radius: 16px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 10000;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.1);
            pointer-events: none;
        }

        .navbar-item.show .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        @media (min-width: 993px) {
            .navbar-item:hover .dropdown-menu-custom {
                opacity: 1;
                visibility: visible;
                transform: translateY(0) scale(1);
                pointer-events: auto;
            }
            .navbar-item:hover .dropdown-arrow {
                transform: rotate(180deg);
            }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #e2e8f0;
            padding: 12px 16px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .dropdown-item:hover {
            background: rgba(255, 0, 0, 0.15);
            color: white;
            transform: translateX(4px);
            text-decoration: none;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: rgba(255, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            padding: 10px;
            transition: all 0.3s ease;
        }

        /* Responsive Mobile */
        @media (max-width: 992px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .navbar-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(15, 23, 42, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                gap: 0;
                display: none;
                padding: 16px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
                border-bottom-left-radius: 16px;
                border-bottom-right-radius: 16px;
            }

            .navbar-menu.show {
                display: flex;
            }

            .dropdown-menu-custom {
                position: static;
                transform: none;
                box-shadow: none;
                background: rgba(30, 41, 59, 0.5);
                margin-top: 8px;
                margin-left: 16px;
                max-height: 0;
                padding: 0;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .navbar-item.show .dropdown-menu-custom {
                max-height: 500px;
                padding: 8px;
            }
        }

        html {
            scroll-behavior: smooth;
        }

        /* ===== MODERN FOOTER ===== */
        .techstore-footer {
            background: rgba(15, 23, 42, 0.95);
            border-top: 1px solid rgba(148, 163, 184, 0.1);
            padding: 60px 0 20px 0;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .footer-heading {
            color: #f8fafc;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-text {
            color: #cbd5e1;
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .footer-links a:hover {
            color: #ff3333;
            transform: translateX(4px);
        }

        .footer-divider {
            border-color: rgba(148, 163, 184, 0.2);
            margin: 30px 0 20px 0;
        }

        /* Back to Top Button */
        .btn-back-to-top {
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 6px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .btn-back-to-top:hover {
            background: #ef4444;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.5);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Modern MJA Tech Navbar -->
    <nav class="techstore-navbar">
        <div class="navbar-container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <div class="brand-icon">
                    <img src="{{ asset('storage/gambar/logo-mja.png') }}" alt="MJA Tech Logo">
                </div>
                <span class="brand-text">Tech Store</span>
            </a>

            <button class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Toggle menu">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
            </button>

            <ul class="navbar-menu" id="mainMenu">
                <li class="navbar-item">
                    <a href="{{ url('/') }}" class="navbar-link">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Home
                    </a>
                </li>
                
                <li class="navbar-item dropdown-parent">
                    <a href="#" class="navbar-link dropdown-trigger">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        Produk Paket
                        <svg class="dropdown-arrow" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <div class="dropdown-menu-custom">
                        <a href="{{ url('/products/hikvision') }}" class="dropdown-item">Hikvision</a>
                        <a href="{{ url('/products/dahua') }}" class="dropdown-item">Dahua</a>
                        <a href="{{ url('/products/hilook') }}" class="dropdown-item">HiLook</a>
                        <a href="{{ url('/products/unv') }}" class="dropdown-item">UNV</a>
                        <a href="{{ url('/products/hiview') }}" class="dropdown-item">HiView</a>
                    </div>
                </li>

                <li class="navbar-item">
                    <a href="{{ url('/access-control') }}" class="navbar-link">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Akses Kontrol
                    </a>
                </li>

                <li class="navbar-item">
                    <a href="{{ url('/wifi-cam') }}" class="navbar-link">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        WiFi Cam
                    </a>
                </li>

                <li class="navbar-item dropdown-parent">
                    <a href="#" class="navbar-link dropdown-trigger">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        Produk Networking
                        <svg class="dropdown-arrow" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <div class="dropdown-menu-custom">
                        <a href="{{ url('/products/ruijie') }}" class="dropdown-item">RUIJIE/REYEE</a>
                        <a href="{{ url('/products/foreage') }}" class="dropdown-item">FOREAGES</a>
                    </div>
                </li>

                <li class="navbar-item">
                    <a href="{{ url('/about') }}" class="navbar-link">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Tentang Kami
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Modern Footer -->
    <footer class="techstore-footer">
        <div class="footer-container">
            <div class="row gy-4 w-100 m-0">
                <!-- Kolom 1: Logo & Nama Perusahaan -->
                <div class="col-lg-4 col-md-6 ps-0">
                    <a href="{{ url('/') }}" class="navbar-brand d-inline-flex align-items-center mb-3">
                        <div class="brand-icon me-2">
                            <img src="{{ asset('storage/gambar/logo-mja.png') }}" alt="MJA Tech Logo">
                        </div>
                        <div class="d-flex flex-column">
                            <span class="brand-text fs-4 mb-0" style="line-height: 1;">Tech Store</span>
                            <small class="fw-bold mt-1" style="color: #cbd5e1; font-size: 0.75rem; letter-spacing: 0.5px;">PT. MJA TEKNOLOGI</small>
                        </div>
                    </a>
                </div>

                <!-- Kolom 2: Alamat -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">Head Office</h5>
                    <p class="footer-text mb-0">
                        Jl. Kalibaru Timur, RT.3/RW.2, Bungur,<br>
                        Kec. Senen, Kota Jakarta Pusat,<br>
                        Daerah Khusus Ibukota Jakarta 10460
                    </p>
                </div>

                <!-- Kolom 3: Navigasi Menu -->
                <div class="col-lg-2 col-md-6">
                    <ul class="footer-links">
                           <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ url('/products/hikvision') }}">Kamera CCTV</a></li>
                        <li><a href="{{ url('/access-control') }}">Akses Kontrol</a></li>
                        <li><a href="{{ 'https://wa.me/62881025756671' }}">Kontak</a></li>
                        <li><a href="{{ url('/about')}}">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Kolom 4: Kontak & Sosmed -->
                <div class="col-lg-3 col-md-6 pe-0">
                    <ul class="footer-links">
                        <li>
                            <a href="#"><i class="bi bi-instagram me-3 fs-5"></i> Instagram</a>
                        </li>
                        <li>
                            <a href="mailto:techstorecctv@gmail.com"><i class="bi bi-envelope me-3 fs-5"></i> Email</a>
                        </li>
                        <li>
                            <a href="https://wa.me/62881025756671"><i class="bi bi-telephone me-3 fs-5"></i> Telfon</a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="footer-divider">

            <!-- Baris Copyright & Tombol Back to Top -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="footer-text mb-0 text-center text-md-start">
                    &copy; 2021 MJA Tech. All rights reserved. 
                </p>
                <button onclick="scrollToTop()" class="btn-back-to-top mt-3 mt-md-0" aria-label="Back to top">
                    <i class="bi bi-arrow-up fs-5"></i>
                </button>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const DROPDOWN_CLOSE_DELAY = 800; 
            let closeTimeout = null;

            const mobileBtn = document.getElementById('mobileMenuBtn');
            const mainMenu = document.getElementById('mainMenu');

            if (mobileBtn) {
                mobileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mainMenu.classList.toggle('show');
                });
            }

            const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');
            dropdownTriggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const parent = this.closest('.navbar-item');
                    document.querySelectorAll('.navbar-item').forEach(function(item) {
                        if (item !== parent) item.classList.remove('show');
                    });
                    parent.classList.toggle('show');
                });
            });

            if (window.innerWidth > 992) {
                const dropdownParents = document.querySelectorAll('.dropdown-parent');
                dropdownParents.forEach(function(parent) {
                    parent.addEventListener('mouseenter', function() {
                        if (closeTimeout) { clearTimeout(closeTimeout); closeTimeout = null; }
                        document.querySelectorAll('.navbar-item').forEach(function(item) {
                            if (item !== parent) item.classList.remove('show');
                        });
                        parent.classList.add('show');
                    });
                    parent.addEventListener('mouseleave', function() {
                        const currentParent = this;
                        closeTimeout = setTimeout(function() {
                            currentParent.classList.remove('show');
                        }, DROPDOWN_CLOSE_DELAY);
                    });
                });
            }

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.navbar-container')) {
                    if (mainMenu) mainMenu.classList.remove('show');
                    document.querySelectorAll('.navbar-item').forEach(function(item) {
                        item.classList.remove('show');
                    });
                }
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    if (mainMenu) mainMenu.classList.remove('show');
                    document.querySelectorAll('.navbar-item').forEach(function(item) {
                        item.classList.remove('show');
                    });
                }
            });
        });

        // Fungsi untuk tombol Back to Top
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