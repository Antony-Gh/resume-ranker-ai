<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfEmailNotVerified
{
    public function handle($request, Closure $next)
    {
        Log::warning("RedirectIfEmailNotVerified");
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            session(['action' => 'verify']);
            return redirect()->route('realHome')->with([
                'action' => 'verify',
            ]);
        }

        return $next($request);
    }
} 