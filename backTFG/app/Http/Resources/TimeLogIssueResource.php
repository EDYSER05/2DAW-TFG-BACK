<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeLogIssueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'time_log_id' => $this->time_log_id,
            'user_id' => $this->user_id,
            'issue_type_id' => $this->issue_type_id,
            'description' => $this->description,
            'resolved' => $this->resolved,
            'created_at' => $this->created_at?->format('d-m-Y H:i:s'),
            'time_log' => new TimeLogResource($this->whenLoaded('timeLog')),
            'issue_type' => new IssueTypeResource($this->whenLoaded('issueType')),
            'reported_by' => new UserResource($this->whenLoaded('reportedBy')),
        ];
    }
}
