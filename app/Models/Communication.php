<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Communication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'communications';

    protected $fillable = [
        'name',
        'type',
        'description',
        'status',
    ];

    /**
     * رابطه با تجهیزات اصلی
     */
    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class, 'communication_id');
    }
}