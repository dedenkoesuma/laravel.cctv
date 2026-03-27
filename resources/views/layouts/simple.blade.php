<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TechStore')</title>
    
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
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .navbar-brand:hover {
            color: #60a5fa;
            transform: translateY(-2px);
        }

        .navbar-brand:hover .brand-icon {
            transform: rotate(360deg) scale(1.1);
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.6);
        }

        .brand-text {
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
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
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(139, 92, 246, 0.15));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
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

        /* Rotate arrow when dropdown is open */
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

        /* Show dropdown when parent has 'show' class */
        .navbar-item.show .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        /* Desktop hover */
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

        .dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .dropdown-item:hover::before {
            transform: scaleY(1);
        }

        .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.15);
            color: white;
            transform: translateX(4px);
            text-decoration: none;
        }

        .dropdown-item.active {
            background: rgba(59, 130, 246, 0.2);
            color: white;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: rgba(59, 130, 246, 0.1);
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: scale(1.05);
        }

        .mobile-menu-toggle:active {
            transform: scale(0.95);
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
                animation: slideDown 0.3s ease;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .navbar-link {
                width: 100%;
                padding: 14px 16px;
                border-radius: 10px;
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

            .dropdown-item {
                padding-left: 20px;
            }
        }

        /* Footer */
        footer {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(148, 163, 184, 0.1);
            color: #94a3b8;
            text-align: center;
            padding: 32px 24px;
            margin-top: auto;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #2563eb, #7c3aed);
        }
    </style>
</head>
<body>
    <!-- Modern TechStore Navbar -->
    <nav class="techstore-navbar">
        <div class="navbar-container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <div class="brand-icon">
                    <svg fill="white" viewBox="0 0 20 20" width="22" height="22">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
                <span class="brand-text">TechStore</span>
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
                  <li class="navbar-item">
                    <a href="{{ url('/about') }}" class="navbar-link">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Tentang Kami
                    </a>
                </li>
                </li>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2024 TechStore. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Navbar script initialized');

            // ========== DROPDOWN DELAY SETTINGS ==========
            const DROPDOWN_CLOSE_DELAY = 800; // Waktu delay dalam milliseconds (800ms = 0.8 detik)
            let closeTimeout = null;

            // ========== MOBILE MENU TOGGLE ==========
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const mainMenu = document.getElementById('mainMenu');

            if (mobileBtn) {
                mobileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mainMenu.classList.toggle('show');
                    console.log('📱 Mobile menu:', mainMenu.classList.contains('show') ? 'Open' : 'Closed');
                });
            }

            // ========== DROPDOWN TOGGLE (MOBILE CLICK) ==========
            const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');
            
            console.log('🎯 Found dropdowns:', dropdownTriggers.length);

            dropdownTriggers.forEach(function(trigger, index) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const parent = this.closest('.navbar-item');
                    const isOpen = parent.classList.contains('show');
                    
                    console.log('🖱️ Dropdown #' + (index + 1) + ' clicked, currently:', isOpen ? 'OPEN' : 'CLOSED');
                    
                    // Close all other dropdowns
                    document.querySelectorAll('.navbar-item').forEach(function(item) {
                        if (item !== parent) {
                            item.classList.remove('show');
                        }
                    });
                    
                    // Toggle current dropdown
                    parent.classList.toggle('show');
                    
                    console.log('✅ Dropdown #' + (index + 1) + ' now:', parent.classList.contains('show') ? 'OPEN' : 'CLOSED');
                });
            });

            // ========== DROPDOWN HOVER WITH DELAY (DESKTOP ONLY) ==========
            if (window.innerWidth > 992) {
                const dropdownParents = document.querySelectorAll('.dropdown-parent');
                
                dropdownParents.forEach(function(parent) {
                    // Mouse enter - buka dropdown
                    parent.addEventListener('mouseenter', function() {
                        // Clear any pending close timeout
                        if (closeTimeout) {
                            clearTimeout(closeTimeout);
                            closeTimeout = null;
                        }
                        
                        // Close other dropdowns
                        document.querySelectorAll('.navbar-item').forEach(function(item) {
                            if (item !== parent) {
                                item.classList.remove('show');
                            }
                        });
                        
                        // Open this dropdown
                        parent.classList.add('show');
                    });
                    
                    // Mouse leave - tutup dropdown dengan delay
                    parent.addEventListener('mouseleave', function() {
                        const currentParent = this;
                        
                        // Set timeout untuk menutup dropdown
                        closeTimeout = setTimeout(function() {
                            currentParent.classList.remove('show');
                            console.log('⏱️ Dropdown closed after delay');
                        }, DROPDOWN_CLOSE_DELAY);
                    });
                });
            }

            // ========== CLOSE ON OUTSIDE CLICK ==========
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.navbar-container')) {
                    // Clear any pending timeout
                    if (closeTimeout) {
                        clearTimeout(closeTimeout);
                        closeTimeout = null;
                    }
                    
                    // Close mobile menu
                    if (mainMenu) {
                        mainMenu.classList.remove('show');
                    }
                    
                    // Close all dropdowns
                    document.querySelectorAll('.navbar-item').forEach(function(item) {
                        item.classList.remove('show');
                    });
                }
            });

            // ========== HANDLE RESIZE ==========
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    mainMenu.classList.remove('show');
                    document.querySelectorAll('.navbar-item').forEach(function(item) {
                        item.classList.remove('show');
                    });
                }
            });

            // ========== ACTIVE LINK ==========
            const currentPath = window.location.pathname;
            document.querySelectorAll('.navbar-link, .dropdown-item').forEach(function(link) {
                const href = link.getAttribute('href');
                if (href && href !== '#' && currentPath.includes(href)) {
                    link.classList.add('active');
                }
            });

            console.log('✅ Navbar ready! Dropdown close delay:', DROPDOWN_CLOSE_DELAY + 'ms');
        });
    </script>
    
    @yield('scripts')
</body>
</html>