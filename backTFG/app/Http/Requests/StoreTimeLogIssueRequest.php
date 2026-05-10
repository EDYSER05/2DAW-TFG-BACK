<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimeLogIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'time_log_id' => 'required|exists:time_logs,id',
            'user_id' => 'required|exists:users,id',
            'issue_type_id' => 'required|exists:issue_types,id',
            'description' => 'nullable|string',
            'resolved' => 'boolean',
        ];
    }
}
