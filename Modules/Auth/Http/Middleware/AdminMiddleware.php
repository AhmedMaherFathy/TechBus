<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        info(Auth::guard('sanctum')->user());
        
        if (!Auth::guard('admin')->check()) {
            return response()->json([
                'message' => 'Unauthorized .',
            ], 401);
        }
        return $next($request);
    }
}
