<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'equipment_type_id',
        'cell_equipment_type_id'
    ];
<<<<<<< HEAD
    
    public function mainEquipmentType()
    {
        return $this->belongsTo(MainEquipmentType::class, 'equipment_type_id');
    }
    
    public function cellEquipmentType()
=======

    public function cellEquipments(): HasMany
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
    {
        return $this->belongsTo(CellEquipmentType::class, 'cell_equipment_type_id');
    }
}