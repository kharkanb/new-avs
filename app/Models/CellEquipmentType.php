<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CellEquipmentType extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function cellEquipments(): HasMany
    {
        return $this->hasMany(CellEquipment::class);
    }
}