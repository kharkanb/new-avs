<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionLog extends Model
{
    use HasFactory;

    protected $table = "inspection_logs";

    protected $fillable = [
        "inspection_id",
        "user_action",
        "description",
        "log_data"
    ];

    protected $casts = [
        "log_data" => "array"
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}


