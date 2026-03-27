<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateProductBrandSeeder extends Seeder
{
    public function run(): void
    {
        // Array brand dan keywords
        $brandMappings = [
            'Dahua' => ['Dahua', 'DAHUA', 'dahua'],
            'HIKVISION' => ['HIKVISION', 'Hikvision', 'hikvision', 'HikVision'],
            'Hilook' => ['Hilook', 'HILOOK', 'hilook', 'HiLook'],
            'UNV' => ['UNV', 'unv', 'Uniview', 'UNIVIEW'],
            'Hiview' => ['Hiview', 'HIVIEW', 'hiview', 'HiView'],
            'Ruijie' => ['Ruijie', 'RUIJIE', 'ruijie'],
            'Foreages' => ['Foreages', 'FOREAGES', 'foreages'],
        ];

        foreach ($brandMappings as $brand => $keywords) {
            foreach ($keywords as $keyword) {
                $updated = DB::table('produk')
                    ->where('nama', 'LIKE', "%{$keyword}%")
                    ->whereNull('brand') // Hanya update yang belum punya brand
                    ->update(['brand' => $brand]);
                
                if ($updated > 0) {
                    $this->command->info("✓ Updated {$updated} products with keyword '{$keyword}' to brand '{$brand}'");
                }
            }
        }

    
        // Tampilkan summary
        $summary = DB::table('produk')
            ->select('brand', DB::raw('COUNT(*) as total'))
            ->groupBy('brand')
            ->get();
        
        $this->command->info("\n📊 Brand Summary:");
        foreach ($summary as $item) {
            $brandName = $item->brand ?? 'No Brand';
            $this->command->info("   {$brandName}: {$item->total} products");
        }
    }
}