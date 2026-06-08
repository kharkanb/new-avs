<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        "main_equipment_type_id",
        "title",
        "description"
    ];

    public function mainEquipmentType(): BelongsTo
    {
        return $this->belongsTo(MainEquipmentType::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ChecklistTemplateItem::class);
    }
}