<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "main_equipment_id",
        "checklist_template_item_id",
        "status",
        "description"
    ];

    protected $casts = [
        "status" => "string"
    ];

    public function mainEquipment(): BelongsTo
    {
        return $this->belongsTo(MainEquipment::class);
    }

    public function templateItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplateItem::class, 'checklist_template_item_id');
    }
}