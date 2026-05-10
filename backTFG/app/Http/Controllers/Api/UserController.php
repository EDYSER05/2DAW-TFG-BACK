<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::with(['role', 'department']);

            // ?active
            if ($request->has('active')) {
                $query->where('active', $request->boolean('active'));
            } else {
                $query->where('active', true);
            }

            // ?department_id
            if ($request->department_id) {
                $query->where('department_id', $request->department_id);
            }

            // ?role_id
            if ($request->role_id) {
                $query->where('role_id', $request->role_id);
            }

            // ?search
            if ($request->search) {
                $search = $request->search;
                $query->where(fn($q) =>
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                );
            }

            return UserResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los usuarios', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(User $user)
    {
        try {
            $user->load(['role', 'department', 'userShifts.shift', 'userShifts.day']);

            return new UserResource($user);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();

            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            $user->load(['role', 'department']);

            return (new UserResource($user))
                ->additional(['msg' => 'Usuario creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);
            $user->load(['role', 'department']);

            return (new UserResource($user))->additional(['msg' => 'Usuario actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->update(['active' => false]);

            return response()->json(['msg' => 'Usuario desactivado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al desactivar el usuario', 'error' => $e->getMessage()], 500);
        }
    }
}
