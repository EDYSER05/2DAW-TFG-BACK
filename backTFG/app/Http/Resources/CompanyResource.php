<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tax_id' => $this->tax_id,
            'address' => $this->address,
            'active' => $this->active,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'departments' => DepartmentResource::collection($this->whenLoaded('departments')),
            'holidays' => HolidayResource::collection($this->whenLoaded('holidays')),
        ];
    }
}
