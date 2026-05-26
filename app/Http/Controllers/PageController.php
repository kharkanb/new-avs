<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function links()
    {
        // لیست تمام لینک‌های سیستم
        $links = [
            ['url' => '/', 'name' => 'home', 'desc' => 'صفحه خوش‌آمدگویی', 'role' => 'all'],
            ['url' => '/inspection-form', 'name' => 'inspection.form', 'desc' => 'فرم بازدید تجهیزات', 'role' => 'all'],
            ['url' => '/dashboard', 'name' => 'dashboard', 'desc' => 'داشبورد اصلی سیستم', 'role' => 'auth'],
            ['url' => '/dashboard/inspections', 'name' => 'dashboard.inspections', 'desc' => 'لیست تمام بازدیدها', 'role' => 'auth'],
            ['url' => '/dashboard/reports', 'name' => 'dashboard.reports', 'desc' => 'گزارش‌های مدیریتی', 'role' => 'admin,supervisor'],
            ['url' => '/dashboard/users', 'name' => 'dashboard.users', 'desc' => 'مدیریت کاربران سیستم', 'role' => 'admin'],
            ['url' => '/dashboard/profile', 'name' => 'dashboard.profile.edit', 'desc' => 'ویرایش پروفایل کاربری', 'role' => 'auth'],
            
            // مدیریت پیمانکاران
            ['url' => '/dashboard/contractors', 'name' => 'dashboard.contractors', 'desc' => 'مدیریت پیمانکاران', 'role' => 'admin'],
            ['url' => '/dashboard/contractors/create', 'name' => 'dashboard.contractors.create', 'desc' => 'افزودن پیمانکار جدید', 'role' => 'admin'],
            
            // مدیریت امورها
            ['url' => '/dashboard/departments', 'name' => 'dashboard.departments.index', 'desc' => 'مدیریت امور/شهرستان‌ها', 'role' => 'admin'],
            
            // مدیریت پست‌ها و فیدرها
            ['url' => '/dashboard/posts', 'name' => 'dashboard.posts.index', 'desc' => 'مدیریت پست‌های برق', 'role' => 'admin'],
            ['url' => '/dashboard/feeders', 'name' => 'dashboard.feeders.index', 'desc' => 'مدیریت فیدرها', 'role' => 'admin'],
            
            // مدیریت برندها
            ['url' => '/dashboard/brands', 'name' => 'dashboard.brands.index', 'desc' => 'مدیریت برندهای تجهیزات', 'role' => 'admin'],
            
            // مدیریت انواع تجهیزات
            ['url' => '/dashboard/equipment-types', 'name' => 'dashboard.equipment-types.index', 'desc' => 'انواع تجهیزات اصلی', 'role' => 'admin'],
            ['url' => '/dashboard/cell-equipment-types', 'name' => 'dashboard.cell-equipment-types.index', 'desc' => 'انواع تجهیزات سلولی', 'role' => 'admin'],
            
            // مدیریت فهرست بها و چک‌لیست
            ['url' => '/dashboard/activity-prices', 'name' => 'dashboard.activity-prices.index', 'desc' => 'مدیریت فهرست بها', 'role' => 'admin'],
            ['url' => '/dashboard/checklist-items', 'name' => 'dashboard.checklist-items.index', 'desc' => 'مدیریت چک‌لیست‌ها', 'role' => 'admin'],
            ['url' => '/dashboard/simcard-types', 'name' => 'dashboard.simcard-types.index', 'desc' => 'انواع سیم‌کارت', 'role' => 'admin'],
            
            // گزارش‌های جدید
            ['url' => '/reports/comprehensive', 'name' => 'reports.comprehensive', 'desc' => 'گزارش جامع داشبورد', 'role' => 'admin,supervisor'],
            ['url' => '/reports/financial', 'name' => 'reports.financial', 'desc' => 'گزارش مالی صورت وضعیت', 'role' => 'admin,supervisor'],
            ['url' => '/reports/failures', 'name' => 'reports.failures', 'desc' => 'گزارش خرابی‌ها (Not OK)', 'role' => 'admin,supervisor'],
            ['url' => '/reports/contractors-report', 'name' => 'reports.contractors-report', 'desc' => 'گزارش عملکرد پیمانکاران', 'role' => 'admin,supervisor'],
            ['url' => '/reports/departments-report', 'name' => 'reports.departments-report', 'desc' => 'گزارش عملکرد امورها', 'role' => 'admin,supervisor'],
            ['url' => '/reports/equipment-report', 'name' => 'reports.equipment-report', 'desc' => 'گزارش تجهیزات', 'role' => 'admin,supervisor'],
            ['url' => '/reports/checklist-results', 'name' => 'reports.checklist-results', 'desc' => 'گزارش نتایج چک‌لیست', 'role' => 'admin,supervisor'],
            ['url' => '/reports/charts', 'name' => 'reports.charts', 'desc' => 'نمودارهای آماری', 'role' => 'admin,supervisor'],
            ['url' => '/reports/advanced', 'name' => 'reports.advanced', 'desc' => 'گزارش پیشرفته', 'role' => 'admin,supervisor'],
            
            // گزارش‌های قدیمی (سازگاری)
            ['url' => '/reports/daily', 'name' => 'reports.daily', 'desc' => 'گزارش روزانه', 'role' => 'admin,supervisor'],
            ['url' => '/reports/monthly', 'name' => 'reports.monthly', 'desc' => 'گزارش ماهانه', 'role' => 'admin,supervisor'],
            
            // صفحات تست
            ['url' => '/test-page', 'name' => 'test.page', 'desc' => 'صفحه تست سیستم', 'role' => 'all'],
            ['url' => '/links', 'name' => 'links', 'desc' => 'این صفحه - لیست لینک‌ها', 'role' => 'all'],
            ['url' => '/my-inspections', 'name' => 'my.inspections', 'desc' => 'لیست بازدیدهای من', 'role' => 'auth'],
        ];
        
        return view('links', compact('links'));
    }
}