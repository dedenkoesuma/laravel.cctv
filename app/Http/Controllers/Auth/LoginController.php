<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login admin
     * Middleware 'guest' di route sudah handle redirect otomatis
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        // Validasi input dengan username (bukan email)
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.'
        ]);

        // Attempt login dengan username dan password
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();
            
            // Cek apakah user adalah admin
            if (auth()->user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang, ' . auth()->user()->name . '!');
            }
            
            // Jika bukan admin, logout dan tolak akses
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'username' => 'Akses ditolak. Hanya admin yang bisa login.'
            ])->withInput($request->only('username'));
        }

        // Login gagal - username atau password salah
        return back()->withErrors([
            'username' => 'Username atau password salah.'
        ])->withInput($request->only('username'));
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        // Simpan nama user sebelum logout
        $name = auth()->user()->name ?? 'Admin';
        
        // Logout
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Redirect ke login dengan pesan sukses
        return redirect()->route('admin.login')
            ->with('success', 'Anda telah logout. Sampai jumpa, ' . $name . '!');
    }
}