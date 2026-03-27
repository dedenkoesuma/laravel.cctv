<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{
    /**
     * Show login form
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        try {
            // Jika sudah login, redirect ke dashboard
            if (Auth::check()) {
                return redirect()->route('admin.dashboard');
            }

            return view('admin.auth.login');

        } catch (Exception $e) {
            Log::error('Show Login Error: ' . $e->getMessage());
            
            return view('admin.auth.login')->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Process login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 6 karakter'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->only('email'));
            }

            // Ambil credentials
            $credentials = $request->only('email', 'password');
            $remember = $request->has('remember');

            // Attempt login dengan rate limiting
            if ($this->hasTooManyLoginAttempts($request)) {
                return $this->sendLockoutResponse($request);
            }

            // Coba login
            if (Auth::attempt($credentials, $remember)) {
                // Login berhasil
                $request->session()->regenerate();
                $this->clearLoginAttempts($request);

                // Log aktivitas
                Log::info('User logged in', [
                    'user_id' => Auth::id(),
                    'email' => Auth::user()->email,
                    'ip' => $request->ip()
                ]);

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
            }

            // Login gagal
            $this->incrementLoginAttempts($request);

            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');

        } catch (Exception $e) {
            Log::error('Login Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Terjadi kesalahan saat login. Silakan coba lagi.');
        }
    }

    /**
     * Logout user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        try {
            $userName = Auth::user()->name ?? 'User';
            
            // Log aktivitas
            Log::info('User logged out', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email ?? 'unknown'
            ]);

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('success', 'Anda telah logout. Sampai jumpa, ' . $userName . '!');

        } catch (Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());

            return redirect()->route('admin.login')
                ->with('error', 'Terjadi kesalahan saat logout.');
        }
    }

    /**
     * Check if user has too many login attempts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        $maxAttempts = 5;
        $decayMinutes = 1;

        $key = $this->throttleKey($request);
        $attempts = cache()->get($key, 0);

        return $attempts >= $maxAttempts;
    }

    /**
     * Increment login attempts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
        $attempts = cache()->get($key, 0);
        
        cache()->put($key, $attempts + 1, now()->addMinutes(1));
    }

    /**
     * Clear login attempts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
        cache()->forget($key);
    }

    /**
     * Get throttle key
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Send lockout response
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = 60;

        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.');
    }

    /**
     * Show forgot password form
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPassword()
    {
        try {
            return view('admin.auth.forgot-password');
        } catch (Exception $e) {
            Log::error('Show Forgot Password Error: ' . $e->getMessage());
            return redirect()->route('admin.login');
        }
    }

    /**
     * Send password reset link
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email'
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.exists' => 'Email tidak ditemukan'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // TODO: Implement password reset logic
            // Password::sendResetLink($request->only('email'));

            return redirect()->back()
                ->with('success', 'Link reset password telah dikirim ke email Anda.');

        } catch (Exception $e) {
            Log::error('Send Reset Link Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengirim link reset password.');
        }
    }
}