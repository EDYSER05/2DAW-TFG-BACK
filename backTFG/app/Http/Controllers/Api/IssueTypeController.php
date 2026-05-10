<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IssueTypeResource;
use App\Models\IssueType;
use App\Http\Requests\StoreIssueTypeRequest;
use App\Http\Requests\UpdateIssueTypeRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class IssueTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = IssueType::query();

            // ?search
            if ($request->search) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            return IssueTypeResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los tipos de incidencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(IssueType $issueType)
    {
        try {
            return new IssueTypeResource($issueType);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el tipo de incidencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreIssueTypeRequest $request)
    {
        try {
            $data = $request->validated();

            $issueType = IssueType::create($data);

            return (new IssueTypeResource($issueType))
                ->additional(['msg' => 'Tipo de incidencia creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el tipo de incidencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateIssueTypeRequest $request, IssueType $issueType)
    {
        try {
            $data = $request->validated();

            $issueType->update($data);

            return (new IssueTypeResource($issueType))->additional(['msg' => 'Tipo de incidencia actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el tipo de incidencia', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(IssueType $issueType)
    {
        try {
            $issueType->delete();

            return response()->json(['msg' => 'Tipo de incidencia eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['msg' => 'No se puede eliminar el tipo de incidencia porque tiene registros relacionados'], 409);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar el tipo de incidencia', 'error' => $e->getMessage()], 500);
        }
    }
}
