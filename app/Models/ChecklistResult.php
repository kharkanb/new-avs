<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistResult extends Model
{
    protected $fillable = [
        'checklist_id',
        'checklist_item_id',
        'status',
        'description'
    ];
}