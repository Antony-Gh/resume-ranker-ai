<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {


        // Alias for route-specific usage
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'log.requests' => \App\Http\Middleware\LogRequests::class,
            // 'api.response' => \App\Http\Middleware\ApiResponseMiddleware::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \App\Http\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \App\Http\Middleware\RedirectIfEmailNotVerified::class,
            // 'verified-' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);

        // Global middleware (runs on every request)
        $middleware->use([
            // Trust proxy settings for load balancers and reverse proxies
            \App\Http\Middleware\TrustProxies::class,

            // Secure Headers middleware to enhance security
            \App\Http\Middleware\SecureHeadersMiddleware::class,

            // CORS middleware to handle Cross-Origin Resource Sharing
            // \App\Http\Middleware\Cors::class,
            \Illuminate\Http\Middleware\HandleCors::class,

            // Prevent requests during maintenance mode
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

            // Content Security Policy (CSP) middleware to protect against XSS and other attacks
            // \App\Http\Middleware\Csp::class,

            // Validate maximum post size to prevent large requests
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

            // Trim extra whitespace from input data
            \App\Http\Middleware\TrimStrings::class,

            // Convert empty strings to null
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

            // Custom middleware to sanitize input data (optional)
            \App\Http\Middleware\SanitizeInput::class,

            // Add your logging middleware here for global logging
            \App\Http\Middleware\LogRequests::class,
        ]);

        // Web middleware group
        $middleware->group('web', [
             // Encrypt cookies
             \App\Http\Middleware\EncryptCookies::class,

             // Add queued cookies to the response
             \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
 
             // Start the session
             \Illuminate\Session\Middleware\StartSession::class,
 
             // Authenticate the session
             \Illuminate\Session\Middleware\AuthenticateSession::class,
 
             // Share errors from the session to the view
             \Illuminate\View\Middleware\ShareErrorsFromSession::class,
 
             // Verify CSRF token to protect against cross-site request forgery
             \App\Http\Middleware\VerifyCsrfToken::class,
 
             // Substitute route parameters
             \Illuminate\Routing\Middleware\SubstituteBindings::class,
 
             // Redirect authenticated users to a specific route (optional)
            //  \App\Http\Middleware\RedirectIfAuthenticated::class,

            // Optionally add here for web-only logging
            // \App\Http\Middleware\LogRequests::class,
        ]);

        // API middleware group
        $middleware->group('api', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // Optionally add here for API-only logging
            // \App\Http\Middleware\LogRequests::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'sanctum/csrf-cookie', // Exclude the Sanctum CSRF route
            'api/*', // Exclude all API routes
            'your-route', // Add any other routes you need to exclude
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
