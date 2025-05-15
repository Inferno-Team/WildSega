<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'common_name' => $this->common_name,
            'scientific_name' => $this->scientific_name,
            'description' => $this->description,
            'safety_notes' => $this->safety_notes,
            'harvesting_tips' => $this->harvesting_tips,
            'status' => $this->status,
            'image_url' => $this->getMedia('images')->first()?->getUrl(),
            'tags' => $this->whenLoaded('tags', TagResource::collection($this->tags)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
