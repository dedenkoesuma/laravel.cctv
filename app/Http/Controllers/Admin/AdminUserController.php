<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = DB::table('admins')->orderBy('id', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:admins',
        'email' => 'required|string|email|max:255|unique:admins',
        'password' => 'required|string|min:6',
        'role' => 'required|exists:roles,name' 
    ]);

    DB::table('admins')->insert([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return back()->with('success', 'User berhasil ditambahkan!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:admins,username,'.$id,
        'email' => 'required|string|email|max:255|unique:admins,email,'.$id,
        'role' => 'required|exists:roles,name' // Samakan dengan store
    ]);

    $data = [
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'role' => $request->role,
        'updated_at' => now()
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    DB::table('admins')->where('id', $id)->update($data);

    return back()->with('success', 'Data user berhasil diperbarui!');
}

    public function destroy($id)
    {
        // Mencegah user menghapus dirinya sendiri (opsional tapi disarankan)
        if (session('admin_id') == $id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }

        DB::table('admins')->where('id', $id)->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}