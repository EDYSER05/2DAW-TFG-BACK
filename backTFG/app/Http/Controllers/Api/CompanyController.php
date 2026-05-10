<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Exception;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Company::with(['owner']);

            // ?active
            if ($request->has('active')) {
                $query->where('active', $request->boolean('active'));
            } else {
                $query->where('active', true);
            }

            // ?search
            if ($request->search) {
                $search = $request->search;
                $query->where(fn($q) =>
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('tax_id', 'like', "%{$search}%")
                );
            }

            return CompanyResource::collection($query->get());
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener las empresas', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Company $company)
    {
        try {
            $company->load(['owner', 'departments.manager', 'holidays']);

            return new CompanyResource($company);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al obtener la empresa', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreCompanyRequest $request)
    {
        try {
            $data = $request->validated();

            $company = Company::create($data);
            $company->load(['owner']);

            return (new CompanyResource($company))
                ->additional(['msg' => 'Empresa creada correctamente'])
                ->response()
                ->setStatusCode(201);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al crear la empresa', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        try {
            $data = $request->validated();

            $company->update($data);
            $company->load(['owner']);

            return (new CompanyResource($company))->additional(['msg' => 'Empresa actualizada correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al actualizar la empresa', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Company $company)
    {
        try {
            $company->update(['active' => false]);

            return response()->json(['msg' => 'Empresa desactivada correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al desactivar la empresa', 'error' => $e->getMessage()], 500);
        }
    }

    public function attachHoliday(Request $request, Company $company)
    {
        try {
            $data = $request->validate(['holiday_id' => 'required|exists:holidays,id']);
            $company->holidays()->syncWithoutDetaching([$data['holiday_id']]);

            return response()->json(['msg' => 'Festivo asociado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al asociar el festivo', 'error' => $e->getMessage()], 500);
        }
    }

    public function detachHoliday(Request $request, Company $company)
    {
        try {
            $data = $request->validate(['holiday_id' => 'required|exists:holidays,id']);
            $company->holidays()->detach($data['holiday_id']);

            return response()->json(['msg' => 'Festivo desvinculado correctamente']);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Error al desvincular el festivo', 'error' => $e->getMessage()], 500);
        }
    }
}
