<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department_id' => 'nullable|exists:departments,id',
            'role_id' => 'sometimes|required|exists:roles,id',
            'name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $this->route('user')->id,
            'password' => 'sometimes|required|string|min:8',
            'hire_date' => 'sometimes|required|date',
            'active' => 'boolean',
        ];
    }
}
