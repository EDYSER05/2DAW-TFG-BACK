<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        // X-API-KEY tiene acceso de sistema completo y por lo tanto omite la comprobación de rol
        $apiKey = $request->header('X-API-KEY');
        if ($apiKey && $apiKey === config('app.key')) {
            return $next($request);
        }

        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['msg' => 'No autenticado'], 401);
        }

        if (!in_array($user->role?->name, $roles)) {
            return response()->json(['msg' => 'Acceso denegado: permisos insuficientes'], 403);
        }

        return $next($request);
    }
}
