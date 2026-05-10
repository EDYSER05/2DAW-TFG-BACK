<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsenceRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'absence_type_id' => $this->absence_type_id,
            'start_date' => $this->start_date?->format('d-m-Y'),
            'end_date' => $this->end_date?->format('d-m-Y'),
            'status' => $this->status,
            'comments' => $this->comments,
            'request_date' => $this->request_date?->format('d-m-Y H:i:s'),
            'user' => new UserResource($this->whenLoaded('user')),
            'absence_type' => new AbsenceTypeResource($this->whenLoaded('absenceType')),
            'approvals' => ApprovalResource::collection($this->whenLoaded('approvals')),
        ];
    }
}
