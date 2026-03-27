<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StaticProductsSeeder extends Seeder
{
    public function run()
    {
        // Cek kolom yang ada di tabel
        $columns = Schema::getColumnListing('static_products');
        $this->command->info('Available columns: ' . implode(', ', $columns));

        // Hapus data lama jika ada
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('static_products')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Exception $e) {
            $this->command->warn('Could not truncate table: ' . $e->getMessage());
            DB::table('static_products')->delete();
        }

        $products = [];
        
        // =====================================================
        // HIKVISION - 128 Products (32 per tab)
        // =====================================================
        $hikModels = [
            'basic' => [
                'DS-2CE16D0T-IT3F', 'DS-2CE56D0T-IRPF', 'DS-2CE16H0T-IT3F', 'DS-2CE56H0T-ITPF',
                'DS-2CE16D0T-IRPF', 'DS-2CE16C0T-IR', 'DS-2CE56C0T-IR', 'DS-2CE16D0T-IRF',
                'DS-2CE16D0T-IT1F', 'DS-2CE56D0T-IT1F', 'DS-2CE16H0T-IRPF', 'DS-2CE56H0T-IRPF',
                'DS-2CE16D0T-IRP', 'DS-2CE56D0T-IRP', 'DS-2CE10D0T-IRF', 'DS-2CE50D0T-IRF',
                'DS-2CE16C0T-IRP', 'DS-2CE56C0T-IRP', 'DS-2CE16D0T-IT5F', 'DS-2CE56D0T-IT5F',
                'DS-2CE16H0T-IT1F', 'DS-2CE56H0T-IT1F', 'DS-2CE12D0T-IR', 'DS-2CE52D0T-IR',
                'DS-2CE16D8T-IT3F', 'DS-2CE56D8T-IT3F', 'DS-2CE10D0T-IRP', 'DS-2CE50D0T-IRP',
                'DS-2CE16C0T-IT3F', 'DS-2CE56C0T-IT3F', 'DS-2CE16D0T-ITF', 'DS-2CE56D0T-ITF'
            ],
            'audio' => [
                'DS-2CE16D0T-IT3F-A', 'DS-2CE56D0T-VPIRF', 'DS-2CE16H0T-IT3F-A', 'DS-2CE56H0T-VPIRF',
                'DS-2CE12DF0T-F', 'DS-2CE72DFT-F', 'DS-2CE16D0T-VPIT3F', 'DS-2CE56D0T-VPITF',
                'DS-2CE16H0T-VPIT3F', 'DS-2CE56H0T-VPITF', 'DS-2CE12DF3T-F', 'DS-2CE72DF3T-F',
                'DS-2CE16D0T-IT5F-A', 'DS-2CE56D0T-IT5F-A', 'DS-2CE16H0T-IT5F-A', 'DS-2CE56H0T-IT5F-A',
                'DS-2CE10DF0T-F-A', 'DS-2CE70DF0T-F-A', 'DS-2CE12DFT-F-A', 'DS-2CE72DFT-F-A',
                'DS-2CE16D8T-IT3F-A', 'DS-2CE56D8T-IT3F-A', 'DS-2CE16C0T-VPIT', 'DS-2CE56C0T-VPIT',
                'DS-2CE10D0T-VPITF', 'DS-2CE50D0T-VPITF', 'DS-2CE12D0T-VPIT', 'DS-2CE52D0T-VPIT',
                'DS-2CE16D0T-AVFIT', 'DS-2CE56D0T-AVFIT', 'DS-2CE16H0T-AVFIT', 'DS-2CE56H0T-AVFIT'
            ],
            'full_color' => [
                'DS-2CE12DF0T-FS', 'DS-2CE72DF0T-FS', 'DS-2CE12HFT-F', 'DS-2CE72HFT-F',
                'DS-2CE10DF0T-F', 'DS-2CE70DF0T-MF', 'DS-2CE12DF3T-FS', 'DS-2CE72DF3T-FS',
                'DS-2CE12HFT-FS', 'DS-2CE72HFT-FS', 'DS-2CE10HFT-F', 'DS-2CE70HFT-F',
                'DS-2CE16DF0T-F', 'DS-2CE76DF0T-F', 'DS-2CE16HFT-F', 'DS-2CE76HFT-F',
                'DS-2CE12DF8T-FS', 'DS-2CE72DF8T-FS', 'DS-2CE10DF8T-F', 'DS-2CE70DF8T-F',
                'DS-2CE16DF8T-F', 'DS-2CE76DF8T-F', 'DS-2CE12DFT-FC', 'DS-2CE72DFT-FC',
                'DS-2CE10DFT-FC', 'DS-2CE70DFT-FC', 'DS-2CE16DFT-FC', 'DS-2CE76DFT-FC',
                'DS-2CE12HFTC-F', 'DS-2CE72HFTC-F', 'DS-2CE16HFTC-F', 'DS-2CE76HFTC-F'
            ],
            'ip_camera' => [
                'DS-2CD1023G0-I', 'DS-2CD1123G0-I', 'DS-2CD2043G0-I', 'DS-2CD2143G0-I',
                'DS-2CD2T83G0-I8', 'DS-2CD1323G0-I', 'DS-2CD2023G0-I', 'DS-2CD2643G0-IZS',
                'DS-2CD1043G0-I', 'DS-2CD1143G0-I', 'DS-2CD2083G0-I', 'DS-2CD2183G0-I',
                'DS-2CD1021-I', 'DS-2CD1121-I', 'DS-2CD2021-I', 'DS-2CD2121-I',
                'DS-2CD1023G2-I', 'DS-2CD1123G2-I', 'DS-2CD2043G2-I', 'DS-2CD2143G2-I',
                'DS-2CD1031-I', 'DS-2CD1131-I', 'DS-2CD2031-I', 'DS-2CD2131-I',
                'DS-2CD1023G0E-I', 'DS-2CD1123G0E-I', 'DS-2CD2023G0E-I', 'DS-2CD2123G0E-I',
                'DS-2CD1043G2-I', 'DS-2CD1143G2-I', 'DS-2CD2043G2-IU', 'DS-2CD2143G2-IU'
            ]
        ];

        $position = 1;
        foreach ($hikModels as $tab => $models) {
            foreach ($models as $model) {
                $product = $this->buildProductArray('hikvision', $tab, $model, 
                    rand(300000, 2500000), rand(10, 100), $position++, $columns);
                $products[] = $product;
            }
        }

        // =====================================================
        // DAHUA - 128 Products (32 per tab)
        // =====================================================
        $dahuaModels = [
            'basic' => [
                'HAC-HFW1200TLP', 'HAC-HDW1200TLP', 'HAC-HFW1500TLP', 'HAC-HDW1500TLP',
                'HAC-HFW1000RP', 'HAC-HDW1000RP', 'HAC-HFW1200RP', 'HAC-HDW1200RP',
                'HAC-HFW1200TLP-Z', 'HAC-HDW1200TLP-Z', 'HAC-HFW1500RP', 'HAC-HDW1500RP',
                'HAC-HFW1000TLP', 'HAC-HDW1000TLP', 'HAC-HFW1239TLP', 'HAC-HDW1239TLP',
                'HAC-HFW1200CP', 'HAC-HDW1200CP', 'HAC-HFW1500CP', 'HAC-HDW1500CP',
                'HAC-HFW1200MP', 'HAC-HDW1200MP', 'HAC-HFW1000MP', 'HAC-HDW1000MP',
                'HAC-HFW1200BP', 'HAC-HDW1200BP', 'HAC-HFW1500BP', 'HAC-HDW1500BP',
                'HAC-HFW1200DP', 'HAC-HDW1200DP', 'HAC-HFW1239DP', 'HAC-HDW1239DP'
            ],
            'audio' => [
                'HAC-HFW1200TLP-A', 'HAC-HDW1200TLP-A', 'HAC-HFW1500TLP-A', 'HAC-HDW1500TLP-A',
                'HAC-HFW2249T-A', 'HAC-HDW2249T-A', 'HAC-HFW1239TLP-A', 'HAC-HDW1239TLP-A',
                'HAC-HFW1200TLP-A-Z', 'HAC-HDW1200TLP-A-Z', 'HAC-HFW1500RP-A', 'HAC-HDW1500RP-A',
                'HAC-HFW2241T-A', 'HAC-HDW2241T-A', 'HAC-HFW2249TP-A', 'HAC-HDW2249TP-A',
                'HAC-HFW1200CP-A', 'HAC-HDW1200CP-A', 'HAC-HFW1500CP-A', 'HAC-HDW1500CP-A',
                'HAC-HFW2231T-A', 'HAC-HDW2231T-A', 'HAC-HFW2241TP-A', 'HAC-HDW2241TP-A',
                'HAC-HFW1239T-A', 'HAC-HDW1239T-A', 'HAC-HFW1200MP-A', 'HAC-HDW1200MP-A',
                'HAC-HFW1500MP-A', 'HAC-HDW1500MP-A', 'HAC-HFW2249T-A-I2', 'HAC-HDW2249T-A-I2'
            ],
            'full_color' => [
                'HAC-HFW1239C-A-LED', 'HAC-HDW1239C-A-LED', 'HAC-HFW1509C-A-LED', 'HAC-HDW1509C-A-LED',
                'HAC-HFW2249TP-A-LED', 'HAC-HDW2249TP-A-LED', 'HAC-HFW1239C-LED', 'HAC-HDW1239C-LED',
                'HAC-HFW1509C-LED', 'HAC-HDW1509C-LED', 'HAC-HFW2249T-LED', 'HAC-HDW2249T-LED',
                'HAC-HFW1239TLMN-LED', 'HAC-HDW1239TLMN-LED', 'HAC-HFW1509TLMN-LED', 'HAC-HDW1509TLMN-LED',
                'HAC-HFW2249TN-LED', 'HAC-HDW2249TN-LED', 'HAC-HFW1239CP-LED', 'HAC-HDW1239CP-LED',
                'HAC-HFW1509CP-LED', 'HAC-HDW1509CP-LED', 'HAC-HFW2249TP-LED', 'HAC-HDW2249TP-LED',
                'HAC-HFW1239C-A-LED-S2', 'HAC-HDW1239C-A-LED-S2', 'HAC-HFW1509C-LED-S2', 'HAC-HDW1509C-LED-S2',
                'HAC-HFW2249T-A-LED-S2', 'HAC-HDW2249T-A-LED-S2', 'HAC-HFW1800C-LED', 'HAC-HDW1800C-LED'
            ],
            'ip_camera' => [
                'IPC-HFW1230S', 'IPC-HDW1230S', 'IPC-HFW2431S', 'IPC-HDW2431T',
                'IPC-HFW4831E', 'IPC-HFW1230T', 'IPC-HDW1230T', 'IPC-HFW2531T-ZS',
                'IPC-HFW1230S1', 'IPC-HDW1230S1', 'IPC-HFW2431SP', 'IPC-HDW2431TP',
                'IPC-HFW1239S', 'IPC-HDW1239S', 'IPC-HFW2439S', 'IPC-HDW2439S',
                'IPC-HFW1239T', 'IPC-HDW1239T', 'IPC-HFW2439T', 'IPC-HDW2439T',
                'IPC-HFW1230S-S5', 'IPC-HDW1230S-S5', 'IPC-HFW2431S-S5', 'IPC-HDW2431T-S5',
                'IPC-HFW2231S', 'IPC-HDW2231S', 'IPC-HFW2831S', 'IPC-HDW2831S',
                'IPC-HFW1230S-W', 'IPC-HDW1230S-W', 'IPC-HFW2431S-W', 'IPC-HDW2431T-W'
            ]
        ];

        foreach ($dahuaModels as $tab => $models) {
            foreach ($models as $model) {
                $product = $this->buildProductArray('dahua', $tab, $model, 
                    rand(300000, 2500000), rand(10, 100), $position++, $columns);
                $products[] = $product;
            }
        }

        // Brands lainnya dengan struktur yang sama...
        $allBrands = [
            'hilook' => [
                'basic' => ['THC-B120-M', 'THC-T120-M', 'THC-B140-M', 'THC-T140-M'],
                'audio' => ['THC-B120-M-A', 'THC-T120-M-A', 'THC-B140-M-A', 'THC-T140-M-A'],
                'full_color' => ['THC-B129-M', 'THC-T129-M', 'THC-B149-M', 'THC-T149-M'],
                'ip_camera' => ['IPC-B120H', 'IPC-T120H', 'IPC-B140H', 'IPC-T140H']
            ],
            'ezviz' => [
                'basic' => ['C3W', 'C3WN', 'C3X', 'C3N'],
                'audio' => ['C3W-A', 'C3WN-A', 'C3X-A', 'C6N-A'],
                'full_color' => ['C3W Pro', 'C3X Pro', 'C6N Pro', 'C6W Pro'],
                'ip_camera' => ['C3W WiFi', 'C3WN WiFi', 'C6N WiFi', 'C6CN WiFi']
            ]
        ];

        foreach ($allBrands as $brand => $categories) {
            foreach ($categories as $tab => $models) {
                foreach ($models as $model) {
                    $product = $this->buildProductArray($brand, $tab, $model, 
                        rand(250000, 1800000), rand(15, 90), $position++, $columns);
                    $products[] = $product;
                }
            }
        }

        // Insert produk dalam batch
        try {
            $this->command->info('Inserting products...');
            $inserted = 0;
            
            foreach (array_chunk($products, 50) as $index => $chunk) {
                DB::table('static_products')->insert($chunk);
                $inserted += count($chunk);
                $this->command->info("Inserted batch " . ($index + 1) . " ({$inserted} products so far)");
            }
            
            $totalProducts = count($products);
            $this->command->info("✅ {$totalProducts} Static products seeded successfully!");
            $this->command->info('📋 Categories: Basic, Audio, Full Color, IP Camera');
            $this->command->info('🏢 Brands:');
            $this->command->info('   - HIKVISION: 128 products');
            $this->command->info('   - Dahua: 128 products');
            $this->command->info('   - HiLook: 16 products');
            $this->command->info('   - EZVIZ: 16 products');
        } catch (\Exception $e) {
            $this->command->error('Error: ' . $e->getMessage());
            if (isset($products[0])) {
                $this->command->error('First product structure: ' . json_encode($products[0], JSON_PRETTY_PRINT));
            }
        }
    }

    private function buildProductArray($brand, $tab, $modelName, $price, $stock, $position, $columns)
    {
        $product = [
            'brand' => $brand,
            'tab_category' => $tab,
            'nama_produk' => $modelName,
            'sku' => strtoupper(substr($brand, 0, 3) . '-' . $tab . '-' . str_pad($position, 4, '0', STR_PAD_LEFT)),
            'kategori' => ucfirst($tab),
            'harga_jual' => $price,
            'harga_modal' => (int)($price * 0.7), // 70% dari harga jual
            'stok' => $stock,
            'position' => $position,
            'is_active' => 1,
            'deskripsi' => "CCTV Camera {$modelName} - {$brand} {$tab} series",
            'gambar' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];

        return $product;
    }

    private function hasColumn($columns, $possibleNames)
    {
        foreach ($possibleNames as $name) {
            if (in_array($name, $columns)) {
                return true;
            }
        }
        return false;
    }

    private function getColumn($columns, $possibleNames)
    {
        foreach ($possibleNames as $name) {
            if (in_array($name, $columns)) {
                return $name;
            }
        }
        return $possibleNames[0]; // fallback
    }
}