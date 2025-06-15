<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Sanitize all input data
        $input = $request->all();
        $sanitizedInput = $this->sanitize($input);

        // Replace the request input with the sanitized version
        $request->merge($sanitizedInput);

        return $next($request);
    }

    /**
     * Sanitize the input data.
     *
     * @param array $input
     * @return array
     */
    protected function sanitize(array $input): array
    {
        $sanitized = [];

        foreach ($input as $key => $value) {
            // Recursively sanitize nested arrays
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value);
            }
            // Handle string values
            elseif (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value);
            }
            // Keep other types as is
            else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize a string value.
     *
     * @param string $value
     * @return string
     */
    protected function sanitizeString(string $value): string
    {
        // Remove null bytes
        $value = str_replace(chr(0), '', $value);

        // Convert special characters to HTML entities
        $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);

        // Remove potentially harmful HTML tags if not in a safe context
        if ($this->shouldStripTags()) {
            $value = strip_tags($value);
        }

        return $value;
    }

    /**
     * Determine if tags should be stripped based on the current route.
     *
     * @return bool
     */
    protected function shouldStripTags(): bool
    {
        // Allow HTML in specific routes or contexts where it's needed
        $allowHtmlRoutes = [
            'admin/content/*',
            'editor/*',
        ];

        $currentRoute = request()->path();

        foreach ($allowHtmlRoutes as $route) {
            if (fnmatch($route, $currentRoute)) {
                return false;
            }
        }

        return true;
    }
}
