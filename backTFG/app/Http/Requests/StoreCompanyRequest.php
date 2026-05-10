<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'owner_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:50|unique:companies',
            'address' => 'nullable|string|max:255',
        ];
    }
}
