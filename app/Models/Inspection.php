<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_date',
        'daily_start_time',
        'daily_end_time',
        'contractor',
        'contract_coefficient',
        'contract_number',
        'whatsapp_number',
        'status'
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'daily_start_time' => 'datetime:H:i',
        'daily_end_time' => 'datetime:H:i',
        'contract_coefficient' => 'decimal:2'
    ];

    /**
     * رابطه با تجهیزات اصلی
     * هر بازرسی می‌تونه چندین تجهیز اصلی داشته باشه
     */
    public function mainEquipments(): HasMany
    {
        return $this->hasMany(MainEquipment::class);
    }

    /**
     * (اختیاری) متد برای گرفتن وضعیت به صورت فارسی
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'پیش‌نویس',
            'completed' => 'تکمیل شده',
            'archived' => 'بایگانی شده',
            default => 'نامشخص'
        };
    }
}