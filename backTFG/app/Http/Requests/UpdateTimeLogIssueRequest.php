<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeLogIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'issue_type_id' => 'sometimes|required|exists:issue_types,id',
            'description' => 'nullable|string',
            'resolved' => 'boolean',
        ];
    }
}
