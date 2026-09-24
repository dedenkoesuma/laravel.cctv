@extends('layouts.simple')

@section('title', 'Tentang Kami - PT. MJA TEKNOLOGI (TechStore)')
@section('meta_description', 'Mengenal TechStore (PT. MJA TEKNOLOGI), penyedia resmi instalasi sistem keamanan CCTV, akses kontrol, dan infrastruktur IT jaringan dengan pengalaman lebih dari 10 tahun.')

@section('content')
<style>
/* ===== ABOUT CORPORATE STYLES ===== */
.hero-about-corp {
    background-color: #0f172a;
    color: white;
    padding: 75px 20px 90px;
    text-align: center;
    border-bottom: 1px solid var(--ts-slate-800);
}

.hero-about-content {
    max-width: 820px;
    margin: 0 auto;
}

.hero-about-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fca5a5;
    font-size: 12.5px;
    font-weight: 600;
    padding: 5px 16px;
    border-radius: 4px;
    margin-bottom: 18px;
}

.hero-about-corp h1 {
    font-size: clamp(2.2rem, 4.5vw, 3.4rem);
    font-weight: 800;
    margin-bottom: 14px;
    color: #ffffff;
    line-height: 1.2;
}

.hero-about-corp h1 span {
    color: #f87171;
}

.hero-about-corp p {
    font-size: clamp(1rem, 1.8vw, 1.15rem);
    color: #cbd5e1;
    line-height: 1.6;
    margin: 0 auto;
}

.about-section-corp {
    padding: 0 20px 70px;
    background-color: var(--ts-slate-50);
}

.about-content-card-corp {
    background: white;
    padding: 50px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
    margin-top: -45px;
    border: 1px solid var(--ts-slate-200);
}

.about-grid-corp {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 45px;
    align-items: center;
}

.about-image-corp {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--ts-slate-200);
}

.about-image-corp img {
    width: 100%;
    height: 100%;
    min-height: 380px;
    object-fit: cover;
}

.about-text-corp h2 {
    font-size: 1.9rem;
    font-weight: 800;
    color: var(--ts-slate-900);
    margin-bottom: 18px;
    position: relative;
    padding-bottom: 10px;
}

.about-text-corp h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background-color: var(--ts-primary);
    border-radius: 2px;
}

.about-text-corp p {
    font-size: 14.5px;
    color: var(--ts-slate-600);
    margin-bottom: 16px;
    line-height: 1.75;
}

/* Certificates */
.certificates-section-corp {
    margin: 60px 0 40px;
    text-align: center;
}

.certificates-section-corp h2 {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--ts-slate-900);
    margin-bottom: 20px;
}

.certificates-image-corp {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    border: 1px solid var(--ts-slate-200);
}

/* Stats */
.stats-grid-corp {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin: 50px 0;
}

.stat-card-corp {
    background: white;
    padding: 30px 20px;
    border-radius: 10px;
    text-align: center;
    border: 1px solid var(--ts-slate-200);
}

.stat-number-corp {
    font-size: 2.8rem;
    font-weight: 800;
    color: var(--ts-slate-900);
    margin-bottom: 6px;
    line-height: 1;
}

.stat-label-corp {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--ts-slate-500);
    margin: 0;
}

/* Values */
.features-grid-corp {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 50px;
}

.feature-card-corp {
    background: white;
    padding: 30px 24px;
    border-radius: 10px;
    border: 1px solid var(--ts-slate-200);
}

.feature-icon-corp {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    background-color: var(--ts-slate-100);
    color: var(--ts-slate-800);
    margin-bottom: 18px;
    border: 1px solid var(--ts-slate-200);
}

.feature-card-corp h3 {
    font-size: 16.5px;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin-bottom: 10px;
}

.feature-card-corp p {
    color: var(--ts-slate-600);
    line-height: 1.6;
    margin-bottom: 0;
    font-size: 13.5px;
}

/* CTA */
.cta-wrapper-corp {
    background-color: #0f172a;
    border-radius: 12px;
    padding: 50px 36px;
    text-align: center;
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.cta-wrapper-corp h2 {
    font-size: 1.9rem;
    font-weight: 800;
    margin-bottom: 12px;
    color: #ffffff;
}

.cta-wrapper-corp p {
    font-size: 14.5px;
    color: #cbd5e1;
    margin-bottom: 26px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

.cta-btn-corp {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background-color: var(--ts-primary);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.2s ease;
}

.cta-btn-corp:hover {
    background-color: var(--ts-primary-dark);
    color: white;
}

@media (max-width: 992px) {
    .about-grid-corp {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .about-image-corp img {
        min-height: 250px;
        max-height: 380px;
    }

    .about-content-card-corp {
        padding: 28px 20px;
        margin-top: -30px;
    }
}
</style>

<div class="hero-about-corp">
    <div class="hero-about-content">
        <div class="hero-about-badge">
            <i class="bi bi-shield-check text-danger me-1"></i> Profil Perusahaan
        </div>
        <h1>Tentang <span>PT. MJA TEKNOLOGI</span></h1>
        <p>Mitra Terpercaya Pengadaan & Pemasangan Sistem Keamanan CCTV, Akses Kontrol, dan Jaringan Komputer di Seluruh Indonesia</p>
    </div>
</div>

<div class="about-section-corp">
    <div class="container">
        
        <div class="about-content-card-corp">
            <div class="about-grid-corp">
                <div class="about-image-corp">
                    <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=800&auto=format&fit=crop" 
                         alt="Tim TechStore di Ruang Server"
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1557597774-9d273605dfa9?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';">
                </div>
                
                <div class="about-text-corp">
                    <h2>Siapa Kami</h2>
                    <p><strong>TechStore (PT. MJA TEKNOLOGI)</strong> adalah badan usaha penyedia solusi keamanan CCTV, smart camera, dan infrastruktur IT networking terkemuka yang telah melayani ribuan pelanggan di Indonesia. Dengan pengalaman lebih dari 10 tahun di industri surveillance, kami berkomitmen menghadirkan produk resmi berkualitas tinggi dan standar pengerjaan terpercaya.</p>
                    <p>Tim kami terdiri dari teknisi bersertifikat dan ahli instalasi yang berpengalaman merancang rute kabel rapi dengan pipa conduit pelindung untuk rumah tinggal, perkantoran, ruko, perbankan, hingga pabrik berskala industri.</p>
                    <p>Kami bangga menjadi mitra bisnis yang tidak sekadar menjual perangkat keras, melainkan memberikan layanan konsultasi teknis menyeluruh, garansi resmi 2 tahun penuh, serta dukungan purna jual responsif untuk memastikan sistem keamanan Anda beroperasi prima tanpa gangguan.</p>
                </div>
            </div>
        </div>

        <!-- Sertifikasi & Kemitraan Resmi -->
        <div class="certificates-section-corp">
            <h2>Sertifikasi & Kemitraan Resmi</h2>
            <img src="{{ asset('storage/gambar/watermarked_img_7031490200548243503.png') }}" 
                 alt="Sertifikat Resmi TechStore" 
                 class="certificates-image-corp"
                 onerror="this.style.display='none';">
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid-corp">
            <div class="stat-card-corp">
                <div class="stat-number-corp">10+</div>
                <div class="stat-label-corp">Tahun Pengalaman</div>
            </div>
            <div class="stat-card-corp">
                <div class="stat-number-corp">5.000+</div>
                <div class="stat-label-corp">Klien Puas</div>
            </div>
            <div class="stat-card-corp">
                <div class="stat-number-corp">50+</div>
                <div class="stat-label-corp">Teknisi Ahli</div>
            </div>
            <div class="stat-card-corp">
                <div class="stat-number-corp">99.9%</div>
                <div class="stat-label-corp">Success Rate</div>
            </div>
        </div>

        <!-- Nilai Layanan -->
        <div class="text-center mt-5 mb-4">
            <span class="section-tag">Komitmen Layanan</span>
            <h2 class="section-heading">Nilai Inti PT. MJA TEKNOLOGI</h2>
            <p class="section-subheading mx-auto">Standar mutu yang kami terapkan dalam setiap penanganan proyek pengadaan dan instalasi</p>
        </div>

        <div class="features-grid-corp">
            <div class="feature-card-corp">
                <div class="feature-icon-corp text-danger">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3>Integritas & Transparansi</h3>
                <p>Kami memberikan rincian penawaran yang jelas dan transparan sesuai denah lokasi tanpa biaya tersembunyi.</p>
            </div>
            
            <div class="feature-card-corp">
                <div class="feature-icon-corp text-primary">
                    <i class="bi bi-tools"></i>
                </div>
                <h3>Pemasangan Rapi Berstandar</h3>
                <p>Kabel dilindungi rapi dengan pipa conduit pelindung untuk mencegah gigitan tikus dan bahaya korsleting listrik.</p>
            </div>
            
            <div class="feature-card-corp">
                <div class="feature-icon-corp text-success">
                    <i class="bi bi-patch-check"></i>
                </div>
                <h3>Unit 100% Original & Garansi</h3>
                <p>Produk resmi bergaransi 2 tahun dari prinsipal dunia seperti Hikvision, Dahua, HiLook, EZVIZ, UNV, dan Ruijie.</p>
            </div>
        </div>

        <!-- CTA Banner -->
        <div class="cta-wrapper-corp mb-4">
            <h2>Konsultasikan Kebutuhan Keamanan Properti Anda</h2>
            <p>Dapatkan jadwal survey lokasi gratis dan estimasi penawaran harga terbaik dari konsultan teknis kami hari ini.</p>
            <a href="https://wa.me/62881025756671?text=Halo%20TechStore%2C%20saya%20ingin%20konsultasi%20instalasi%20CCTV" target="_blank" class="cta-btn-corp">
                <i class="bi bi-whatsapp"></i> Hubungi Konsultan Teknis WA
            </a>
        </div>

    </div>
</div>
@endsection