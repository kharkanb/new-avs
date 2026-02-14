<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'feeders' => FeederResource::collection($this->whenLoaded('feeders')),
            'feeders_count' => $this->whenCounted('feeders'),
        ];
    }
}