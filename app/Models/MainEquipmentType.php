<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainEquipmentType extends Model
{
    protected $table = 'main_equipment_types';
    
    protected $fillable = [
        'name',
        'feeder_mode',
        'has_cells',
        'has_brand',
        'has_height',
        'description'
    ];
    
    protected $casts = [
        'has_cells' => 'boolean',
        'has_brand' => 'boolean',
        'has_height' => 'boolean'
    ];
    
    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class, 'main_equipment_type_id');
    }
}