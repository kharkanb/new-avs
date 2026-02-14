<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CellEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        "cell_id",
        "equipment_type",
        "brand",
        "other_brand",
        "manual_name"
    ];

    public function cell(): BelongsTo
    {
        return $this->belongsTo(CellSpecification::class, "cell_id");
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, "brand", "name");
    }
}