<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Role::query();

            // ?search
            if ($request->search) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            return RoleResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los roles', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Role $role)
    {
        try {
            return new RoleResource($role);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el rol', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $data = $request->validated();

            $role = Role::create($data);

            return (new RoleResource($role))
                ->additional(['msg' => 'Rol creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el rol', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $data = $request->validated();

            $role->update($data);

            return (new RoleResource($role))->additional(['msg' => 'Rol actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el rol', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return response()->json(['msg' => 'Rol eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['msg' => 'No se puede eliminar el rol porque tiene registros relacionados'], 409);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar el rol', 'error' => $e->getMessage()], 500);
        }
    }
}
