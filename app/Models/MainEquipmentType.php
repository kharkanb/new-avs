<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainEquipmentType extends Model
{
<<<<<<< HEAD
    use SoftDeletes;
    
=======
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
    protected $table = 'main_equipment_types';
    
    protected $fillable = [
        'name',
<<<<<<< HEAD
        'code',
        'description',
=======
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
        'feeder_mode',
        'has_cells',
        'has_brand',
        'has_height',
<<<<<<< HEAD
        'default_coefficient',
        'is_active'
    ];

    
    // ============================================
    // رابطه با MainEquipment (تجهیزات اصلی)
    // ============================================
    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class, 'main_equipment_type_id');
    }

    
    // ============================================
    // رابطه با CellEquipment (تجهیزات سلولی)
    // ============================================
    public function cellEquipments()
    {
        return $this->hasMany(CellEquipment::class, 'cell_equipment_type_id');
    }
    
    // ============================================
    // سایر متدها
    // ============================================
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
=======
        'description'
    ];
    
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
    protected $casts = [
        'has_cells' => 'boolean',
        'has_brand' => 'boolean',
        'has_height' => 'boolean',
    ];
<<<<<<< HEAD

public function brands()
{
    return $this->hasMany(Brand::class, 'equipment_type_id');
} 
=======
    
    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class, 'main_equipment_type_id');
    }
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
}