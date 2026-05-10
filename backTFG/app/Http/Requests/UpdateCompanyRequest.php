<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'owner_id' => 'nullable|exists:users,id',
            'name' => 'sometimes|required|string|max:255',
            'tax_id' => 'sometimes|required|string|max:50|unique:companies,tax_id,' . $this->route('company')->id,
            'address' => 'nullable|string|max:255',
            'active' => 'boolean',
        ];
    }
}
