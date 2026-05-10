<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'sometimes|required|date|unique:holidays,date,' . $this->route('holiday')->id,
            'name' => 'sometimes|required|string|max:255',
        ];
    }
}
