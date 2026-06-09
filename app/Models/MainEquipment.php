<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainEquipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'main_equipments';

    protected $fillable = [
        'inspection_id',
        'main_equipment_type_id',
        'cell_equipment_type_id',
        'brand_id',
        'post_id',
        'scada_code',
        'communication_id',
        'department_id',
        'status',
        'notes',
        'location_description',
        'power_source',
        'voltage_level',
        'installation_date',
        'last_maintenance_date',
        'manufacturer',
        'model',
        'serial_number',
        'warranty_expiry',
        'latitude',
        'longitude',
        'height',
        'installation_type',
        'other_brand',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'last_maintenance_date' => 'date',
        'warranty_expiry' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'height' => 'float',
    ];

    // ========== روابط ==========

    /**
     * رابطه با بازدید (هر تجهیز متعلق به یک بازدید است)
     */
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * رابطه با نوع تجهیزات اصلی (MainEquipmentType)
     */
    public function mainEquipmentType()
    {
        return $this->belongsTo(MainEquipmentType::class, 'main_equipment_type_id');
    }

    /**
     * نام مستعار برای mainEquipmentType (برای سازگاری با کدهای قدیمی)
     */
    public function type()
    {
        return $this->belongsTo(MainEquipmentType::class, 'main_equipment_type_id');
    }

    /**
     * رابطه با نوع تجهیزات سلولی (CellEquipmentType)
     */
    public function cellEquipmentType()
    {
        return $this->belongsTo(CellEquipmentType::class, 'cell_equipment_type_id');
    }

    /**
     * رابطه با برند
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * رابطه با موقعیت (پست) - از طریق post_id
     */
    public function location()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * نام مستعار برای location
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * رابطه با تجهیزات ارتباطی (از طریق communication_id)
     */
    public function communication()
    {
        return $this->belongsTo(Communication::class, 'communication_id');
    }

    /**
     * رابطه با دپارتمان (بخش)
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * رابطه با چک‌لیست‌ها
     */
    public function checklists()
    {
        return $this->hasMany(EquipmentChecklist::class);
    }

    /**
     * رابطه با فعالیت‌ها
     */
    public function activities()
    {
        return $this->hasMany(EquipmentActivity::class);
    }

    /**
     * رابطه با مصرفی‌ها
     */
    public function consumables()
    {
        return $this->hasMany(EquipmentConsumable::class);
    }

    // ========== روابط با جداول جانبی (مختصات، ارتباطات، فیدر) ==========

    /**
     * رابطه با موقعیت جغرافیایی (EquipmentLocation)
     */
    public function equipmentLocation()
    {
        return $this->hasOne(EquipmentLocation::class, 'main_equipment_id');
    }

    /**
     * رابطه با اطلاعات ارتباطات (EquipmentCommunication)
     */
    public function equipmentCommunication()
    {
        return $this->hasOne(EquipmentCommunication::class, 'main_equipment_id');
    }

    /**
     * رابطه با اطلاعات فیدرها (EquipmentFeeder)
     */
    public function equipmentFeeders()
    {
        return $this->hasMany(EquipmentFeeder::class, 'main_equipment_id');
    }

    // ========== سکوپ‌ها ==========

    /**
     * سکوپ برای فیلتر بر اساس وضعیت
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * سکوپ برای تجهیزات سالم
     */
    public function scopeOk($query)
    {
        return $query->where('status', 'OK');
    }

    /**
     * سکوپ برای تجهیزات خراب
     */
    public function scopeNotOk($query)
    {
        return $query->where('status', 'Not OK');
    }

    /**
     * سکوپ برای فیلتر بر اساس دپارتمان
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * سکوپ برای فیلتر بر اساس برند
     */
    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    /**
     * سکوپ برای جستجو بر اساس کد اسکادا
     */
    public function scopeByScadaCode($query, $scadaCode)
    {
        return $query->where('scada_code', 'LIKE', "%{$scadaCode}%");
    }

    // ========== متدهای کمکی ==========

    /**
     * آیا تجهیز سالم است؟
     */
    public function isOk()
    {
        return $this->status === 'OK';
    }

    /**
     * آیا تجهیز خراب است؟
     */
    public function isNotOk()
    {
        return $this->status === 'Not OK';
    }

    /**
     * دریافت نام دپارتمان
     */
    public function getDepartmentNameAttribute()
    {
        return $this->department?->name ?? '-';
    }

    /**
     * دریافت نام موقعیت (پست)
     */
    public function getLocationNameAttribute()
    {
        return $this->location?->name ?? '-';
    }

    /**
     * دریافت نام برند
     */
    public function getBrandNameAttribute()
    {
        return $this->brand?->name ?? $this->other_brand ?? '-';
    }

    /**
     * دریافت عرض جغرافیایی از جدول equipment_locations
     */
    public function getLatitudeAttribute()
    {
        return $this->equipmentLocation?->latitude ?? null;
    }

    /**
     * دریافت طول جغرافیایی از جدول equipment_locations
     */
    public function getLongitudeAttribute()
    {
        return $this->equipmentLocation?->longitude ?? null;
    }

    /**
     * دریافت آدرس از جدول equipment_locations
     */
    public function getAddressAttribute()
    {
        return $this->equipmentLocation?->address ?? '-';
    }

    /**
     * دریافت وضعیت سیگنال از جدول equipment_communications
     */
    public function getSignalStatusAttribute()
    {
        return $this->equipmentCommunication?->signal_status ?? '-';
    }

    /**
     * دریافت وضعیت مودم از جدول equipment_communications
     */
    public function getModemStatusAttribute()
    {
        return $this->equipmentCommunication?->modem_power ?? '-';
    }

    /**
     * دریافت نام کامل تجهیز
     */
    public function getFullNameAttribute()
    {
        $type = $this->mainEquipmentType?->name ?? 'نامشخص';
        $code = $this->scada_code ? " ({$this->scada_code})" : '';
        return $type . $code;
    }

    /**
     * دریافت نام کامل به همراه موقعیت
     */
    public function getFullNameWithLocationAttribute()
    {
        $type = $this->mainEquipmentType?->name ?? 'نامشخص';
        $code = $this->scada_code ? " ({$this->scada_code})" : '';
        $location = $this->location?->name ?? '';
        return trim($type . $code . ($location ? " - {$location}" : ''));
    }
}