<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MainEquipment extends Model
{
    use HasFactory;

    protected $table = 'main_equipments';

    protected $fillable = [
        'inspection_id',
        'main_equipment_type_id',  // ✅ این خط رو اضافه کن
        'scada_code',
        'post_id',
        'latitude',
        'longitude',
        'height',
        'installation_type'
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'height' => 'integer'
    ];

    // روابط
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MainEquipmentType::class, 'main_equipment_type_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function feeders(): BelongsToMany
    {
        return $this->belongsToMany(Feeder::class, 'main_equipment_feeder')
                    ->withTimestamps();
    }

    public function cells(): HasMany
    {
        return $this->hasMany(CellSpecification::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function consumables(): HasMany
    {
        return $this->hasMany(Consumable::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}