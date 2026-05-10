<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Exception;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Department::with(['company', 'manager']);

            // ?active
            if ($request->has('active')) {
                $query->where('active', $request->boolean('active'));
            } else {
                $query->where('active', true);
            }

            // ?company_id
            if ($request->company_id) {
                $query->where('company_id', $request->company_id);
            }

            // ?search
            if ($request->search) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            return DepartmentResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los departamentos', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Department $department)
    {
        try {
            $department->load(['company', 'manager', 'employees.role']);

            return new DepartmentResource($department);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el departamento', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreDepartmentRequest $request)
    {
        try {
            $data = $request->validated();

            $department = Department::create($data);
            $department->load(['company', 'manager']);

            return (new DepartmentResource($department))
                ->additional(['msg' => 'Departamento creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el departamento', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        try {
            $data = $request->validated();

            $department->update($data);
            $department->load(['company', 'manager']);

            return (new DepartmentResource($department))->additional(['msg' => 'Departamento actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el departamento', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Department $department)
    {
        try {
            $department->update(['active' => false]);

            return response()->json(['msg' => 'Departamento desactivado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al desactivar el departamento', 'error' => $e->getMessage()], 500);
        }
    }
}
