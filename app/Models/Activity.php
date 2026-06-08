<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_equipment_id',
        'activitable_type',
        'activitable_id',
        "activity_code",
        "description",
        "unit",
        "unit_price",
        "quantity",
        "total_price"
    ];

    protected $casts = [
        "unit_price" => "decimal:2",
        "total_price" => "decimal:2"
    ];

    public function mainEquipment(): BelongsTo
    {
        return $this->belongsTo(MainEquipment::class);
    }
}


