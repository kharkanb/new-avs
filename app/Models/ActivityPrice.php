<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityPrice extends Model
{
    use HasFactory;

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
    ];
}