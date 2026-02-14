<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentCommunication extends Model
{
    use HasFactory;

    protected $table = "equipment_communication";

    protected $fillable = [
        "equipment_id",
        "simcard_type",
        "simcard_number",
        "simcard_ip",
        "antenna_status",
        "signal_status",
        "modem_power",
        "reset_possible"
    ];

    protected $casts = [
        "reset_possible" => "boolean"
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}


