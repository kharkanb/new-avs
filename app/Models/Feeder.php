<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feeder extends Model
{
    use HasFactory;

    protected $fillable = ["name", "post_id"];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function mainEquipments(): BelongsToMany
    {
        return $this->belongsToMany(MainEquipment::class, 'main_equipment_feeder')
                    ->withTimestamps();
    }

    public function equipments(): BelongsToMany
    {
        return $this->mainEquipments();
    }
}