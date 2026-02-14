<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "category",
        "equipment_type"
    ];

    public function mainEquipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    public function cellEquipments(): HasMany
    {
        return $this->hasMany(CellEquipment::class);
    }
}
