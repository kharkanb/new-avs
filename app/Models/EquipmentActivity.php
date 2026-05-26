<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentActivity extends Model
{
    use HasFactory;

    protected $table = 'equipment_activities';
    
    protected $fillable = [
        'main_equipment_id',
        'code',
        'title',
        'unit',
        'unit_price',
        'quantity',
        'total'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function mainEquipment()
    {
        return $this->belongsTo(MainEquipment::class);
    }
}