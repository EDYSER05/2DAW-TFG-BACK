<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'absence_request_id' => $this->absence_request_id,
            'approved_by' => $this->approved_by,
            'status' => $this->status,
            'comments' => $this->comments,
            'created_at' => $this->created_at?->format('d-m-Y H:i:s'),
            'absence_request' => new AbsenceRequestResource($this->whenLoaded('absenceRequest')),
            'approver' => new UserResource($this->whenLoaded('approvedBy')),
        ];
    }
}
