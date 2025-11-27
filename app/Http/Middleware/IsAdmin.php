<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * Allow only authenticated users with is_admin = true.
     * Otherwise abort(403) or redirect.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->is_admin) {
            // Option A: abort with 403
            abort(403, 'Unauthorized.');

            // Option B: redirect to home with message (uncomment if you prefer)
            // return redirect('/')->with('error','Not authorized.');
        }

        return $next($request);
    }
}