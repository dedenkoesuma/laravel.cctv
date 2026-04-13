<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Daftar Modul dan Aksi yang ingin dibuat
        // Format: aksi_namamodul (agar grouping di controller jalan)
        $modules = [
            'inventory', 
            'ruijie', 
            'wifi_cameras', 
            'access_control', 
            'static_products', 
            'bookkeeping', 
            'sales_documents',
            'users'
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . '_' . $module,
                    'guard_name' => 'web'
                ]);
            }
        }
    }
}