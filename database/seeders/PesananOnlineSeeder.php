<?php

namespace Database\Seeders;

use App\Models\PesananOnline;
use Illuminate\Database\Seeder;

class PesananOnlineSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['pelanggan' => 'Siti Rahma',      'platform' => 'WA',        'tipe_kertas' => 'Foto glossy',  'jumlah_lembar' => 10,  'total' => 35000,  'status' => 'Selesai'],
            ['pelanggan' => 'Budi Santoso',     'platform' => 'Tokopedia', 'tipe_kertas' => 'A4 80gr BW',   'jumlah_lembar' => 50,  'total' => 25000,  'status' => 'Selesai'],
            ['pelanggan' => 'Ahmad Fauzi',      'platform' => 'Shopee',    'tipe_kertas' => 'A4 warna',     'jumlah_lembar' => 30,  'total' => 45000,  'status' => 'Proses'],
            ['pelanggan' => 'Dewi Lestari',     'platform' => 'WA',        'tipe_kertas' => 'F4 BW',        'jumlah_lembar' => 120, 'total' => 54000,  'status' => 'Proses'],
            ['pelanggan' => 'Roni Kurnia',      'platform' => 'Instagram', 'tipe_kertas' => 'A3 80gr',      'jumlah_lembar' => 20,  'total' => 60000,  'status' => 'Selesai'],
            ['pelanggan' => 'Maya Putri',       'platform' => 'WA',        'tipe_kertas' => 'A4 70gr BW',   'jumlah_lembar' => 80,  'total' => 32000,  'status' => 'Proses'],
            ['pelanggan' => 'Hendra W.',        'platform' => 'Tokopedia', 'tipe_kertas' => 'Foto glossy',  'jumlah_lembar' => 25,  'total' => 87500,  'status' => 'Selesai'],
            ['pelanggan' => 'Fitri Handayani',  'platform' => 'Shopee',    'tipe_kertas' => 'A4 warna',     'jumlah_lembar' => 15,  'total' => 22500,  'status' => 'Dibatalkan'],
        ];

        foreach ($data as $i => $item) {
            PesananOnline::create([
                ...$item,
                'no_order' => 'ORD-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'catatan'  => null,
            ]);
        }
    }
}