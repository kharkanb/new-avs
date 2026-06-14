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
        "cell_equipment_type_id",
        "brand_id",
        "other_brand",
        "model",
        "serial_number",
        "manufacture_year",
        "manual_name",
        "description"
    ];

    public function cell(): BelongsTo
    {
        return $this->belongsTo(CellSpecification::class, "cell_id");
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CellEquipmentType::class, 'cell_equipment_type_id');
    }
}