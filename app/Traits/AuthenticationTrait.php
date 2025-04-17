<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

trait AuthenticationTrait
{
    /**
     * Handle rate limiting for authentication attempts
     *
     * @param Request $request
     * @param int $maxAttempts
     * @return \Illuminate\Http\JsonResponse|null
     */
    protected function handleRateLimiting(Request $request, int $maxAttempts = 5): ?\Illuminate\Http\JsonResponse
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), $maxAttempts)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            return response()->json([
                'success' => false,
                'errors' => ['email' => "Too many attempts. Please try again in {$seconds} seconds."]
            ], 429);
        }

        RateLimiter::hit($this->throttleKey($request));
        return null;
    }

    /**
     * Generate throttle key for rate limiting
     *
     * @param Request $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    /**
     * Log authentication attempt
     *
     * @param string $action
     * @param array $data
     * @return void
     */
    protected function logAuthAttempt(string $action, array $data): void
    {
        $logData = array_merge([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ], $data);

        Log::info("Authentication {$action}", $logData);
    }

    /**
     * Log authentication error
     *
     * @param string $action
     * @param \Throwable $th
     * @param array $data
     * @return void
     */
    protected function logAuthError(string $action, \Throwable $th, array $data = []): void
    {
        $logData = array_merge([
            'error' => $th->getMessage(),
            'trace' => $th->getTraceAsString(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString()
        ], $data);

        Log::error("Authentication {$action} error", $logData);
    }

    /**
     * Handle session regeneration
     *
     * @param Request $request
     * @return string
     */
    protected function handleSessionRegeneration(Request $request): string
    {
        $request->session()->regenerate();
        $request->session()->regenerateToken();
        $newCsrfToken = $request->session()->token();
        return $newCsrfToken;
    }

    /**
     * Handle session invalidation
     *
     * @param Request $request
     * @return string
     */
    protected function handleSessionInvalidation(Request $request): string
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $newCsrfToken = $request->session()->token();
        return $newCsrfToken;
    }
} 