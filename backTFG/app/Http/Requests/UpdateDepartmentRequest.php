<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'sometimes|required|exists:companies,id',
            'name' => 'sometimes|required|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'active' => 'boolean',
        ];
    }
}
