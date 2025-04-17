<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * List of sensitive parameters that should be redacted
     */
    protected array $sensitiveParams = [
        'password',
        'password_confirmation',
        'credit_card',
        'cvv',
        'token',
        'api_key',
        'secret'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip logging for file uploads
        if ($this->shouldSkipLogging($request)) {
            return $next($request);
        }

        // Log the incoming request
        $this->logRequest($request);

        // Process the request
        $response = $next($request);

        // Log the outgoing response
        $this->logResponse($request, $response);

        return $response;
    }

    /**
     * Determine if the request should skip logging
     */
    protected function shouldSkipLogging(Request $request): bool
    {
        return $request->isMethod('post') && $request->files->count() > 0;
    }

    /**
     * Log the incoming request data
     */
    protected function logRequest(Request $request): void
    {
        try {
            $logData = [
                'type' => 'request',
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'params' => $this->filterSensitiveData($request->all()),
                'user_id' => optional($request->user())->id ?? 'guest',
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                // 'session_id' => $request->session()->getId(),
            ];

            Log::channel('requests')->info('Incoming Request', $logData);
        } catch (\Exception $e) {
            Log::channel('requests')->error('Failed to log request', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log the outgoing response data
     */
    protected function logResponse(Request $request, $response): void
    {
        try {
            $content = $response->getContent();
            $contentType = $response->headers->get('Content-Type');

            $logData = [
                'type' => 'response',
                'status' => $response->status(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'content_type' => $contentType,
                'content_length' => strlen($content),
                'user_id' => optional($request->user())->id ?? 'guest',
                'ip' => $request->ip(),
            ];

            // Only log response body for non-HTML responses and small payloads
            if (!str_contains($contentType, 'text/html') && strlen($content) < 1000) {
                $logData['content_sample'] = substr($content, 0, 200);
            }

            Log::channel('requests')->info('Outgoing Response', $logData);
        } catch (\Exception $e) {
            Log::channel('requests')->error('Failed to log response', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Filter out sensitive data from parameters
     */
    protected function filterSensitiveData(array $data): array
    {
        foreach ($this->sensitiveParams as $param) {
            if (array_key_exists($param, $data)) {
                $data[$param] = '***REDACTED***';
            }
        }

        return $data;
    }
}