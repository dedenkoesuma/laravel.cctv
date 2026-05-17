<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Grouping: ambil kata PERTAMA sebelum underscore sebagai aksi (view/manage/create/dll)
        // dan kata KEDUA sebagai modul (inventory, finance, dll)
        $permissions = Permission::all()->groupBy(function ($perm) {
            $parts = explode('_', $perm->name);
            return count($parts) > 1 ? implode('_', array_slice($parts, 1)) : 'lainnya';
        });

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Hak akses role "' . $role->name . '" berhasil diperbarui!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:50'
        ]);

        Role::create(['name' => strtolower($request->name), 'guard_name' => 'web']);

        return back()->with('success', 'Role "' . $request->name . '" berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Proteksi: jangan hapus superadmin
        if ($role->name === 'superadmin') {
            return back()->withErrors(['error' => 'Role superadmin tidak bisa dihapus!']);
        }

        // Cek apakah ada user yang masih pakai role ini
        if ($role->users()->count() > 0) {
            return back()->withErrors(['error' => 'Role ini masih digunakan oleh ' . $role->users()->count() . ' user. Pindahkan role mereka terlebih dahulu.']);
        }

        $role->delete();

        return back()->with('success', 'Role "' . $role->name . '" berhasil dihapus!');
    }
}