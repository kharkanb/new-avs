<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    protected $keyType = 'int';
    
    public $timestamps = true;
    
    protected $fillable = [
        // از بخش اول
        "inspection_id",
        "equipment_index",
        "equipment_type",
        "scada_code",
        "installation_type",
        "switch_brand",
        "modem_brand",
        "rtu_brand",
        "other_switch_brand",
        "other_modem_brand",
        "other_rtu_brand",
        "start_time",
        "end_time",
        "department",
        "city_gis_code",
        
        // از بخش دوم (به صورت JSON)
        'feeders',
        'location_data',
        'communication_data',
        'checklist_data',
        'activities_data',
        'consumables_data',
        'photos_data',
        'department_data',
        'cell_specs',
        'tabs_validated'
    ];
    
    protected $casts = [
        "start_time" => "datetime:H:i",
        "end_time" => "datetime:H:i",
        
        // JSON casts از بخش دوم
        'feeders' => 'array',
        'location_data' => 'array',
        'communication_data' => 'array',
        'checklist_data' => 'array',
        'activities_data' => 'array',
        'consumables_data' => 'array',
        'photos_data' => 'array',
        'department_data' => 'array',
        'cell_specs' => 'array',
        'tabs_validated' => 'array'
    ];
    
    protected $attributes = [
        'start_time' => '08:00:00',
        'end_time' => '16:00:00'
    ];

    // Relationships از بخش اول
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, "equipment_type", "name");
    }

    public function feeders(): BelongsToMany
    {
        return $this->belongsToMany(Feeder::class, "equipment_feeders")
                    ->withPivot("feeder_number", "post_name", "feeder_name")
                    ->withTimestamps();
    }

    public function location(): HasOne
    {
        return $this->hasOne(EquipmentLocation::class);
    }

    public function communication(): HasOne
    {
        return $this->hasOne(EquipmentCommunication::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function consumables(): HasMany
    {
        return $this->hasMany(Consumable::class);
    }

    public function cellSpecifications(): HasMany
    {
        return $this->hasMany(CellSpecification::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}