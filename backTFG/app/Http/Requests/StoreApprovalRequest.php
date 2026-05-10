<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'absence_request_id' => 'required|exists:absence_requests,id|unique:approvals',
            'approved_by' => 'required|exists:users,id',
            'status' => 'required|in:approved,rejected',
            'comments' => 'nullable|string',
        ];
    }
}
