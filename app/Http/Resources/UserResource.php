<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'notification_range_km' => $this->notification_range_km,
            'created_at' => $this->created_at,
            'is_admin' => $this->roles()->where('name', 'admin')->exists(),
        ];
    }
}
