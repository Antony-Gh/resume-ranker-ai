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
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin'); // Control the referrer information sent with requests
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');

        // Skip CSP for specific paths like admin routes that might need more flexibility
        if (!$this->shouldSkipCsp($request)) {
            $response->headers->set('Content-Security-Policy', $this->getContentSecurityPolicy());
        }

        return $response;
    }

    /**
     * Get the Content Security Policy directives.
     *
     * @return string
     */
    protected function getContentSecurityPolicy(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' https://cdn.jsdelivr.net https://ajax.googleapis.com https://www.google-analytics.com 'unsafe-inline' 'unsafe-eval'",
            "style-src 'self' https://cdn.jsdelivr.net https://fonts.googleapis.com https://use.fontawesome.com https://fonts.bunny.net 'unsafe-inline'",
            "img-src 'self' data: https://www.google-analytics.com https://*.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://use.fontawesome.com https://fonts.bunny.net",
            "connect-src 'self' https://www.google-analytics.com",
            "media-src 'self'",
            "frame-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "block-all-mixed-content",
            "upgrade-insecure-requests"
        ]);
    }

    /**
     * Determine if CSP should be skipped for the current request.
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldSkipCsp(Request $request): bool
    {
        $skipPaths = [
            'admin/editor/*',
            'tinymce/*',
            'filemanager/*',
        ];

        $path = $request->path();

        foreach ($skipPaths as $skipPath) {
            if (fnmatch($skipPath, $path)) {
                return true;
            }
        }

        return false;
    }
}
