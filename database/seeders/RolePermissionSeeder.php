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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_inventory', 'manage_inventory',
            'view_sales_orders', 'manage_sales_orders',
            'view_purchase_orders', 'manage_purchase_orders',
            'view_quotation', 'manage_quotation',
            'view_bookkeeping', 'manage_bookkeeping',
            'view_finance', 'manage_finance',
            'view_static_products', 'create_static_products', 'edit_static_products', 'delete_static_products',
            'view_ruijie', 'manage_ruijie',
            'view_wifi_cameras', 'manage_wifi_cameras',
            'view_access_control', 'manage_access_control',
            'view_users', 'manage_users', 'manage_roles',
            'view_kalkulator', // ✅ Tambahan baru
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $roleMatrix = [
            'superadmin' => $permissions,

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
                'view_kalkulator', // ✅ Admin bisa akses kalkulator
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