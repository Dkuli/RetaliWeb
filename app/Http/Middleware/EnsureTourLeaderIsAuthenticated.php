<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureTourLeaderIsAuthenticated
{
    public function handle($request, Closure $next)
    {
        // Gunakan guard tourleader
        if (!Auth::guard('tourleader')->check()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        return $next($request);
    }
}
