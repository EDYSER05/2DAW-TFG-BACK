<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AbsenceRequestResource;
use App\Models\AbsenceRequest;
use App\Http\Requests\StoreAbsenceRequestRequest;
use App\Http\Requests\UpdateAbsenceRequestRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AbsenceRequestController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AbsenceRequest::with(['user', 'absenceType', 'approvals'])->orderBy('request_date', 'desc');

            // ?user_id
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            // ?absence_type_id
            if ($request->absence_type_id) {
                $query->where('absence_type_id', $request->absence_type_id);
            }

            // ?status
            if ($request->status) {
                $query->where('status', $request->status);
            }

            // ?department_id
            if ($request->department_id) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $request->department_id));
            }

            // ?date_from
            if ($request->date_from) {
                $query->whereDate('start_date', '>=', $request->date_from);
            }

            // ?date_to
            if ($request->date_to) {
                $query->whereDate('end_date', '<=', $request->date_to);
            }

            return AbsenceRequestResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener las solicitudes de ausencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(AbsenceRequest $absenceRequest)
    {
        try {
            $absenceRequest->load(['user', 'absenceType', 'approval.approvedBy']);

            return new AbsenceRequestResource($absenceRequest);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener la solicitud de ausencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreAbsenceRequestRequest $request)
    {
        try {
            $data = $request->validated();
            $data['status'] = 'pending';
            $data['request_date'] = now();

            $absenceRequest = AbsenceRequest::create($data);
            $absenceRequest->load(['user', 'absenceType']);

            return (new AbsenceRequestResource($absenceRequest))
                ->additional(['msg' => 'Solicitud de ausencia creada correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear la solicitud de ausencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateAbsenceRequestRequest $request, AbsenceRequest $absenceRequest)
    {
        try {
            $data = $request->validated();

            $absenceRequest->update($data);
            $absenceRequest->load(['user', 'absenceType', 'approvals']);

            return (new AbsenceRequestResource($absenceRequest))->additional(['msg' => 'Solicitud actualizada correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar la solicitud de ausencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(AbsenceRequest $absenceRequest)
    {
        try {
            $absenceRequest->delete();

            return response()->json(['msg' => 'Solicitud de ausencia eliminada correctamente']);
        } catch (QueryException $e) {
            return response()->json(['msg' => 'No se puede eliminar la solicitud porque tiene registros relacionados'], 409);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar la solicitud de ausencia', 'error' => $e->getMessage()], 500);
        }
    }
}
