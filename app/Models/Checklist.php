<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    protected $fillable = [
        'main_equipment_id',
        'cell_equipment_id',
        'user_id',
        'visit_date',
        'notes'
    ];

    public function results(): HasMany
    {
        return $this->hasMany(ChecklistResult::class);
    }
}