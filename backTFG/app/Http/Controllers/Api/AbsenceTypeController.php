<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AbsenceTypeResource;
use App\Models\AbsenceType;
use App\Http\Requests\StoreAbsenceTypeRequest;
use App\Http\Requests\UpdateAbsenceTypeRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AbsenceTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AbsenceType::query();

            // ?search
            if ($request->search) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            return AbsenceTypeResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los tipos de ausencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(AbsenceType $absenceType)
    {
        try {
            return new AbsenceTypeResource($absenceType);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el tipo de ausencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreAbsenceTypeRequest $request)
    {
        try {
            $data = $request->validated();

            $absenceType = AbsenceType::create($data);

            return (new AbsenceTypeResource($absenceType))
                ->additional(['msg' => 'Tipo de ausencia creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el tipo de ausencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateAbsenceTypeRequest $request, AbsenceType $absenceType)
    {
        try {
            $data = $request->validated();

            $absenceType->update($data);

            return (new AbsenceTypeResource($absenceType))->additional(['msg' => 'Tipo de ausencia actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el tipo de ausencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(AbsenceType $absenceType)
    {
        try {
            $absenceType->delete();

            return response()->json(['msg' => 'Tipo de ausencia eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['msg' => 'No se puede eliminar el tipo de ausencia porque tiene registros relacionados'], 409);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar el tipo de ausencia', 'error' => $e->getMessage()], 500);
        }
    }
}
