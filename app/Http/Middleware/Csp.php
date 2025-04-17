<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Csp
{
    /**
     * Handle an incoming request and apply Content Security Policy (CSP) headers.
     *
     * This middleware sets the Content-Security-Policy (CSP) header to help prevent
     * cross-site scripting (XSS) and other code injection attacks. The policy restricts
     * the sources from which content can be loaded on the page.
     *
     * CSP Directives:
     * - default-src: Restricts all types of content (scripts, images, styles, etc.)
     *   to only the origin (your own domain).
     * - script-src: Allows scripts to load from your domain ('self') and specified
     *   external domains (e.g., https://seamagics.com).
     * - style-src: Specifies allowed sources for stylesheets, including inline styles
     *   if needed.
     * - img-src: Restricts image sources, allowing images to load from your domain
     *   ('self'), data URIs, and specified external domains (e.g., https://seamagics.com).
     * - font-src: Specifies allowed sources for web fonts.
     * - object-src: Disallows any <object> elements, which is a good practice to mitigate
     *   security risks associated with them.
     * - frame-src: Specifies valid sources for nested browsing contexts (iframes).
     * - connect-src: Restricts the URLs which can be loaded using script interfaces.
     * - media-src: Specifies valid sources for loading media such as audio and video.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @param \Closure $next The next middleware or request handler.
     * @return mixed The response with CSP headers.
     */
    public function handle(Request $request, Closure $next)
    {
        // Pass the request to the next middleware or request handler
        $response = $next($request);

        // Set the Content Security Policy header
        return $response->header('Content-Security-Policy', $this->getCspHeader());
    }

    /**
     * Get the Content Security Policy header value.
     *
     * This method constructs the CSP string that defines the allowed sources for
     * different types of content.
     *
     * @return string The constructed CSP header value.
     */
    protected function getCspHeader()
    {
        return implode('; ', [
            "default-src 'self'", // Restrict all content to the same origin
            "script-src 'self' https://seamagics.com https://www.seamagics.com", // Allow scripts from self and specified domains
            "style-src 'self' https://fonts.googleapis.com", // Allow styles from self and Google Fonts
            "font-src 'self' https://fonts.gstatic.com", // Allow fonts from self and Google Fonts
            "img-src 'self' data: https://seamagics.com https://www.seamagics.com", // Allow images from self, data URIs, and specified domains
            "object-src 'none'", // Disallow all <object> elements
            "frame-src 'self' https://trusted-iframe-source.com", // Allow iframes from self and a trusted source
            "connect-src 'self' https://api.seamagics.com", // Allow connections to self and specified API
            "media-src 'self' https://media.seamagics.com", // Allow media from self and specified media source
            "report-uri '/csp-report'", // Specify the endpoint for CSP violation reports
        ]);
    }
}
