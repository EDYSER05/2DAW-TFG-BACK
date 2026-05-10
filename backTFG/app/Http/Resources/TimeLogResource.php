<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'date' => $this->date?->format('d-m-Y'),
            'check_in' => $this->check_in?->format('d-m-Y H:i:s'),
            'check_out' => $this->check_out?->format('d-m-Y H:i:s'),
            'user' => new UserResource($this->whenLoaded('user')),
            'issues' => TimeLogIssueResource::collection($this->whenLoaded('issues')),
        ];
    }
}
