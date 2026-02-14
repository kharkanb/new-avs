<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        "equipment_id",
        "photo_data",
        "photo_type",
        "photo_path",
        "scan_code",
        "description",
        "uploaded_at"
    ];

    protected $casts = [
        "uploaded_at" => "datetime"
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}


