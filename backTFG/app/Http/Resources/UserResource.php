<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'hire_date' => $this->hire_date ? Carbon::parse($this->hire_date)->format('d-m-Y') : null,
            'active' => $this->active,
            'must_change_password' => $this->must_change_password,
            'last_login_at' => $this->last_login_at?->format('d-m-Y H:i:s'),
            'role' => new RoleResource($this->whenLoaded('role')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
        ];
    }
}
