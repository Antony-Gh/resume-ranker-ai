<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function handle($request, \Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 'super-admin') {
            return $next($request);
        }

        return redirect()->route('admins.login'); // Redirect to the login page
    }
}