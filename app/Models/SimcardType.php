<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimcardType extends Model
{
    use SoftDeletes;
    
    protected $table = 'simcard_types';
    
    protected $fillable = [
        'name',
        'code',
        'description'
    ];
}