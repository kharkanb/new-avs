<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItem extends Model
{
    use SoftDeletes;

 protected $table = 'checklist_items';
    
    protected $fillable = [
        'checklist_template_id',
        'item_text',
        'sort_order'
    ];
    
    protected $casts = [
        'sort_order' => 'integer'
    ];
    
    public function checklistTemplate(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }
}
