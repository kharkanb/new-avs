<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentType extends Model
{

    protected $table = 'main_equipment_types';

    use HasFactory;

    protected $fillable = [
        'name', 
        'category',
        'has_brand', 
        'has_height',
        'feeder_mode'
    ];

    protected $casts = [
        "has_height" => "boolean",
        "has_brand" => "boolean"
    ];

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function checklistTemplates(): HasMany
    {
        return $this->hasMany(ChecklistTemplate::class);
    }
}


