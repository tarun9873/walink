<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and email is admin email
        if (auth()->check() && auth()->user()->email === 'ak3400988@gmail.com') {
            return $next($request);
        }

        return redirect('/')->with('error', 'You do not have admin access.');
    }
}