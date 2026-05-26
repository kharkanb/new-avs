<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentConsumable extends Model
{
    use HasFactory;

    protected $table = 'equipment_consumables';
    
    protected $fillable = [
        'main_equipment_id',
        'name',
        'other_name',
        'quantity',
        'unit',
        'description'
    ];

    public function mainEquipment()
    {
        return $this->belongsTo(MainEquipment::class);
    }
}