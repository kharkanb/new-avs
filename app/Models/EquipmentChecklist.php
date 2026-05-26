<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentChecklist extends Model
{
    use HasFactory;

    protected $table = 'equipment_checklists';
    
    protected $fillable = [
        'main_equipment_id',
        'item',
        'status',
        'description',
        'sort_order'
    ];

    public function mainEquipment()
    {
        return $this->belongsTo(MainEquipment::class);
    }
}