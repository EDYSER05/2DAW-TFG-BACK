<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimeLogResource;
use App\Models\TimeLog;
use App\Http\Requests\StoreTimeLogRequest;
use App\Http\Requests\UpdateTimeLogRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = TimeLog::with(['user'])->orderBy('date', 'desc');

            // ?user_id
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            // ?department_id
            if ($request->department_id) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $request->department_id));
            }

            // ?date_from
            if ($request->date_from) {
                $query->whereDate('date', '>=', $request->date_from);
            }

            // ?date_to
            if ($request->date_to) {
                $query->whereDate('date', '<=', $request->date_to);
            }

            return TimeLogResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los fichajes', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(TimeLog $timeLog)
    {
        try {
            $timeLog->load(['user', 'issues.issueType']);

            return new TimeLogResource($timeLog);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el fichaje', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreTimeLogRequest $request)
    {
        try {
            $data = $request->validated();

            $timeLog = TimeLog::create($data);
            $timeLog->load(['user']);

            return (new TimeLogResource($timeLog))
                ->additional(['msg' => 'Fichaje creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el fichaje', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateTimeLogRequest $request, TimeLog $timeLog)
    {
        try {
            $data = $request->validated();

            $timeLog->update($data);
            $timeLog->load(['user']);

            return (new TimeLogResource($timeLog))->additional(['msg' => 'Fichaje actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el fichaje', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(TimeLog $timeLog)
    {
        try {
            $timeLog->delete();

            return response()->json(['msg' => 'Fichaje eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['msg' => 'No se puede eliminar el fichaje porque tiene registros relacionados'], 409);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar el fichaje', 'error' => $e->getMessage()], 500);
        }
    }
}
