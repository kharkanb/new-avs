<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MainEquipment extends Model
{
    use HasFactory;

    protected $table = 'main_equipments';
    
    protected $fillable = [
        'inspection_id',
        'main_equipment_type_id',
        'scada_code',
        'post_id',
        'latitude',
        'longitude',
        'height',
        'installation_type',
        'feeders',
        'department_data',
        'location_data',
        'communication_data',
        'checklist_data',
        'activities_data',
        'consumables_data',
        'photos_data',
        'cell_specs',
        'tabs_validated'
    ];
    
    protected $casts = [
        'feeders' => 'array',
        'department_data' => 'array',
        'location_data' => 'array',
        'communication_data' => 'array',
        'checklist_data' => 'array',
        'activities_data' => 'array',
        'consumables_data' => 'array',
        'photos_data' => 'array',
        'cell_specs' => 'array',
        'tabs_validated' => 'array'
    ];

    // روابط
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MainEquipmentType::class, 'main_equipment_type_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function feeders(): BelongsToMany
    {
        return $this->belongsToMany(Feeder::class, 'main_equipment_feeder')
            ->withTimestamps();
    }

    public function cells(): HasMany
    {
        return $this->hasMany(CellSpecification::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function consumables(): HasMany
    {
        return $this->hasMany(Consumable::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}