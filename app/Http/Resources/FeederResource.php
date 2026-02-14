<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeederResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'feeder_number' => $this->feeder_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'post' => new PostResource($this->whenLoaded('post')),
        ];
    }
}