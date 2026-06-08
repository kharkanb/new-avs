<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityPrice extends Model
{
    use HasFactory;

    protected $table = "activity_prices";

    protected $fillable = [
        "code",
        "title",
        "unit",
        "unit_price"
    ];

    protected $casts = [
        "unit_price" => "integer"
    ];
}