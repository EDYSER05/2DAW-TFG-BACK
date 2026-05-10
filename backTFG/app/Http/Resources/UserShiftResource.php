<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserShiftResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'shift_id' => $this->shift_id,
            'day_id' => $this->day_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'shift' => new ShiftResource($this->whenLoaded('shift')),
            'day' => new DayResource($this->whenLoaded('day')),
        ];
    }
}
