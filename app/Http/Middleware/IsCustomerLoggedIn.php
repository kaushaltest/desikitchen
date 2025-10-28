<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // ✅ Important

class IsCustomerLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('user_id')) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Please log in to continue.');
    }
}
