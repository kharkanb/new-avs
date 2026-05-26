<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistTemplate extends Model
{
    use SoftDeletes;
    
    protected $table = 'checklist_templates';
    
    protected $fillable = [
        'main_equipment_type_id',
        'title',
        'description'
    ];
    
    public function mainEquipmentType(): BelongsTo
    {
        return $this->belongsTo(MainEquipmentType::class, 'main_equipment_type_id');
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class, 'checklist_template_id');
    }
}