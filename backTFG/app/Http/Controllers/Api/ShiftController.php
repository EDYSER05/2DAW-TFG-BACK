<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Shift::query();

            // ?search
            if ($request->search) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            return ShiftResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los turnos', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Shift $shift)
    {
        try {
            return new ShiftResource($shift);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el turno', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreShiftRequest $request)
    {
        try {
            $data = $request->validated();

            $shift = Shift::create($data);

            return (new ShiftResource($shift))
                ->additional(['msg' => 'Turno creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el turno', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        try {
            $data = $request->validated();

            $shift->update($data);

            return (new ShiftResource($shift))->additional(['msg' => 'Turno actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el turno', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Shift $shift)
    {
        try {
            $shift->delete();

            return response()->json(['msg' => 'Turno eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['msg' => 'No se puede eliminar el turno porque tiene registros relacionados'], 409);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar el turno', 'error' => $e->getMessage()], 500);
        }
    }
}
