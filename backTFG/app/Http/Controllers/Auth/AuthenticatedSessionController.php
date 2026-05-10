<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();

            $user = Auth::user();
            $user->update(['last_login_at' => now()]);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'msg' => 'Login correcto',
                'token' => $token,
                'must_change_password' => (bool) $user->must_change_password,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Credenciales incorrectas',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['msg' => 'Sesión cerrada correctamente']);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Error al cerrar sesión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $data = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = Auth::guard('sanctum')->user();

            $user->update([
                'password' => Hash::make($data['password']),
                'must_change_password' => false,
            ]);

            return response()->json(['msg' => 'Contraseña actualizada correctamente']);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Error al cambiar la contraseña',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
