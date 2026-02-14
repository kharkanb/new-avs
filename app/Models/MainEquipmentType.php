<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainEquipmentType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'feeder_mode', 'has_cells', 'has_brand', 'has_height'];

    protected $casts = [
        'has_cells' => 'boolean',
        'has_brand' => 'boolean',
        'has_height' => 'boolean'
    ];

    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class);
    }
}