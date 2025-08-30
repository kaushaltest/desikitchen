<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // ✅ Important

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->role === 'super-admin' || Auth::user()->role === 'admin')) {
            return $next($request);
        }

        abort(403, 'Unauthorized.');
    }
}
