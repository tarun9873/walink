<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user is admin
        $user = Auth::user();
        
        // Define admin emails
        $adminEmails = [
            'ak3400988@gmail.com',
            'admin@example.com',
            // Add more admin emails as needed
        ];

        if (!in_array($user->email, $adminEmails)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access admin area.');
        }

        return $next($request);
    }
}