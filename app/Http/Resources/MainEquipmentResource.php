<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainEquipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'scada_code' => $this->scada_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'height' => $this->height,
            'installation_type' => $this->installation_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relations
            'type' => new EquipmentTypeResource($this->whenLoaded('type')),
            'post' => new PostResource($this->whenLoaded('post')),
            'feeders' => FeederResource::collection($this->whenLoaded('feeders')),
            'cells' => CellSpecificationResource::collection($this->whenLoaded('cells')),
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
            'consumables' => ConsumableResource::collection($this->whenLoaded('consumables')),
            'checklist_items' => ChecklistItemResource::collection($this->whenLoaded('checklistItems')),
            'photos' => PhotoResource::collection($this->whenLoaded('photos')),
            
            // Counts
            'cells_count' => $this->whenCounted('cells'),
            'activities_count' => $this->whenCounted('activities'),
            'consumables_count' => $this->whenCounted('consumables'),
            'photos_count' => $this->whenCounted('photos'),
        ];
    }
}