<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // 1. Cek login
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $user = Auth::user();

        // 2. Cek is_active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['login' => 'Akun kamu tidak aktif.']);
        }

        // 3. Superadmin bypass: Bebas akses semua tanpa batas
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // 4. Cek permission manual
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                if (!$user->can($permission)) {
                    abort(403, 'Kamu tidak punya akses ke halaman ini.');
                }
            }
            return $next($request);
        }

        // 5. MAGIC OTOMATIS: Baca nama route yang sedang diakses
        $routeName = $request->route()->getName();

        // Abaikan route umum yang aman diakses semua admin
        $ignoredRoutes = ['admin.dashboard', 'admin.login', 'admin.logout'];
        if (!$routeName || in_array($routeName, $ignoredRoutes)) {
            return $next($request);
        }

        // Pecah nama route
        $parts = explode('.', $routeName);

        if (count($parts) >= 3 && $parts[0] === 'admin') {
            $module = str_replace('-', '_', $parts[1]); 
            $action = end($parts); 

            // Kamus rute singkatan
            $moduleMap = [
                'po' => 'purchase_orders',
                // sales_orders dibiarkan karena namanya sudah sama dengan di DB
            ];
            
            if (array_key_exists($module, $moduleMap)) {
                $module = $moduleMap[$module];
            }

            // Map action Laravel ke format permission di UI
            $actionMap = [
                'index'         => 'view',
                'show'          => 'view',
                'create'        => 'create',
                'store'         => 'create',
                'edit'          => 'edit',
                'update'        => 'edit',
                'destroy'       => 'delete',
                'bulk-delete'   => 'delete',
                'bulk-status'   => 'edit',
                'toggle-active' => 'edit',
                
                // Khusus Sales Order & Tambahan
                'cancel'        => 'delete', // Aksi "Batalkan SO" pakai izin delete
                'approve'       => 'edit',
                'approve-form'  => 'edit',
                'deliver'       => 'edit',
                'pdf'           => 'view',
            ];

            if (array_key_exists($action, $actionMap)) {
                $specificPermission = $actionMap[$action] . '_' . $module; 
                $managePermission   = 'manage_' . $module; 

                // INI KUNCINYA: Boleh masuk kalau punya izin spesifik ATAU izin Manage
                if (!$user->can($specificPermission) && !$user->can($managePermission)) {
                    abort(403, "Akses Ditolak! Role kamu tidak punya izin: {$specificPermission} atau {$managePermission}");
                }
            }
        }

        return $next($request);
    }
}