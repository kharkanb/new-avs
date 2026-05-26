<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description', 'level'];
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
    
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}