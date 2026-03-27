<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "🌱 Seeding database...\n\n";
        
        // 1. Seed Admin User
        $this->seedAdmins();
        
        // 2. Seed Static Products (512 items)
        $this->seedStaticProducts();
        
        // 3. Seed Categories (optional)
        $this->seedKategori();
        
        echo "\n✅ Database seeding completed!\n";
    }
    
    /**
     * Seed admin users
     */
    private function seedAdmins()
    {
        echo "👤 Seeding admins...\n";
        
        DB::table('admins')->insert([
            'name' => 'Administrator',
            'email' => 'admin@techstore.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "   ✓ Created 1 admin user (admin/admin123)\n";
    }
    
    /**
     * Seed static products (512 items)
     */
    private function seedStaticProducts()
    {
        echo "📦 Seeding static products...\n";
        
        $brands = [
            'hikvision' => 'HIKVISION',
            'dahua' => 'Dahua',
            'hilook' => 'HiLook',
            'ezviz' => 'EZVIZ',
            'unv' => 'UNV',
            'ruijie' => 'RUIJIE',
            'hiview' => 'HIVIEW',
            'foreage' => 'Foreage'
        ];
        
        $totalInserted = 0;
        
        foreach ($brands as $brandKey => $brandName) {
            echo "   → Processing {$brandName}...\n";
            
            // Basic Products (16 items)
            $this->createBasicProducts($brandKey, $brandName);
            $totalInserted += 16;
            
            // Audio Products (16 items)
            $this->createAudioProducts($brandKey, $brandName);
            $totalInserted += 16;
            
            // Full Color Products (16 items)
            $this->createFullColorProducts($brandKey, $brandName);
            $totalInserted += 16;
            
            // IP Camera Products (16 items)
            $this->createIPCameraProducts($brandKey, $brandName);
            $totalInserted += 16;
        }
        
        echo "   ✓ Created {$totalInserted} static products\n";
    }
    
    /**
     * Create Basic DVR products
     */
    private function createBasicProducts($brandKey, $brandName)
    {
        $channels = [2, 4, 8, 16];
        $position = 1;
        
        foreach ($channels as $channel) {
            for ($i = 0; $i < 4; $i++) {
                $basePrice = 1500000 + ($channel * 200000) + ($i * 100000);
                
                DB::table('static_products')->insert([
                    'brand' => $brandKey,
                    'tab_category' => 'basic',
                    'position' => $position++,
                    'sku' => strtoupper($brandKey) . '-DVR-' . $channel . 'CH-' . ($i + 1),
                    'kategori' => 'DVR',
                    'nama_produk' => "DVR {$channel} Channel 1080P Full HD - Series " . ($i + 1),
                    'harga_modal' => $basePrice * 0.7,
                    'harga_jual' => $basePrice,
                    'stok' => rand(5, 50),
                    'deskripsi' => "{$channel}CH 1080P DVR\nH.265+ Compression\nSupports HDD up to 10TB\nRemote viewing via mobile app\nMotion detection\nEmail alerts",
                    'gambar' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    /**
     * Create Audio Recording products
     */
    private function createAudioProducts($brandKey, $brandName)
    {
        $channels = [2, 4, 8, 16];
        $position = 1;
        
        foreach ($channels as $channel) {
            for ($i = 0; $i < 4; $i++) {
                $basePrice = 1800000 + ($channel * 250000) + ($i * 120000);
                
                DB::table('static_products')->insert([
                    'brand' => $brandKey,
                    'tab_category' => 'audio',
                    'position' => $position++,
                    'sku' => strtoupper($brandKey) . '-AUD-' . $channel . 'CH-' . ($i + 1),
                    'kategori' => 'DVR Audio',
                    'nama_produk' => "DVR {$channel}CH Audio Recording - Series " . ($i + 1),
                    'harga_modal' => $basePrice * 0.7,
                    'harga_jual' => $basePrice,
                    'stok' => rand(5, 40),
                    'deskripsi' => "{$channel}CH Audio DVR\nBuilt-in audio input\nAAC audio compression\nTwo-way audio support\nNoise reduction\nH.265+ video",
                    'gambar' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    /**
     * Create Full Color Night Vision products
     */
    private function createFullColorProducts($brandKey, $brandName)
    {
        $resolutions = ['2MP', '4MP', '5MP', '8MP'];
        $position = 1;
        
        foreach ($resolutions as $res) {
            for ($i = 0; $i < 4; $i++) {
                $megapixel = (int)filter_var($res, FILTER_SANITIZE_NUMBER_INT);
                $basePrice = 2000000 + ($megapixel * 300000) + ($i * 150000);
                
                DB::table('static_products')->insert([
                    'brand' => $brandKey,
                    'tab_category' => 'fullcolor',
                    'position' => $position++,
                    'sku' => strtoupper($brandKey) . '-FC-' . $res . '-' . ($i + 1),
                    'kategori' => 'Full Color Camera',
                    'nama_produk' => "Full Color Night Vision {$res} - Series " . ($i + 1),
                    'harga_modal' => $basePrice * 0.7,
                    'harga_jual' => $basePrice,
                    'stok' => rand(10, 60),
                    'deskripsi' => "{$res} Full Color Camera\n24/7 color imaging\nF1.0 super aperture\nWarm LED light\nSmart motion detection\nWeatherproof IP67",
                    'gambar' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    /**
     * Create IP Camera products
     */
    private function createIPCameraProducts($brandKey, $brandName)
    {
        $resolutions = ['2MP', '4MP', '5MP', '8MP'];
        $position = 1;
        
        foreach ($resolutions as $res) {
            for ($i = 0; $i < 4; $i++) {
                $megapixel = (int)filter_var($res, FILTER_SANITIZE_NUMBER_INT);
                $basePrice = 2500000 + ($megapixel * 400000) + ($i * 180000);
                
                DB::table('static_products')->insert([
                    'brand' => $brandKey,
                    'tab_category' => 'ipcamera',
                    'position' => $position++,
                    'sku' => strtoupper($brandKey) . '-IPC-' . $res . '-' . ($i + 1),
                    'kategori' => 'IP Camera',
                    'nama_produk' => "IP Camera {$res} AI Powered - Series " . ($i + 1),
                    'harga_modal' => $basePrice * 0.7,
                    'harga_jual' => $basePrice,
                    'stok' => rand(8, 45),
                    'deskripsi' => "{$res} IP Camera\nAI Deep Learning\nFace recognition\nLicense plate recognition\nIntrusion detection\nPoE powered\nWeatherproof",
                    'gambar' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    /**
     * Seed product categories
     */
    private function seedKategori()
    {
        echo "📂 Seeding categories...\n";
        
        $categories = [
            ['nama_kategori' => 'DVR', 'slug' => 'dvr', 'icon' => 'bi-camera-video', 'deskripsi' => 'Digital Video Recorder'],
            ['nama_kategori' => 'NVR', 'slug' => 'nvr', 'icon' => 'bi-hdd-network', 'deskripsi' => 'Network Video Recorder'],
            ['nama_kategori' => 'IP Camera', 'slug' => 'ip-camera', 'icon' => 'bi-camera', 'deskripsi' => 'IP Network Camera'],
            ['nama_kategori' => 'CCTV Camera', 'slug' => 'cctv-camera', 'icon' => 'bi-webcam', 'deskripsi' => 'Analog CCTV Camera'],
            ['nama_kategori' => 'WiFi Camera', 'slug' => 'wifi-camera', 'icon' => 'bi-wifi', 'deskripsi' => 'Wireless WiFi Camera'],
            ['nama_kategori' => 'Access Control', 'slug' => 'access-control', 'icon' => 'bi-door-closed', 'deskripsi' => 'Access Control System'],
        ];
        
        foreach ($categories as $category) {
            DB::table('kategori')->insert([
                'nama_kategori' => $category['nama_kategori'],
                'slug' => $category['slug'],
                'deskripsi' => $category['deskripsi'],
                'icon' => $category['icon'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        echo "   ✓ Created " . count($categories) . " categories\n";
    }
}