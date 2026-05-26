<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; use Illuminate\Pagination\Paginator;
use App\Models\Inspection;
use Hekmatinasser\Verta\Verta;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        // Blade directives برای بررسی نقش و دسترسی
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });
        
        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        // تنظیم پیجینیشن با Bootstrap 5
        Paginator::useBootstrapFive();
        
        // اضافه کردن متد toJalali به مدل Inspection
        Inspection::retrieved(function($inspection) {
            if ($inspection->inspection_date) {
                $inspection->jalali_date = verta($inspection->inspection_date)->format('Y/m/d');
            }
        });
    }
}