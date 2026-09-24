@extends('layouts.simple')

@section('title', 'Hubungi Kami - PT. MJA TEKNOLOGI | Layanan Pengadaan & Jasa Pasang CCTV')
@section('meta_description', 'Hubungi PT. MJA TEKNOLOGI untuk konsultasi instalasi CCTV, permintaan survey lokasi gratis, dan penawaran harga resmi perangkat keamanan.')

@section('content')
<style>
/* ===== CONTACT PAGE CORPORATE DESIGN ===== */
.contact-header {
    background-color: #0f172a;
    color: #ffffff;
    padding: 60px 0 50px;
    border-bottom: 1px solid var(--ts-slate-800);
}

.contact-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fca5a5;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 14px;
    border-radius: 4px;
    margin-bottom: 16px;
    letter-spacing: 0.5px;
}

.contact-title {
    font-size: clamp(1.8rem, 3.5vw, 2.6rem);
    font-weight: 800;
    letter-spacing: -0.02em;
    margin-bottom: 12px;
    color: #ffffff;
}

.contact-subtitle {
    font-size: 15px;
    color: #94a3b8;
    max-width: 650px;
    line-height: 1.6;
}

.contact-card-info {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 12px;
    padding: 24px;
    height: 100%;
    transition: all 0.25s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.contact-card-info:hover {
    border-color: var(--ts-slate-300);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.06);
}

.contact-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background-color: var(--ts-primary-light);
    color: var(--ts-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 18px;
}

.contact-card-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--ts-slate-900);
    margin-bottom: 8px;
}

.contact-card-text {
    font-size: 13.5px;
    color: var(--ts-slate-600);
    line-height: 1.6;
    margin-bottom: 0;
}

.contact-form-card {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 14px;
    padding: 36px;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
}

.form-label-custom {
    font-size: 13px;
    font-weight: 600;
    color: var(--ts-slate-800);
    margin-bottom: 6px;
}

.form-control-custom, .form-select-custom {
    border: 1px solid var(--ts-slate-300);
    border-radius: 8px;
    padding: 11px 14px;
    font-size: 14px;
    color: var(--ts-slate-900);
    transition: all 0.2s ease;
}

.form-control-custom:focus, .form-select-custom:focus {
    border-color: var(--ts-primary);
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    outline: none;
}

.faq-item-custom {
    background: #ffffff;
    border: 1px solid var(--ts-slate-200);
    border-radius: 10px;
    margin-bottom: 12px;
    overflow: hidden;
}

.faq-button-custom {
    width: 100%;
    padding: 16px 20px;
    background: #ffffff;
    border: none;
    text-align: left;
    font-size: 14.5px;
    font-weight: 600;
    color: var(--ts-slate-800);
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: background-color 0.2s;
}

.faq-button-custom:hover {
    background-color: var(--ts-slate-50);
}

.faq-answer-custom {
    padding: 0 20px 16px;
    font-size: 13.5px;
    color: var(--ts-slate-600);
    line-height: 1.6;
}
</style>

<!-- HEADER -->
<section class="contact-header">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Kontak & Konsultasi</li>
            </ol>
        </nav>
        
        <div class="contact-chip">
            <i class="bi bi-headset"></i> Layanan Konsultasi & Pengadaan
        </div>
        <h1 class="contact-title">Hubungi Tim PT. MJA TEKNOLOGI</h1>
        <p class="contact-subtitle mb-0">
            Diskusikan kebutuhan tata letak CCTV, peremajaan perangkat, permintaan survey lokasi gratis, maupun permohonan penawaran resmi (Quotation/RAB).
        </p>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="py-5" style="background-color: var(--ts-slate-50);">
    <div class="container py-2">
        <!-- INFO CARDS -->
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="contact-card-info">
                    <div class="contact-card-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="contact-card-title">Kantor Operasional</div>
                    <p class="contact-card-text">
                        <strong>PT. MJA TEKNOLOGI</strong><br>
                        Pusat Integrasi Sistem Keamanan & CCTV Terpercaya<br>
                        Jakarta & Jabodetabek, Indonesia
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="contact-card-info">
                    <div class="contact-card-icon" style="background-color: rgba(5, 150, 105, 0.12); color: #059669;">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div class="contact-card-title">Konsultasi WhatsApp</div>
                    <p class="contact-card-text">
                        Respon cepat dari teknisi & tim sales:<br>
                        <a href="https://wa.me/62881025756671" target="_blank" class="fw-bold text-decoration-none" style="color: #059669;">
                            +62 881-0257-56671
                        </a><br>
                        <span class="text-muted small">Tersedia panggilan suara & chat</span>
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="contact-card-info">
                    <div class="contact-card-icon" style="background-color: rgba(37, 99, 235, 0.12); color: #2563eb;">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="contact-card-title">Email Perusahaan</div>
                    <p class="contact-card-text">
                        Pengiriman dokumen RAB & tender:<br>
                        <a href="mailto:admin@techstorecctv.com" class="fw-bold text-decoration-none text-dark">
                            admin@techstorecctv.com
                        </a><br>
                        <span class="text-muted small">Terkirim langsung ke divisi pengadaan</span>
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="contact-card-info">
                    <div class="contact-card-icon" style="background-color: rgba(217, 119, 6, 0.12); color: #d97706;">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="contact-card-title">Jam Operasional</div>
                    <p class="contact-card-text">
                        <strong>Senin - Sabtu:</strong> 08:30 - 17:30 WIB<br>
                        <strong>Minggu / Libur:</strong> Janji temu survey<br>
                        <span class="badge bg-success-subtle text-success mt-1">Layanan Darurat 24/7 Siaga</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- FORM & FAQ ROW -->
        <div class="row g-4">
            <!-- CONSULTATION FORM (INTERACTIVE WHATSAPP PREFILL) -->
            <div class="col-lg-7">
                <div class="contact-form-card">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-1 fw-bold" style="font-size: 11px;">
                            FORMULIR KONSULTASI
                        </span>
                        <span class="text-muted small">Gratis Tanpa Biaya Komitmen</span>
                    </div>
                    <h3 class="fw-bold mb-3" style="color: var(--ts-slate-900); font-size: 20px;">
                        Permintaan Survey Lokasi & Estimasi Anggaran
                    </h3>
                    <p class="text-muted small mb-4">
                        Isi form di bawah untuk langsung terhubung dengan teknisi kami via WhatsApp dengan ringkasan kebutuhan Anda.
                    </p>

                    <form id="consultationForm" onsubmit="handleContactSubmit(event)">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Nama Lengkap / Instansi</label>
                                <input type="text" id="custName" class="form-control form-control-custom" placeholder="Contoh: Bpk. Hendra / PT. Tri Jaya" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Nomor WhatsApp Aktif</label>
                                <input type="tel" id="custPhone" class="form-control form-control-custom" placeholder="Contoh: 08123456789" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">Jenis Properti</label>
                                <select id="custProperty" class="form-select form-select-custom">
                                    <option value="Rumah Tinggal / Hunian">Rumah Tinggal / Cluster</option>
                                    <option value="Ruko / Toko / Minimarket">Ruko / Toko / Minimarket</option>
                                    <option value="Kantor / Gedung Perusahaan">Kantor / Gedung Perusahaan</option>
                                    <option value="Gudang / Pabrik Industri">Gudang / Pabrik Industri</option>
                                    <option value="Sekolah / Yayasan / Tempat Ibadah">Sekolah / Yayasan / Rumah Ibadah</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Kebutuhan Sistem</label>
                                <select id="custNeed" class="form-select form-select-custom">
                                    <option value="Paket Lengkap CCTV 4 Channel">Paket Lengkap CCTV 4 Channel</option>
                                    <option value="Paket Lengkap CCTV 8 Channel">Paket Lengkap CCTV 8 Channel</option>
                                    <option value="Paket Lengkap CCTV 16 Channel">Paket Lengkap CCTV 16 Channel</option>
                                    <option value="Kamera WiFi Nirkabel Mandiri">Kamera WiFi Nirkabel Mandiri</option>
                                    <option value="Sistem Akses Kontrol Pintu / Absensi">Sistem Akses Kontrol Pintu / Absensi</option>
                                    <option value="Custom Proyek Pengadaan Besar">Custom Proyek Pengadaan Besar</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">Alamat / Lokasi Pemasangan</label>
                                <input type="text" id="custAddress" class="form-control form-control-custom" placeholder="Contoh: Kebayoran Baru, Jakarta Selatan">
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">Catatan Tambahan (Opsional)</label>
                                <textarea id="custNotes" class="form-control form-control-custom" rows="3" placeholder="Sebutkan kebutuhan khusus seperti jarak kabel, kabel tanam pipa, atau target waktu pemasangan..."></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-danger w-100 py-3 rounded-2 fw-bold d-flex align-items-center justify-content-center gap-2" style="font-size: 15px;">
                                    <i class="bi bi-whatsapp fs-5"></i>
                                    <span>Kirim Permintaan Konsultasi via WhatsApp</span>
                                </button>
                                <small class="text-muted text-center d-block mt-2">
                                    <i class="bi bi-shield-lock me-1"></i> Data Anda aman dan hanya digunakan untuk keperluan koordinasi survey teknis.
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FAQ & TRUST SIDEBAR -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4" style="background: #ffffff; border: 1px solid var(--ts-slate-200) !important;">
                    <h4 class="fw-bold mb-3" style="font-size: 17px; color: var(--ts-slate-900);">
                        <i class="bi bi-question-circle-fill text-danger me-2"></i>Pertanyaan Umum (FAQ)
                    </h4>

                    <div class="faq-item-custom">
                        <button class="faq-button-custom" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            <span>Apakah survey lokasi dikenakan biaya?</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div id="faq1" class="collapse show faq-answer-custom">
                            Tidak, survey denah dan penentuan titik kamera di area operasional kami adalah <strong>gratis tanpa pungutan biaya</strong>.
                        </div>
                    </div>

                    <div class="faq-item-custom">
                        <button class="faq-button-custom" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            <span>Bisa menerbitkan faktur pajak resmi?</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div id="faq2" class="collapse faq-answer-custom">
                            Bisa. PT. MJA TEKNOLOGI merupakan entitas perusahaan resmi dan siap menyediakan Faktur Pajak PPN, Surat Penawaran Harga (SPH), dan invoice resmi untuk kebutuhan korporasi maupun instansi pemerintah.
                        </div>
                    </div>

                    <div class="faq-item-custom">
                        <button class="faq-button-custom" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            <span>Berapa lama garansi yang diberikan?</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div id="faq3" class="collapse faq-answer-custom">
                            Semua paket instalasi CCTV dan unit DVR/NVR bergaransi resmi <strong>2 tahun</strong> dengan dukungan teknisi siap bantu jika ada kendala setting atau gambar.
                        </div>
                    </div>

                    <div class="faq-item-custom">
                        <button class="faq-button-custom" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            <span>Kabelnya pakai pipa pelindung?</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div id="faq4" class="collapse faq-answer-custom">
                            Ya, standar instalasi kami selalu mengutamakan kerapian dan keamanan dengan perlindungan pipa conduit PVC tahan gigitan tikus dan tahan panas.
                        </div>
                    </div>
                </div>

                <!-- DIRECT CALL CARD -->
                <div class="card border-0 rounded-3 p-4 text-white" style="background-color: var(--ts-slate-900);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: rgba(220, 38, 38, 0.2); color: #f87171; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="bi bi-telephone-inbound-fill"></i>
                        </div>
                        <div>
                            <div class="text-white-50 small">Butuh Respon Cepat Segera?</div>
                            <div class="fw-bold" style="font-size: 15px;">Hubungi Hotline Support Langsung</div>
                        </div>
                    </div>
                    <p class="text-white-50 small mb-3">
                        Teknisi kami siap menjawab spesifikasi kamera yang tepat untuk ukuran ruangan atau properti Anda.
                    </p>
                    <a href="https://wa.me/62881025756671?text=Halo%20PT.%20MJA%20TEKNOLOGI,%20saya%20ingin%20konsultasi%20langsung%20terkait%20pemasangan%20CCTV." target="_blank" class="btn btn-outline-light w-100 py-2.5 rounded-2 fw-semibold" style="font-size: 13.5px;">
                        <i class="bi bi-whatsapp me-1 text-success"></i> Hubungi 0881-0257-56671
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function handleContactSubmit(e) {
    e.preventDefault();
    const name = document.getElementById('custName').value.trim();
    const phone = document.getElementById('custPhone').value.trim();
    const property = document.getElementById('custProperty').value;
    const need = document.getElementById('custNeed').value;
    const address = document.getElementById('custAddress').value.trim();
    const notes = document.getElementById('custNotes').value.trim();

    let text = `Halo PT. MJA TEKNOLOGI,\n\nSaya ingin konsultasi / permintaan survey pemasangan CCTV:\n`;
    text += `*Nama:* ${name}\n`;
    text += `*No. WA:* ${phone}\n`;
    text += `*Jenis Properti:* ${property}\n`;
    text += `*Kebutuhan Sistem:* ${need}\n`;
    if (address) text += `*Lokasi / Alamat:* ${address}\n`;
    if (notes) text += `*Catatan Khusus:* ${notes}\n`;
    text += `\nMohon info estimasi penawaran atau jadwal survey lokasi. Terima kasih.`;

    const waUrl = `https://wa.me/62881025756671?text=${encodeURIComponent(text)}`;
    window.open(waUrl, '_blank');
}
</script>

<x-ai-assistant />
@endsection
