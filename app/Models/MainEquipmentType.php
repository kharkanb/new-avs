<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainEquipmentType extends Model
{
    use SoftDeletes;
    
    protected $table = 'main_equipment_types';
    
    protected $fillable = [
        'name',
        'code',
        'description',
        'feeder_mode',
        'has_cells',
        'has_brand',
        'has_height',
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
    protected $casts = [
        'has_cells' => 'boolean',
        'has_brand' => 'boolean',
        'has_height' => 'boolean',
    ];

public function brands()
{
    return $this->hasMany(Brand::class, 'equipment_type_id');
} 
}