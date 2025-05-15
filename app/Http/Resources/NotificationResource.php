<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'type'      => $this->type,
            'data'      => $this->data, // Customize if needed (e.g., merge plant/discovery data)
            'read_at'   => $this->read_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'time_ago'  => $this->created_at->diffForHumans(), // Optional: "2 hours ago"
        ];
    }
}
