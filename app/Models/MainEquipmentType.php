<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ChecklistTemplate;

class MainEquipmentType extends Model
{
<<<<<<< HEAD
    use SoftDeletes;
    
=======
    use HasFactory;

>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
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
    
    protected $casts = [
        'has_cells' => 'boolean',
        'has_brand' => 'boolean',
        'has_height' => 'boolean',
        'is_active' => 'boolean',
        'default_coefficient' => 'float'
    ];
    
    // ============================================
    // Model Events
    // ============================================
    protected static function booted()
    {
        static::created(function ($mainEquipmentType) {
            if (!\App\Models\ChecklistTemplate::where('main_equipment_type_id', $mainEquipmentType->id)->exists()) {
                \App\Models\ChecklistTemplate::create([
                    'main_equipment_type_id' => $mainEquipmentType->id,
                    'title' => 'چک‌لیست ' . $mainEquipmentType->name,
                    'description' => 'چک‌لیست مربوط به ' . $mainEquipmentType->name
                ]);
            }
        });
        
        static::deleting(function ($mainEquipmentType) {
            if ($mainEquipmentType->checklistTemplate) {
                $mainEquipmentType->checklistTemplate->items()->delete();
                $mainEquipmentType->checklistTemplate->delete();
            }
        });
    }
    
    // ============================================
    // روابط
    // ============================================
    public function mainEquipments(): HasMany
    {
        return $this->hasMany(MainEquipment::class, 'main_equipment_type_id');
    }
    
    public function cellEquipments(): HasMany
    {
        return $this->hasMany(CellEquipment::class, 'cell_equipment_type_id');
    }
    
    public function checklistTemplate(): HasOne
    {
        return $this->hasOne(ChecklistTemplate::class, 'main_equipment_type_id');
    }
    
    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class, 'equipment_type_id');
    }
    
    // ============================================
    // اسکوپ‌ها
    // ============================================
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeHasChecklist($query)
    {
        return $query->has('checklistTemplate');
    }
    
    public function scopeWithoutChecklist($query)
    {
        return $query->doesntHave('checklistTemplate');
    }
    
    // ============================================
    // متدهای کمکی (Helper Methods)
    // ============================================
    
    /**
     * بررسی یکتا بودن نام با در نظر گرفتن Soft Delete
     */
    public static function isNameUnique($name, $excludeId = null)
    {
        $query = static::where('name', $name)->whereNull('deleted_at');
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }
}