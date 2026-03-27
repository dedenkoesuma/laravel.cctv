<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - TechStore</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            padding: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            font-size: 1.4em;
            font-weight: 600;
        }

        .logo-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex: 1;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 20px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95em;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }

        .nav-link.active {
            background: rgba(102, 126, 234, 0.2);
            border-bottom: 3px solid #667eea;
        }

        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 20px 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero h1 {
            font-size: 3em;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.3em;
            opacity: 0.95;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section {
            padding: 80px 20px;
        }

        .about-content {
            background: white;
            padding: 60px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 40px 0;
        }

        .about-content p {
            font-size: 1.1em;
            color: #4a5568;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 60px 0;
        }

        .stat-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-number {
            font-size: 3em;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1em;
            color: #4a5568;
            font-weight: 500;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2em;
            color: white;
            margin-bottom: 25px;
        }

        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 40px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-3px);
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
            padding: 10px;
            margin-left: auto;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            .nav-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #2d3748;
                flex-direction: column;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }
            .nav-menu.active {
                max-height: 500px;
            }
            .hero h1 {
                font-size: 2em;
            }
            .about-content {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-logo">
                <div class="logo-icon">🏠</div>
                TechStore
            </a>
            <button class="mobile-menu-toggle" onclick="toggleMenu()">☰</button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ url('/') }}" class="nav-link"><span>🏠</span> Home</a></li>
                <li><a href="{{ url('/products/hikvision') }}" class="nav-link"><span>🛒</span> Produk Paket</a></li>
                <li><a href="{{ url('/access-control') }}" class="nav-link"><span>👤</span> Akses Kontrol</a></li>
                <li><a href="{{ url('/wifi-cam') }}" class="nav-link"><span>📶</span> WiFi Cam</a></li>
                <li><a href="{{ url('/products/ruijie') }}" class="nav-link"><span>📡</span> Produk Networking</a></li>
                <li><a href="{{ url('/about') }}" class="nav-link active"><span>👥</span> Tentang Kami</a></li>
            </ul>
        </div>
    </nav>

    <div class="hero">
        <div class="hero-content">
            <h1>Tentang Kami</h1>
            <p>Partner Terpercaya untuk Solusi Keamanan CCTV Profesional</p>
        </div>
    </div>

    <div class="container">
        <section class="section">
            <div class="about-content">
                <h2 style="font-size: 2.5em; margin-bottom: 30px; color: #2d3748;">Siapa Kami</h2>
                <p>TechStore adalah perusahaan penyedia solusi keamanan CCTV terkemuka yang telah melayani ribuan klien di seluruh Indonesia. Dengan pengalaman lebih dari 10 tahun di industri keamanan, kami berkomitmen untuk memberikan produk berkualitas tinggi dan layanan terbaik kepada setiap pelanggan.</p>
                <p>Tim profesional kami terdiri dari teknisi bersertifikat dan ahli keamanan yang berpengalaman dalam merancang, menginstal, dan memelihara sistem CCTV untuk berbagai kebutuhan, mulai dari rumah tinggal, perkantoran, hingga kompleks industri berskala besar.</p>
                <p>Kami bangga menjadi mitra tepercaya yang tidak hanya menjual produk, tetapi juga memberikan konsultasi menyeluruh dan dukungan purna jual yang responsif untuk memastikan keamanan Anda terjaga 24/7.</p>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number">10+</div>
                    <div class="