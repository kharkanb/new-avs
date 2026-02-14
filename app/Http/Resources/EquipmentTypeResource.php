<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'feeder_mode' => $this->feeder_mode,
            'has_cells' => $this->has_cells,
            'has_brand' => $this->has_brand,
            'has_height' => $this->has_height,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}