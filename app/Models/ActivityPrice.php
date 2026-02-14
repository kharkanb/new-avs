<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityPrice extends Model
{
    use HasFactory;

    protected $table = "activity_price_list";

    protected $fillable = [
        "code",
        "title",
        "unit",
        "price"
    ];

    protected $casts = [
        "price" => "decimal:2"
    ];
}