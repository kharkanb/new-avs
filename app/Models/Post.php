<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function feeders(): HasMany
    {
        return $this->hasMany(Feeder::class);
    }

    public function mainEquipments(): HasMany
    {
        return $this->hasMany(MainEquipment::class);
    }
}