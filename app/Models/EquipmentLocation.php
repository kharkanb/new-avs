<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentLocation extends Model
{
    use HasFactory;

    protected $table = "equipment_location";

    protected $fillable = [
        "equipment_id",
        "latitude",
        "longitude",
        "installation_address",
        "cabinet_initial_height",
        "cabinet_final_height"
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}


