<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feeder extends Model
{
    use HasFactory;

    protected $fillable = ["name", "post_id"];

    // رابطه با پست
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

<<<<<<< HEAD
    // رابطه با تجهیزات
    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(MainEquipment::class, "equipment_feeders", "feeder_id", "main_equipment_id")
                    ->withTimestamps();
    }
    
    // به‌روزرسانی خودکار تعداد فیدرها در پست
    protected static function booted()
    {
        static::saved(function ($feeder) {
            if ($feeder->post_id) {
                $post = Post::find($feeder->post_id);
                if ($post && !$post->trashed()) {
                    $post->update(['feeders_count' => $post->feeders()->count()]);
                }
            }
        });
        
        static::deleted(function ($feeder) {
            if ($feeder->post_id) {
                $post = Post::find($feeder->post_id);
                if ($post && !$post->trashed()) {
                    $post->update(['feeders_count' => $post->feeders()->count()]);
                }
            }
        });
=======
    public function mainEquipments(): BelongsToMany
    {
        return $this->belongsToMany(MainEquipment::class, 'main_equipment_feeder')
                    ->withTimestamps();
    }

    public function equipments(): BelongsToMany
    {
        return $this->mainEquipments();
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
    }
}