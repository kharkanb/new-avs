<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'city',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class);
    }

    public function inspections()
    {
        return $this->hasManyThrough(
            Inspection::class,
            MainEquipment::class,
            'department_id',
            'id',
            'id',
            'inspection_id'
        )->distinct();
    }
}