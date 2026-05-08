@extends('layouts.simple')

@section('title', 'Tentang Kami - TechStore')

@section('content')
<style>
    /* ===== ABOUT PAGE SPECIFIC STYLES ===== */
    .hero-about {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 100px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
        margin-top: -1.5rem; /* Menyesuaikan gap default layout */
    }

    .hero-about::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.3;
    }

    .hero-about-content {
        position: relative;
        z-index: 1;
    }

    .hero-about h1 {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        margin-bottom: 20px;
        letter-spacing: -0.02em;
    }

    .hero-about p {
        font-size: clamp(1.1rem, 2vw, 1.5rem);
        opacity: 0.95;
        max-width: 800px;
        margin: 0 auto;
    }

    .about-section {
        padding: 80px 20px;
        background-color: #f8fafc;
    }

    .about-content-card {
        background: white;
        padding: 60px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        margin-top: -60px; /* Overlap effect with hero */
        position: relative;
        z-index: 10;
        border: 1px solid rgba(0,0,0,0.05);
    }

    /* ===== NEW: Layout Grid Kiri-Kanan ===== */
    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr; /* Kolom kanan sedikit lebih lebar */
        gap: 50px;
        align-items: center;
    }

    .about-image {
        position: relative;
    }

    .about-image img {
        width: 100%;
        height: 100%;
        min-height: 400px;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .about-image img:hover {
        transform: translateY(-5px);
    }
    
    .about-text {
        text-align: left;
    }

    .about-content-card h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
    }

    .about-content-card h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    .about-content-card p {
        font-size: 1.1rem;
        color: #475569;
        margin-bottom: 20px;
        line-height: 1.8;
    }

    /* ===== NEW: Certificates Section ===== */
    .certificates-section {
        margin: 60px 0;
        text-align: center;
    }

    .certificates-section h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 30px;
    }

    .certificates-image {
        max-width: 100%;
        height: auto;
        border-radius: 16px;
        transition: transform 0.3s ease;
    }

    .certificates-image:hover {
        transform: translateY(-5px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin: 60px 0;
    }

    .stat-card {
        background: white;
        padding: 40px 30px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        transition: transform 0.3s ease;
        border: 1px solid #f1f5f9;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
        line-height: 1;
    }

    .stat-label {
        font-size: 1.1rem;
        color: #64748b;
        font-weight: 600;
    }

    .core-values {
        text-align: center;
        margin-bottom: 50px;
    }

    .core-values h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 15px;
    }
    
    .core-values p {
        color: #64748b;
        font-size: 1.1rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .feature-card {
        background: white;
        padding: 40px 30px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.15);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .feature-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 15px;
    }

    .feature-card p {
        color: #64748b;
        line-height: 1.7;
        margin-bottom: 0;
    }

    .cta-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        padding: 60px 40px;
        text-align: center;
        color: white;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .cta-wrapper h2 {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .cta-wrapper p {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 35px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 40px;
        background: white;
        color: #667eea;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        color: #764ba2;
    }

    /* ===== Responsive Adjustments ===== */
    @media (max-width: 992px) {
        .about-grid {
            grid-template-columns: 1fr; /* Menjadi atas bawah di layar HP/Tablet */
            gap: 30px;
        }

        .about-image img {
            min-height: 250px;
            max-height: 400px;
        }
    }

    @media (max-width: 768px) {
        .about-content-card {
            padding: 30px 20px;
            margin-top: -30px;
        }
        
        .hero-about {
            padding: 80px 20px 60px;
        }

        .cta-wrapper {
            padding: 40px 20px;
        }

        .certificates-section h2 {
            font-size: 1.8rem;
        }
    }
</style>

<div class="hero-about">
    <div class="hero-about-content container">
        <h1>Tentang Kami</h1>
        <p>Partner Terpercaya untuk Solusi Keamanan CCTV Profesional & Networking Infrastruktur</p>
    </div>
</div>

<div class="about-section">
    <div class="container">
        
        <div class="about-content-card">
            <div class="about-grid">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=800&auto=format&fit=crop" alt="Tim TechStore di Ruang Server">
                </div>
                
                <div class="about-text">
                    <h2>Siapa Kami</h2>
                    <p><strong>TechStore</strong> adalah perusahaan penyedia solusi keamanan CCTV dan networking terkemuka yang telah melayani ribuan klien di seluruh Indonesia. Dengan pengalaman lebih dari 10 tahun di industri keamanan, kami berkomitmen untuk memberikan produk berkualitas tinggi dan layanan terbaik kepada setiap pelanggan.</p>
                    <p>Tim profesional kami terdiri dari teknisi bersertifikat dan ahli keamanan yang berpengalaman dalam merancang, menginstal, dan memelihara sistem CCTV untuk berbagai kebutuhan, mulai dari rumah tinggal, perkantoran, hingga kompleks industri berskala besar.</p>
                    <p>Kami bangga menjadi mitra tepercaya yang tidak hanya menjual produk, tetapi juga memberikan konsultasi menyeluruh dan dukungan purna jual yang responsif untuk memastikan keamanan Anda terjaga 24/7.</p>
                </div>
            </div>
        </div>

        <!-- NEW: Certificates Section ditambahkan di bawah Siapa Kami -->
        <div class="certificates-section">
            <h2>Sertifikasi & Kemitraan Resmi</h2>
            <img src="{{ asset('storage/gambar/watermarked_img_7031490200548243503.png') }}" alt="Sertifikat Resmi TechStore" class="certificates-image">
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">10+</div>
                <div class="stat-label">Tahun Pengalaman</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">5K+</div>
                <div class="stat-label">Klien Puas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Teknisi Ahli</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">99%</div>
                <div class="stat-label">Success Rate</div>
            </div>
        </div>

        <div class="core-values mt-5 pt-4">
            <h2>Nilai Inti Kami</h2>
            <p>Prinsip yang kami pegang teguh dalam melayani setiap klien</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3>Integritas & Kepercayaan</h3>
                <p>Keamanan bukan hanya tentang alat, tapi tentang kepercayaan. Kami merekomendasikan solusi yang benar-benar Anda butuhkan, tanpa *hidden cost*.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-lightning-charge"></i>
                </div>
                <h3>Responsif & Cepat</h3>
                <p>Kami memahami urgensi keamanan. Tim kami merespons keluhan dan kebutuhan instalasi dengan cepat, efisien, dan tepat waktu.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-star"></i>
                </div>
                <h3>Kualitas Original</h3>
                <p>Kami hanya menggunakan produk original dari *brand-brand* terbaik dunia seperti Hikvision, Dahua, EZVIZ, Ruijie, dan Foreage bergaransi resmi.</p>
            </div>
        </div>

        <div class="cta-wrapper mb-5">
            <h2>Siap Mengamankan Aset Anda?</h2>
            <p>Jangan tunggu sampai hal yang tidak diinginkan terjadi. Konsultasikan kebutuhan CCTV dan jaringan Anda bersama tim ahli kami sekarang juga.</p>
            <a href="https://wa.me/62881025756671" target="_blank" class="cta-button">
                <i class="bi bi-whatsapp"></i> Hubungi Kami Sekarang
            </a>
        </div>

    </div>
</div>
@endsection