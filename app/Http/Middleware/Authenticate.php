<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            
            if (! $request->expectsJson()) {
        return route('admin.login');
            }
            return route('login');
        }
    }
}