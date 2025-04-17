<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, \Closure $next)
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
     * @return array
     */
    protected function sanitize(array $input)
    {
        // Remove potentially harmful characters (e.g., <, >, &, etc.)
        return array_map(function ($item) {
            if (is_string($item)) {
                return filter_var($item, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $item;
        }, $input);
    }
}