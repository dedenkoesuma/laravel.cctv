<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman home/welcome
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. DATA BRANDS
        $brands = [
            ['name' => 'HIKVISION', 'slug' => 'hikvision', 'image' => 'hikvision.png'],
            ['name' => 'Dahua', 'slug' => 'dahua', 'image' => 'dahua.png'],
            ['name' => 'HiLook', 'slug' => 'hilook', 'image' => 'hilook.png'],
            ['name' => 'EZVIZ', 'slug' => 'ezviz', 'image' => 'ezviz.png'],
            ['name' => 'UNV', 'slug' => 'unv', 'image' => 'unv.png'],
            ['name' => 'RUIJIE', 'slug' => 'ruijie', 'image' => 'ruijie.png'],
            ['name' => 'HIVIEW', 'slug' => 'hiview', 'image' => 'hiview.png'],
        ];

        // 2. DATA WIFI CAMERAS (Dikembalikan ke 4 produk spesifik yang tadi)
        $wifiCameras = [
            [
                'name' => 'EZVIZ C6N 3MP', 
                'price' => '450000', 
                'desc' => '3MP 2K Smart Pan & Tilt Indoor WiFi Camera dengan resolusi tajam dan pelacakan pintar.', 
                'brand' => 'EZVIZ', 
                'image' => 'storage/wifi_cameras/v6LL8HisEC46kQvOxfigAV1CAwkhNPF56C2In0fL.png', 
                'slug' => 'ezviz-c6n-3mp-1777015443', // Jangan lupa sesuaikan kalau di URL aslinya ada angkanya
                'img_style' => 'transform: scale(1.1);'
            ],
            [
                'name' => 'EZVIZ H8c 2MP', 
                'price' => '650000', 
                'desc' => '2MP 1080P Smart Pan & Tilt Outdoor Camera dengan fitur perlindungan aktif dan night vision.', 
                'brand' => 'EZVIZ', 
                'image' => 'storage/wifi_cameras/rxaGgP1uLpfZtwyugs1AflJCCdbmmiLlLDPs64rK.png', 
                'slug' => 'h8c-2mp-1777210553', // Jangan lupa sesuaikan kalau di URL aslinya ada angkanya
                'img_style' => 'transform: scale(1.1);'
            ],
            [
                'name' => 'IMOU Ranger A1 3MP', 
                'price' => '430000', 
                'desc' => '3MP 2K Smart WiFi Pan & Tilt Indoor Camera dengan deteksi manusia dan mode privasi.', 
                'brand' => 'IMOU', 
                'image' => 'storage/wifi_cameras/xTI8ykz6668DaEkWUUIydb5k80RCjLig9BYgT1GH.jpg', 
                'slug' => 'ranger-a1-3mp-1777277541', 
                'img_style' => 'transform: scale(1.15); clip-path: inset(2% 2% 8% 2%);'
            ],
            [
                'name' => 'IMOU Cruiser 2 3MP', 
                'price' => '550000', 
                'desc' => '3MP Smart Home Pan & Tilt Outdoor WiFi Camera yang tahan cuaca ekstrem.', 
                'brand' => 'IMOU', 
                'image' => 'storage/wifi_cameras/OvQajIXCbMJ0G8Wtm0bXPpNd8ooxckx6so1xOOI6.webp', 
                'slug' => 'cruiser-2-3mp-1777283631', 
                'img_style' => 'transform: scale(2.2); transform-origin: center;'
            ],
        ];

        // 3. DATA PACKAGES
        $packages = [
            [
                'channel' => '2',
                'title' => '2MP 1080P HDCVI Camera',
                'price' => '2899000',
                'image' => 'storage/products/TeJYvHLEn3OmJs3mRGAHskW4cWO9EDpeSD81iinI.png',
                'badge_class' => 'bg-primary',
                'items' => ['Jasa Pasang & Konfigurasi', 'Garansi Unit 2 Tahun', '1 DVR 4 Channel', '2 Camera Indoor / Outdoor 2MP', '1 Hardisk 500GB', '30m Coaxial Cable', '4 Konektor BNC', '2 Konektor DC', '1 Kabel HDMI 1.5 Meter', 'Power Supply 4 Port'],
                'brand' => 'dahua'
            ],
            [
                'channel' => '4',
                'title' => '2MP 1080P HDCVI Camera',
                'price' => '4699000',
                'image' => 'storage/products/m3fLjMn24gT6zoguqqMGtMjg1mMeKTnwcGholNZl.png',
                'badge_class' => 'bg-success',
                'items' => ['Jasa Pasang & Konfigurasi', 'Garansi Unit 2 Tahun', '1 DVR 4 Channel', '4 Camera Indoor / Outdoor 2MP', '1 Hardisk 500GB', '60m Coaxial Cable', '8 Konektor BNC', '4 Konektor DC', '1 Kabel HDMI 1.5 Meter', 'Power Supply 4 Port'],
                'brand' => 'hilook'
            ],
            [
                'channel' => '8',
                'title' => '2MP 1080P Color Hunter',
                'price' => '8799000',
                'image' => 'storage/products/CftBpkpFDz5MRbLcB0WyICtapW66qkuSZUIXnApv.png',
                'badge_class' => 'bg-info',
                'items' => ['Jasa Pasang & Konfigurasi', 'Garansi Unit 2 Tahun', '1 DVR 8 Channel', '8 Camera Indoor / Outdoor 2MP', '1 Hardisk 1 TB', '120m Coaxial Cable', '16 Konektor BNC', '8 Konektor DC', '1 Kabel HDMI 1.5 Meter', 'Power Supply 8 Port'],
                'brand' => 'unv'
            ],
            [
                'channel' => '16',
                'title' => '2MP 1080P Network Camera',
                'price' => '29899000',
                'image' => 'storage/products/UHJzPJfliIFuX8C3mAnxYeZqZCuidRtykeBXYxei.png',
                'badge_class' => 'bg-danger',
                'items' => ['Jasa Pasang & Konfigurasi', 'Garansi Unit 2 Tahun', '1 DVR 16 Channel', '16 Camera Indoor / Outdoor 2MP', '1 Hardisk 4 TB', '240m Coaxial Cable', '32 Konektor BNC', '16 Konektor DC', '1 Kabel HDMI 1.5 Meter', 'Power Supply 8 Port'],
                'brand' => 'hikvision'
            ],
        ];

        // 4. DATA ACCESS CONTROLS
        $accessControls = [
            ['id' => 1, 'name' => 'MB20', 'price' => '1032000', 'desc' => 'Time Attendance & Access Control', 'brand' => 'ZKTeco'],
            ['id' => 2, 'name' => 'DS-K1T343MX', 'price' => '1989000', 'desc' => 'Access Control', 'brand' => 'HIKVISION'],
            ['id' => 3, 'name' => 'SF300', 'price' => '2779000', 'desc' => 'Time Attendance & Access Control', 'brand' => 'ZKTeco'],
            ['id' => 4, 'name' => 'LH6500', 'price' => '2589000', 'desc' => 'Hotel Lock', 'brand' => 'ZKTeco'],
        ];

        // 5. DATA SERVICES
        $services = [
            ['icon' => '🏠', 'title' => 'Rumah', 'description' => 'CCTV membantu mencegah tindak kejahatan seperti pencurian atau perusakan karena keberadaannya sering menjadi penghalang psikologis bagi pelaku kriminal.'],
            ['icon' => '🔒', 'title' => 'Toko', 'description' => 'Kamera CCTV Mencegah pencurian oleh pelanggan atau karyawan, Mengawasi area stok atau gudang untuk menghindari kehilangan barang.'],
            ['icon' => '🏢', 'title' => 'Kantor', 'description' => 'Keberadaan CCTV membuat karyawan, pelanggan, dan pengunjung lebih berhati-hati dalam berperilaku dan membantu menjaga lingkungan kerja yang kondusif.'],
        ];

        return view('welcome', compact('brands', 'wifiCameras', 'packages', 'accessControls', 'services'));
    }
}