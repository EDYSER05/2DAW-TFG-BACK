<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Autenticación via Sanctum (Bearer token)
        if (Auth::guard('sanctum')->check()) {
            return $next($request);
        }

        // Acceso de sistema via X-API-KEY
        $apiKey = $request->header('X-API-KEY');
        if ($apiKey && $apiKey === config('app.key')) {
            return $next($request);
        }

        return response()->json(['msg' => 'No autorizado'], 401);
    }
}
