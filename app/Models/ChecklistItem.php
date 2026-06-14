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
<<<<<<< HEAD
        'checklist_template_id',
        'item_text',
        'sort_order'
=======
        "main_equipment_id",
        "checklist_template_item_id",
        "status",
        "description"
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
    ];
    
    protected $casts = [
        'sort_order' => 'integer'
    ];
<<<<<<< HEAD
    
    public function checklistTemplate(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
=======

    public function mainEquipment(): BelongsTo
    {
        return $this->belongsTo(MainEquipment::class);
    }

    public function templateItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplateItem::class, 'checklist_template_item_id');
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
    }
}
