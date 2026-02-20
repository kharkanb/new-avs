<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'inspection_date',
        'daily_start_time',
        'daily_end_time',
        'contractor',
        'contract_coefficient',
        'contract_number',
        'whatsapp_number',
        'status'
    ];
    
    protected $casts = [
        'inspection_date' => 'date',
        'daily_start_time' => 'datetime',
        'daily_end_time' => 'datetime',
        'contract_coefficient' => 'float'
    ];
    
    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class);
    }
}