<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DayResource;
use App\Models\Day;
use App\Http\Requests\StoreDayRequest;
use App\Http\Requests\UpdateDayRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DayController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Day::query();

            return DayResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los días', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Day $day)
    {
        try {
            return new DayResource($day);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el día', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreDayRequest $request)
    {
        try {
            $data = $request->validated();

            $day = Day::create($data);

            return (new DayResource($day))
                ->additional(['msg' => 'Día creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el día', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateDayRequest $request, Day $day)
    {
        try {
            $data = $request->validated();

            $day->update($data);

            return (new DayResource($day))->additional(['msg' => 'Día actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el día', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Day $day)
    {
        try {
            $day->delete();

            return response()->json(['msg' => 'Día eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['msg' => 'No se puede eliminar el día porque tiene registros relacionados'], 409);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar el día', 'error' => $e->getMessage()], 500);
        }
    }
}
