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
        "equipment_id",
        "cell_type",
        "cell_number",
        "cell_notes"
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function cellEquipments(): HasMany
    {
        return $this->hasMany(CellEquipment::class, "cell_id");
    }
}


