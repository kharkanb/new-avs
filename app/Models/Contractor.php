<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $fillable = [
        'name',
        'coefficient',
        'contract_number',
        'phone',
        'whatsapp',
        'address'
    ];

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }
}