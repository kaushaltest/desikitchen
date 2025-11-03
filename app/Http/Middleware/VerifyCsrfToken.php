<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'api/*',          // all API routes
        'webhook/*',      // webhook routes
        'form/submit',    // your specific route
    ];
}