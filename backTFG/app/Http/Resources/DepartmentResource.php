<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'active' => $this->active,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'manager' => new UserResource($this->whenLoaded('manager')),
            'employees' => UserResource::collection($this->whenLoaded('employees')),
        ];
    }
}
