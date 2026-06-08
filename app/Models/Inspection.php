<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

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
        'daily_start_time' => 'string',
        'daily_end_time' => 'string',
        'contract_coefficient' => 'float'
    ];
    
    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class);
    }
}