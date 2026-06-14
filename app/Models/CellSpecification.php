<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CellSpecification extends Model
{
    use HasFactory;

    protected $table = "cell_specifications";

    protected $fillable = [
        "main_equipment_id",
        "cell_name",
        "description"
    ];

    public function mainEquipment(): BelongsTo
    {
        return $this->belongsTo(MainEquipment::class);
    }

    public function cellEquipments(): HasMany
    {
        return $this->hasMany(CellEquipment::class, "cell_id");
    }
}


