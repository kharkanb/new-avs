<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentLocation extends Model
{
    use HasFactory;

    protected $table = 'equipment_locations';
    
    protected $fillable = [
        'main_equipment_id',
        'latitude',
        'longitude',
        'address',
        'cabinet_initial_height',
        'cabinet_final_height'
    ];

    public function mainEquipment()
    {
        return $this->belongsTo(MainEquipment::class);
    }
}