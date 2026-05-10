<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserShiftResource;
use App\Models\UserShift;
use App\Http\Requests\StoreUserShiftRequest;
use App\Http\Requests\UpdateUserShiftRequest;
use Exception;
use Illuminate\Http\Request;

class UserShiftController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = UserShift::with(['user', 'shift', 'day']);

            // ?user_id
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            // ?shift_id
            if ($request->shift_id) {
                $query->where('shift_id', $request->shift_id);
            }

            // ?day_id
            if ($request->day_id) {
                $query->where('day_id', $request->day_id);
            }

            // ?department_id
            if ($request->department_id) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $request->department_id));
            }

            return UserShiftResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los turnos de usuarios', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(UserShift $userShift)
    {
        try {
            $userShift->load(['user', 'shift', 'day']);

            return new UserShiftResource($userShift);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el turno del usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreUserShiftRequest $request)
    {
        try {
            $data = $request->validated();

            $userShift = UserShift::create($data);
            $userShift->load(['user', 'shift', 'day']);

            return (new UserShiftResource($userShift))
                ->additional(['msg' => 'Turno asignado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al asignar el turno', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateUserShiftRequest $request, UserShift $userShift)
    {
        try {
            $data = $request->validated();

            $userShift->update($data);
            $userShift->load(['user', 'shift', 'day']);

            return (new UserShiftResource($userShift))->additional(['msg' => 'Turno de usuario actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el turno de usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(UserShift $userShift)
    {
        try {
            $userShift->delete();

            return response()->json(['msg' => 'Turno de usuario eliminado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar el turno de usuario', 'error' => $e->getMessage()], 500);
        }
    }
}
