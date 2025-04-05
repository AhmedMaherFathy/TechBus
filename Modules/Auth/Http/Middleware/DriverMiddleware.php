<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('driver')->check()) {
            return response()->json([
                'message' => 'Unauthorized .',
            ], 401);
        }
    
        return $next($request);
    }
}
