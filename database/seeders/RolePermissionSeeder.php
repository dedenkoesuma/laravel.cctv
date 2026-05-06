<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // === DAFTAR SEMUA PERMISSION ===
        $permissions = [
            // Inventory / Gudang
            'view_inventory', 'manage_inventory',

            // Sales & Purchase
            'view_sales_orders', 'manage_sales_orders',
            'view_purchase_orders', 'manage_purchase_orders',

            // Quotation
            'view_quotation', 'manage_quotation',

            // Keuangan Boss (bookkeeping)
            'view_bookkeeping', 'manage_bookkeeping',

            // Finance Staff
            'view_finance', 'manage_finance',

            // Static Products
            'view_static_products', 'create_static_products',
            'edit_static_products', 'delete_static_products',

            // Ruijie
            'view_ruijie', 'manage_ruijie',

            // WiFi Cameras
            'view_wifi_cameras', 'manage_wifi_cameras',

            // Access Control
            'view_access_control', 'manage_access_control',

            // System
            'view_users', 'manage_users', 'manage_roles',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // === DEFINISI ROLE & PERMISSION-NYA ===
        $roleMatrix = [
            'superadmin' => $permissions, // Semua permission

            'admin' => [
                'view_inventory', 'manage_inventory',
                'view_sales_orders', 'manage_sales_orders',
                'view_purchase_orders', 'manage_purchase_orders',
                'view_quotation', 'manage_quotation',
                'view_bookkeeping', 'manage_bookkeeping',
                'view_finance', 'manage_finance',
                'view_static_products', 'create_static_products', 'edit_static_products',
                'view_ruijie', 'manage_ruijie',
                'view_wifi_cameras', 'manage_wifi_cameras',
                'view_access_control', 'manage_access_control',
            ],

            'finance' => [
                'view_sales_orders',
                'view_purchase_orders',
                'view_quotation',
                'view_finance', 'manage_finance',
            ],

            'teknisi' => [
                'view_static_products', 'edit_static_products',
                'view_ruijie',
                'view_wifi_cameras',
                'view_access_control',
            ],

            'gudang_staff' => [
                'view_inventory', 'manage_inventory',
                'view_sales_orders', 'manage_sales_orders',
                'view_purchase_orders', 'manage_purchase_orders',
            ],
        ];

        foreach ($roleMatrix as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePerms);
        }

        // === BUAT DEFAULT USER SUPERADMIN ===
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@techstore.com'],
            [
                'name'      => 'Super Admin',
                'username'  => 'superadmin',
                'password'  => Hash::make('SuperAdmin123!'),
                'is_active' => true,
            ]
        );
        $superadmin->syncRoles('superadmin');

        $this->command->info('✅ Roles, Permissions & Default User berhasil dibuat!');
        $this->command->table(
            ['Role', 'Jumlah Permission'],
            collect($roleMatrix)->map(fn($p, $r) => [$r, count($p)])->values()->toArray()
        );
    }
}