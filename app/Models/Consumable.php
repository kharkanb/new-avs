<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consumable extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_equipment_id',  // ✅ این خط رو اضافه کن
        "equipment_id",
        "item_name",
        "quantity",
        "unit",
        "description",
        "other_name"
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}


