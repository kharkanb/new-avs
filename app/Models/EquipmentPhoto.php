<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentPhoto extends Model
{
    use HasFactory;

    protected $table = 'equipment_photos';
    
    protected $fillable = [
        'main_equipment_id',
        'scan_code',
        'description',
        'path',
        'sort_order'
    ];

    public function mainEquipment()
    {
        return $this->belongsTo(MainEquipment::class);
    }
}