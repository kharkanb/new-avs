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
    
    public function mainEquipmentType()
    {
        return $this->belongsTo(MainEquipmentType::class, 'equipment_type_id');
    }
    
    public function cellEquipmentType()
    {
        return $this->belongsTo(CellEquipmentType::class, 'cell_equipment_type_id');
    }
}