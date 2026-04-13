<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // 1. Menampilkan daftar Role
    public function index()
    {
        // Ambil semua role beserta jumlah permission-nya
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    // 2. Menampilkan halaman Edit Permission (Centang-centang)
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        
        // Grouping permission berdasarkan kata setelah underscore (misal: view_inventory -> inventory)
        $permissions = Permission::all()->groupBy(function($perm) {
            $parts = explode('_', $perm->name);
            return count($parts) > 1 ? $parts[1] : 'Lainnya'; 
        });

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    // 3. Menyimpan hasil centang permission
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        // Sinkronisasi permission (jika tidak ada yang dicentang, kirim array kosong)
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Konfigurasi hak akses berhasil diperbarui!');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        Role::create(['name' => strtolower($request->name)]);

        return back()->with('success', 'Role baru berhasil ditambahkan!');
    }
}