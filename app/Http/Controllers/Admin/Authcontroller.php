<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login'); // sesuai view yang sudah ada
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string|min:6',
            ], [
                'username.required' => 'Username atau email harus diisi',
                'password.required' => 'Password harus diisi',
                'password.min'      => 'Password minimal 6 karakter',
            ]);

            // Rate limiting manual
            if ($this->hasTooManyLoginAttempts($request)) {
                return back()->withInput($request->only('username'))
                    ->with('error', 'Terlalu banyak percobaan. Coba lagi dalam 1 menit.');
            }

            // Support login pakai username ATAU email
            $field       = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $credentials = [
                $field     => $request->username,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $user = Auth::user();

                // Cek is_active
                if (!$user->is_active) {
                    Auth::logout();
                    return back()->withInput($request->only('username'))
                        ->with('error', 'Akun kamu tidak aktif. Hubungi superadmin.');
                }

                $request->session()->regenerate();
                $this->clearLoginAttempts($request);

                Log::info('User logged in', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'ip'      => $request->ip(),
                ]);

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang, ' . $user->name . '!');
            }

            $this->incrementLoginAttempts($request);

            return back()->withInput($request->only('username'))
                ->with('error', 'Username/email atau password salah.');

        } catch (Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return back()->withInput($request->only('username'))
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function logout(Request $request)
    {
        try {
            $userName = Auth::user()->name ?? 'User';

            Log::info('User logged out', [
                'user_id' => Auth::id(),
                'email'   => Auth::user()->email ?? 'unknown',
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('success', 'Sampai jumpa, ' . $userName . '!');

        } catch (Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            return redirect()->route('admin.login');
        }
    }

    // ==========================================
    // Rate Limiting Helpers
    // ==========================================

    protected function hasTooManyLoginAttempts(Request $request): bool
    {
        return cache()->get($this->throttleKey($request), 0) >= 5;
    }

    protected function incrementLoginAttempts(Request $request): void
    {
        $key = $this->throttleKey($request);
        cache()->put($key, cache()->get($key, 0) + 1, now()->addMinutes(1));
    }

    protected function clearLoginAttempts(Request $request): void
    {
        cache()->forget($this->throttleKey($request));
    }

    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('username')) . '|' . $request->ip();
    }
}