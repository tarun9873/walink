<?php
// app/Http/Middleware/CheckLinkLimit
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLinkLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->canCreateMoreLinks()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You have reached your link limit. Please upgrade your plan.',
                    'redirect' => route('pricing')
                ], 403);
            }
            
            return redirect()->route('pricing')
                ->with('error', 'You have reached your link limit. Please upgrade your plan.');
        }

        return $next($request);
    }
}