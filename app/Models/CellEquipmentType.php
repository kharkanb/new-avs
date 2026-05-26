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

public function brands()
{
    return $this->hasMany(Brand::class, 'cell_equipment_type_id');
}


}