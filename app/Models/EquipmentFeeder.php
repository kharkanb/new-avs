<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentFeeder extends Model
{
    use HasFactory;

    protected $table = 'equipment_feeders';
    
    protected $fillable = [
        'main_equipment_id',
        'post',
        'feeder'
    ];

    public function mainEquipment()
    {
        return $this->belongsTo(MainEquipment::class);
    }
}