<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shift_id' => 'sometimes|required|exists:shifts,id',
            'day_id' => 'sometimes|required|exists:days,id',
        ];
    }
}
