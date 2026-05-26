<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deleted_at' => 'datetime',
    ];

    // ============================================
    // روابط اصلی
    // ============================================
    
    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    // ============================================
    // متدهای بررسی نقش و دسترسی
    // ============================================

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }

    public function hasRole($roleName)
    {
        if (method_exists($this, 'hasRoleFromSpatie') && $this->hasRoleFromSpatie($roleName)) {
            return true;
        }
        return $this->role === $roleName;
    }

    public function hasPermission($permissionName)
    {
        if (method_exists($this, 'hasPermissionTo') && $this->hasPermissionTo($permissionName)) {
            return true;
        }
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionName)) {
                return true;
            }
        }
        return false;
    }

    // ============================================
    // متدهای لاگ‌گیری
    // ============================================

public function logActivity($action, $targetType, $targetId, $oldData = null, $newData = null)
{
    // دریافت IP و UserAgent از درخواست جاری
    $ip = request()->ip();
    $userAgent = request()->userAgent();
    
    // اگر در محیط کنسول یا Queue بود، از مقادیر پیش‌فرض استفاده کن
    if (empty($ip) || $ip == '127.0.0.1') {
        $ip = $_SERVER['REMOTE_ADDR'] ?? request()->server('REMOTE_ADDR') ?? '0.0.0.0';
    }
    
    activity()
        ->causedBy($this)
        ->performedOn($targetType::find($targetId))
        ->withProperties([
            'user_name' => $this->name,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => $ip,
            'user_agent' => $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        ])
        ->log($action);
}
    
    // ============================================
    // Spatie Activity Log Configuration
    // ============================================
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}