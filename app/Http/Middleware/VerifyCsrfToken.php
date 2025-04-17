<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
   /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'sanctum/csrf-cookie', // Exclude the Sanctum CSRF route
        'api/*', // Exclude all API routes
        'your-route', // Add any other routes you need to exclude
    ];
}
