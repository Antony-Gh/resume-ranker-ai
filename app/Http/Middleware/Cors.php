<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request and set CORS headers.
     *
     * This middleware adds the necessary CORS headers to the HTTP response
     * to allow cross-origin requests from specified origins. It is essential
     * for enabling API access from different domains while maintaining security.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request instance.
     * @param  \Closure  $next  The next middleware or request handler.
     * @return \Illuminate\Http\Response  The response with CORS headers.
     */
    public function handle(Request $request, Closure $next)
    {
        // Pass the request to the next middleware or request handler
        $response = $next($request);

        // Set CORS headers to allow cross-origin requests
        $response->header('Access-Control-Allow-Origin', 'https://seamagics.com') // Allow requests only from this origin

            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS') // Specify allowed HTTP methods

            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN, API-KEY'); // Specify allowed headers

        // Optional: Handle preflight requests for OPTIONS method
        if ($request->isMethod('OPTIONS')) {
            return response()->json([], 200); // Respond with a 200 OK for preflight requests
        }

        return $response; // Return the response with the added CORS headers
    }
}
