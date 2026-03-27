<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika sudah login sebagai admin, redirect ke dashboard
                if (auth()->user()->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                // Jika bukan admin, logout
                Auth::logout();
            }
        }

        return $next($request);
    }
}