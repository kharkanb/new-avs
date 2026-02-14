<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inspection_date' => $this->inspection_date,
            'daily_start_time' => $this->daily_start_time,
            'daily_end_time' => $this->daily_end_time,
            'contractor' => $this->contractor,
            'contract_coefficient' => $this->contract_coefficient,
            'contract_number' => $this->contract_number,
            'whatsapp_number' => $this->whatsapp_number,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relations
            'main_equipments' => MainEquipmentResource::collection($this->whenLoaded('mainEquipments')),
            'main_equipments_count' => $this->whenCounted('mainEquipments'),
        ];
    }

    private function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'پیش‌نویس',
            'completed' => 'تکمیل شده',
            'archived' => 'بایگانی شده',
            default => 'نامشخص'
        };
    }
}