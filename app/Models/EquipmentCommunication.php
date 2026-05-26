<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCommunication extends Model
{
    use HasFactory;

    protected $table = 'equipment_communications';
    
    protected $fillable = [
        'main_equipment_id',
        'simcard_type',
        'simcard_number',
        'simcard_ip',
        'antenna_status',
        'signal_status',
        'modem_power',
        'reset_possible'
    ];

    protected $casts = [
        'reset_possible' => 'boolean'
    ];

    public function mainEquipment()
    {
        return $this->belongsTo(MainEquipment::class);
    }
}