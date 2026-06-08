<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consumable extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_equipment_id',
        'consumableable_type',
        'consumableable_id',
        "name",
        "other_name",
        "quantity",
        "unit",
        "description",
        "price"
    ];

    protected $casts = [
        "price" => "decimal:2"
    ];

    public function mainEquipment(): BelongsTo
    {
        return $this->belongsTo(MainEquipment::class);
    }
}


