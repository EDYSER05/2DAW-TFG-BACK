<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimeLogIssueResource;
use App\Models\TimeLogIssue;
use App\Http\Requests\StoreTimeLogIssueRequest;
use App\Http\Requests\UpdateTimeLogIssueRequest;
use Exception;
use Illuminate\Http\Request;

class TimeLogIssueController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = TimeLogIssue::with(['reportedBy', 'issueType', 'timeLog'])->orderBy('created_at', 'desc');

            // ?time_log_id
            if ($request->time_log_id) {
                $query->where('time_log_id', $request->time_log_id);
            }

            // ?user_id
            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            // ?issue_type_id
            if ($request->issue_type_id) {
                $query->where('issue_type_id', $request->issue_type_id);
            }

            // ?resolved
            if ($request->has('resolved')) {
                $query->where('resolved', $request->boolean('resolved'));
            }

            return TimeLogIssueResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener las incidencias', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(TimeLogIssue $timeLogIssue)
    {
        try {
            $timeLogIssue->load(['reportedBy', 'issueType', 'timeLog.user']);

            return new TimeLogIssueResource($timeLogIssue);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener la incidencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreTimeLogIssueRequest $request)
    {
        try {
            $data = $request->validated();

            $issue = TimeLogIssue::create($data);
            $issue->load(['reportedBy', 'issueType', 'timeLog']);

            return (new TimeLogIssueResource($issue))
                ->additional(['msg' => 'Incidencia creada correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear la incidencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateTimeLogIssueRequest $request, TimeLogIssue $timeLogIssue)
    {
        try {
            $data = $request->validated();

            $timeLogIssue->update($data);
            $timeLogIssue->load(['reportedBy', 'issueType', 'timeLog']);

            return (new TimeLogIssueResource($timeLogIssue))->additional(['msg' => 'Incidencia actualizada correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar la incidencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(TimeLogIssue $timeLogIssue)
    {
        try {
            $timeLogIssue->delete();

            return response()->json(['msg' => 'Incidencia eliminada correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar la incidencia', 'error' => $e->getMessage()], 500);
        }
    }
}
