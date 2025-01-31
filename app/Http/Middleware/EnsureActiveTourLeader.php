<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureActiveTourLeader
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->is_active) {
            return response()->json([
                'message' => 'Your account is not active'
            ], 403);
        }

        if (!auth()->user()->current_group_id) {
            return response()->json([
                'message' => 'You are not assigned to any group'
            ], 403);
        }

        return $next($request);
    }
}
