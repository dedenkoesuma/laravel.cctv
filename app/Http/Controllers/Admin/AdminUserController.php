<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar user
     */
    public function index()
    {
        // Menggunakan Eloquent Model dan mengambil relasi roles
        // supaya tidak terjadi error Undefined property: stdClass::$roles
        $users = User::with('roles')->get();
        
        // Pastikan nama view ('admin.users.index') sesuai dengan lokasi file blade kamu.
        // Jika file kamu ada di folder resources/views/users/index.blade.php, ubah jadi 'users.index'
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menyimpan user baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        // Buat user baru
        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => 1, // Set default aktif
        ]);

        // Assign role dari Spatie
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Mengupdate data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'     => 'required|exists:roles,name',
        ];

        // Jika password diisi, maka tambahkan rule validasi password
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $request->validate($rules);

        // Update data dasar
        $user->name     = $request->name;
        $user->username = $request->username;
        $user->email    = $request->email;

        // Jika user mengisi form password, berarti ganti password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update Role menggunakan syncRoles dari Spatie
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'Data user berhasil diupdate!');
    }

    /**
     * Menghapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteksi: jangan sampai superadmin menghapus akunnya sendiri secara tidak sengaja
        if (auth()->id() == $user->id) {
            return redirect()->back()->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri!']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus secara permanen!');
    }
}