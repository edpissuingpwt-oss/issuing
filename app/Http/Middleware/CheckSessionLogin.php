<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSessionLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('is_logged_in')) {
            return redirect('/login')->withErrors([
                'login' => 'Silakan login terlebih dahulu.'
            ]);
        }

        return $next($request);
    }
}