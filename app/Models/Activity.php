<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_equipment_id',  // ✅ این خط رو اضافه کن
        "equipment_id",
        "activity_code",
        "activity_title",
        "unit",
        "unit_price",
        "quantity",
        "total_price"
    ];

    protected $casts = [
        "unit_price" => "decimal:2",
        "total_price" => "decimal:2"
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}


