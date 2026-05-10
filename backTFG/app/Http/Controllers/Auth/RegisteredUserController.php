<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data['must_change_password'] = true;

            $user = User::create($data);
            $user->load(['role', 'department']);

            return (new UserResource($user))
                ->additional(['msg' => 'Usuario registrado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Error al registrar el usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
