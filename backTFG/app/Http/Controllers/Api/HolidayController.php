<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use App\Http\Requests\StoreHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;
use Exception;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Holiday::query()->orderBy('date');

            // ?search
            if ($request->search) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            // ?year
            if ($request->year) {
                $query->whereYear('date', $request->year);
            }

            // ?company_id
            if ($request->company_id) {
                $query->whereHas('companies', fn($q) => $q->where('companies.id', $request->company_id));
            }

            return HolidayResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener los festivos', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Holiday $holiday)
    {
        try {
            return new HolidayResource($holiday);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener el festivo', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreHolidayRequest $request)
    {
        try {
            $data = $request->validated();

            $holiday = Holiday::create($data);

            return (new HolidayResource($holiday))
                ->additional(['msg' => 'Festivo creado correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear el festivo', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateHolidayRequest $request, Holiday $holiday)
    {
        try {
            $data = $request->validated();

            $holiday->update($data);

            return (new HolidayResource($holiday))->additional(['msg' => 'Festivo actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar el festivo', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Holiday $holiday)
    {
        try {
            $holiday->companies()->detach();
            $holiday->delete();

            return response()->json(['msg' => 'Festivo eliminado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al eliminar el festivo', 'error' => $e->getMessage()], 500);
        }
    }
}
