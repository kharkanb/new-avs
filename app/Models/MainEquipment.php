<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MainEquipment extends Model
{
    protected $fillable = [
        'inspection_id',
        'type_id',
        'scada_code',
        'height',
        'brand_id',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'height' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function feeder(): BelongsTo
    {
        return $this->belongsTo(Feeder::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function cells(): HasMany
    {
        return $this->hasMany(CellEquipment::class);
    }
}