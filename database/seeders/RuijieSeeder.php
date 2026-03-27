<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RuijieCategory;
use App\Models\RuijieProduct;
use App\Models\RuijiePageSettings;
use Illuminate\Support\Str;

class RuijieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or Update Page Settings
        RuijiePageSettings::updateOrCreate(
            ['id' => 1], // Find by ID
            [
                'title' => 'Ruijie Networks',
                'subtitle' => 'Solusi networking enterprise-grade dengan teknologi terkini untuk infrastruktur jaringan yang handal, scalable, dan mudah dikelola',
                'products_count' => 500,
                'clients_count' => 10000,
                'satisfaction_rate' => 99,
                'is_active' => true
            ]
        );

        // Create Categories
        $categories = [
            [
                'name' => 'Wireless Access Point',
                'slug' => 'wireless-access-point',
                'description' => 'Access Point WiFi untuk indoor dan outdoor',
                'icon' => 'fas fa-wifi',
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Switch',
                'slug' => 'switch',
                'description' => 'Network Switch untuk berbagai kebutuhan',
                'icon' => 'fas fa-network-wired',
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Router',
                'slug' => 'router',
                'description' => 'Router enterprise untuk koneksi yang stabil',
                'icon' => 'fas fa-route',
                'order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Controller',
                'slug' => 'controller',
                'description' => 'Wireless Controller untuk manajemen terpusat',
                'icon' => 'fas fa-server',
                'order' => 4,
                'is_active' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            RuijieCategory::updateOrCreate(
                ['slug' => $categoryData['slug']], // Find by slug
                $categoryData
            );
        }

        // Create Sample Products
        $products = [
            [
                'category_id' => 1, // Wireless AP
                'name' => 'Ruijie RG-AP740-I',
                'slug' => 'ruijie-rg-ap740-i',
                'description' => 'WiFi 6 Indoor Access Point dengan kecepatan hingga 2.97 Gbps. Ideal untuk kantor, hotel, dan kampus.',
                'specifications' => json_encode([
                    'WiFi 6 (802.11ax)',
                    'Dual-band 2.4GHz/5GHz',
                    'Max speed 2.97 Gbps',
                    'PoE powered',
                    'Cloud managed'
                ]),
                'features' => json_encode([
                    'MU-MIMO technology',
                    'OFDMA support',
                    'Seamless roaming',
                    'Easy deployment'
                ]),
                'price' => 2500000,
                'order' => 1,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'category_id' => 2, // Switch
                'name' => 'Ruijie RG-S2910-24GT4XS-E',
                'slug' => 'ruijie-rg-s2910-24gt4xs-e',
                'description' => 'L3 Gigabit Switch 24 port dengan 4 SFP+ uplink. Cocok untuk core dan distribution layer.',
                'specifications' => json_encode([
                    '24x GE ports',
                    '4x 10G SFP+ ports',
                    'L3 routing',
                    'Stackable',
                    'Fanless design'
                ]),
                'features' => json_encode([
                    'VLAN support',
                    'QoS advanced',
                    'ACL security',
                    'SNMP monitoring'
                ]),
                'price' => 15000000,
                'order' => 2,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'category_id' => 3, // Router
                'name' => 'Ruijie RG-EG310GH-E',
                'slug' => 'ruijie-rg-eg310gh-e',
                'description' => 'Enterprise Gateway Router dengan throughput tinggi. Mendukung VPN dan firewall advanced.',
                'specifications' => json_encode([
                    'Throughput 3 Gbps',
                    '10x GE ports',
                    '2x SFP ports',
                    'Dual WAN',
                    'Hardware acceleration'
                ]),
                'features' => json_encode([
                    'IPSec VPN',
                    'Load balancing',
                    'Bandwidth control',
                    'Deep packet inspection'
                ]),
                'price' => 8500000,
                'order' => 3,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'category_id' => 4, // Controller
                'name' => 'Ruijie RG-WS6008',
                'slug' => 'ruijie-rg-ws6008',
                'description' => 'Wireless Controller untuk mengelola hingga 512 AP. Mudah dikonfigurasi dan monitoring real-time.',
                'specifications' => json_encode([
                    'Manage up to 512 APs',
                    'Support 8000 clients',
                    'Web-based GUI',
                    'CLI access',
                    'Redundancy support'
                ]),
                'features' => json_encode([
                    'Centralized management',
                    'Auto RF optimization',
                    'Rogue AP detection',
                    'Guest portal'
                ]),
                'price' => 12000000,
                'order' => 4,
                'is_featured' => false,
                'is_active' => true
            ]
        ];

        foreach ($products as $productData) {
            RuijieProduct::updateOrCreate(
                ['slug' => $productData['slug']], // Find by slug
                $productData
            );
        }

        $this->command->info('✅ Ruijie categories, products, and settings created successfully!');
    }
}