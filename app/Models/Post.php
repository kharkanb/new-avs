<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'feeders_count',  // اضافه شد
    ];
    
    protected $casts = [
        'feeders_count' => 'integer',
        'deleted_at' => 'datetime',
    ];
    
    // رابطه با فیدرها
    public function feeders()
    {
        return $this->hasMany(Feeder::class);
    }
    
    // رابطه با تجهیزات از طریق فیدرها
    public function equipments()
    {
        return $this->hasManyThrough(MainEquipment::class, Feeder::class, 'post_id', 'id');
    }
    
    // دریافت تعداد فیدرها (به صورت attribute)
    public function getFeedersCountAttribute()
    {
        return $this->feeders()->count();
    }
    
    // به‌روزرسانی خودکار تعداد فیدرها
    protected static function booted()
    {
        static::deleting(function ($post) {
            // جلوگیری از حذف پست اگر فیدر دارد
            if ($post->feeders()->count() > 0) {
                throw new \Exception('این پست دارای فیدر است و قابل حذف نیست');
            }
        });
    }
}