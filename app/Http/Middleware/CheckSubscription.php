<?php
// app/Http/Middleware/CheckSubscription.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasActiveSubscription()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You need an active subscription to access this feature.',
                    'redirect' => route('pricing')
                ], 403);
            }
            
            return redirect()->route('pricing')
                ->with('error', 'You need an active subscription to access this feature.');
        }

        return $next($request);
    }
}