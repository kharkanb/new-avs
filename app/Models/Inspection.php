<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hekmatinasser\Verta\Verta;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Inspection extends Model
{
    use LogsActivity;  // ✅ اینجا باید باشد (بعد از class declaration)
    
    protected $table = 'inspections';
    
    // ✅ فقط یک بار $fillable تعریف شود
    protected $fillable = [
        'user_id',
        'contractor_id',
        'contractor_name',
        'user_name',
        'inspection_date',
        'daily_start_time',
        'daily_end_time',
        'contractor',
        'contract_coefficient',
        'contract_number',
        'whatsapp_number',
        'status',
        'report_number',
        'final_status',
        'total_cost',
        'travel_distance',
        'department_id',
        'travel_cost',
        //  'equipments_data', // اگر فیلد JSON دارید
    ];

    protected $casts = [
        'inspection_date' => 'date',
    ];
    
    // ============================================
    // روابط (Relationships)
    // ============================================

// Accessor برای دریافت نام پیمانکار
public function getContractorDisplayNameAttribute()
{
    // اگر contractor_name مستقیم وجود دارد
    if (!empty($this->contractor_name)) {
        return $this->contractor_name;
    }
    
    // اگر contractor به صورت آبجکت است
    if (is_object($this->contractor) && isset($this->contractor->name)) {
        return $this->contractor->name;
    }
    
    // اگر contractor به صورت JSON string است
    if (is_string($this->contractor) && !empty($this->contractor)) {
        try {
            $data = json_decode($this->contractor, true);
            if (isset($data['name'])) {
                return $data['name'];
            }
            if (isset($data[0]['name'])) {
                return $data[0]['name'];
            }
        } catch (\Exception $e) {}
    }
    
    // اگر از طریق رابطه است
    if ($this->contractor_id && $this->contractor) {
        return $this->contractor->name;
    }
    
    return '-';
}

    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mainEquipments()
    {
        return $this->hasMany(MainEquipment::class);
    }
    
    // متد کمکی برای دسترسی به تجهیزات
    public function getEquipmentsAttribute()
    {
        // اگر داده تجهیزات در فیلد JSON ذخیره شده
        return $this->equipments_data ?? [];
    }
    
    // اگر رابطه جداگانه با جدول equipments دارید
    // public function equipments()
    // {
    //     return $this->hasMany(Equipment::class);
    // }


    // ============================================
    // Accessors (تبدیل تاریخ به شمسی)
    // ============================================
    
    public function getJalaliDateAttribute()
    {
        return Verta::instance($this->inspection_date)->format('Y/m/d');
    }

    public function getJalaliCreatedAtAttribute()
    {
        return Verta::instance($this->created_at)->format('Y/m/d H:i');
    }
    
    // ============================================
    // Activity Log Configuration
    // ============================================
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => $eventName);
    }

    // این متد برای اضافه کردن IP به لاگ خودکار استفاده می‌شود
    public function tapActivity($activity, string $eventName)
    {
        $activity->properties = $activity->properties->merge([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}