<?php

namespace App\Http\Middleware;

use Closure; // Import the Closure class for type hinting
use Illuminate\Http\Request; // Import the Request class for type hinting
use Symfony\Component\HttpFoundation\Response; // Import the Response class for type hinting

class SecureHeadersMiddleware
{
    /**
     * Handle an incoming request and set secure headers.
     *
     * This middleware adds various security headers to the HTTP response
     * to enhance the security of the application and protect against common
     * web vulnerabilities.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request instance.
     * @param  \Closure  $next  The next middleware or request handler.
     * @return \Symfony\Component\HttpFoundation\Response  The response with secure headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pass the request to the next middleware or request handler
        $response = $next($request);

        // Set secure headers to enhance security
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains'); // Enforce HTTPS for 1 year (31536000 seconds)
        $response->headers->set('X-Content-Type-Options', 'nosniff'); // Prevent MIME type sniffing

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); // Prevent click jacking by allowing framing only from the same origin

        $response->headers->set('X-XSS-Protection', '1; mode=block'); // Enable XSS filtering and block the response if an attack is detected

        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin'); // Control the referrer information sent with requests

        // Optional: Implement Content Security Policy (CSP) for additional security
        // $response->headers->set('Content-Security-Policy', "default-src 'self';"); // Example CSP header

        return $response; // Return the response with the added headers
    }
}
