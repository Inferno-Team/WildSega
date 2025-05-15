<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantDiscoveryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'plant_id' => $this->plant_id,
            'photo_path' => $this->getMedia('images')->first()?->getFullUrl(),
            'ai_confidence_score' => $this->ai_confidence_score,
            'status' => $this->status,
            'admin_notes' => $this->admin_notes,
            'plant' => $this->whenLoaded('plant', PlantResource::make($this->plant)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
