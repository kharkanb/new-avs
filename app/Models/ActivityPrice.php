<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityPrice extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'activity_prices';
    
    protected $fillable = [
        'code',
        'title',
        'unit',
        'unit_price',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'unit_price' => 'decimal:0'  // ← اصلاح شد
=======
    protected $table = "activity_prices";

    protected $fillable = [
        "code",
        "title",
        "unit",
        "unit_price"
    ];

    protected $casts = [
        "unit_price" => "integer"
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
    ];
}