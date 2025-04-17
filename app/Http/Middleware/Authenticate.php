<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Log::warning("Authenticate");
        
        if (!$request->expectsJson()) {
            // Log::warning("Authenticate2");

            // Correct: Return a string (not a redirect response)
            session(['action' => 'signin']);
            return route('realHome');
        }

        return null; // Explicitly return null if JSON is expected
    }
}
