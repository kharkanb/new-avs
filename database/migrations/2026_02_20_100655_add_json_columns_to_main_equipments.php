<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainEquipment extends Model
{
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
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    public function type()
    {
        return $this->belongsTo(MainEquipmentType::class, 'main_equipment_type_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
// ❌ اینجا نباید کلاس دیگری تعریف شود