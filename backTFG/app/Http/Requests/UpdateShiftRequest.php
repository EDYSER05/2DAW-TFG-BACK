<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255|unique:shifts,name,' . $this->route('shift')->id,
            'start_time' => 'sometimes|required|date_format:H:i:s',
            'end_time' => 'sometimes|required|date_format:H:i:s',
        ];
    }
}
