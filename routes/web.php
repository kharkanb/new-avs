<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FeederController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\MainEquipmentTypeController;
use App\Http\Controllers\CellEquipmentTypeController;
use App\Http\Controllers\ActivityPriceController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\SimcardTypeController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\InspectionWebController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdvancedDashboardController;
use App\Http\Controllers\Reports\FinancialReportController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// صفحات عمومی (بدون احراز هویت)
// ============================================

// صفحه اصلی
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// فرم بازدید
Route::get('/inspection-form', function () {
    return view('inspection-form');
})->name('inspection.form');

// ============================================
// صفحات تست و ابزار
// ============================================

Route::get('/test-chart', function() {
    return view('dashboard.test-chart');
});

Route::get('/test-data', function() {
    $data = DB::table('inspections')
        ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
        ->whereYear('created_at', 2026)
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    
    return response()->json($data);
});


Route::get('/links', [PageController::class, 'links'])->name('links');

Route::get('/test-inspection/{id}', function($id) {
    return "Test ID: " . $id;
})->name('test.inspection');

// ============================================
// مسیرهای اصلی با احراز هویت
// ============================================

Route::middleware(['auth'])->group(function () {
    
    // ============================================
    // مسیر اصلی داشبورد - مهم
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // داشبورد پیشرفته جدید
Route::get('/advanced-dashboard', function(Request $request) {
    return app(AdvancedDashboardController::class)->index($request);
})->middleware(['auth'])->name('advanced.dashboard');
    // ============================================
    // مسیرهای مشاهده و ویرایش بازدید (WEB)
    // ============================================
    Route::get('/inspection/{id}', [InspectionWebController::class, 'show'])->name('inspection.show');
    Route::get('/inspection/{id}/edit', [InspectionWebController::class, 'edit'])->name('inspection.edit');
    Route::get('/my-inspections', [InspectionWebController::class, 'myInspections'])->name('my.inspections');
    Route::get('/dashboard/inspections', [InspectionWebController::class, 'index'])->name('dashboard.inspections');
    Route::delete('/inspections/{id}', [InspectionWebController::class, 'destroy'])->name('inspections.destroy');
    Route::get('/api/advanced-dashboard/monthly', [AdvancedDashboardController::class, 'getMonthlyChartData']);
    Route::get('/api/advanced-dashboard/stats', [AdvancedDashboardController::class, 'getStats']);
    Route::get('/api/contractors/{name}/details', [AdvancedDashboardController::class, 'getContractorDetails']);
Route::get('/advanced-dashboard/export-data', [AdvancedDashboardController::class, 'exportData'])->name('advanced.dashboard.export');
    // ============================================
    // داده‌های نمودار داشبورد
    // ============================================
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');
    
    // ============================================
    // گروه مسیرهای داشبورد (با پیشوند dashboard)
    // ============================================
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        
        // گزارش فعالیت‌ها
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');
        Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
        Route::get('/activity-logs/clear', [ActivityLogController::class, 'clearAll'])->name('activity-logs.clear');
        
        // پروفایل کاربری
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        
        // لیست بازدیدها (از DashboardController)
        Route::get('/inspections-list', [DashboardController::class, 'inspections'])->name('inspections');
        
        // ============================================
        // مدیریت کاربران
        // ============================================
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // ============================================
        // مدیریت پیمانکاران
        // ============================================
        Route::get('/contractors', [ContractorController::class, 'index'])->name('contractors.index');
        Route::get('/contractors/create', [ContractorController::class, 'create'])->name('contractors.create');
        Route::post('/contractors', [ContractorController::class, 'store'])->name('contractors.store');
        Route::get('/contractors/{contractor}/edit', [ContractorController::class, 'edit'])->name('contractors.edit');
        Route::put('/contractors/{contractor}', [ContractorController::class, 'update'])->name('contractors.update');
        Route::delete('/contractors/{contractor}', [ContractorController::class, 'destroy'])->name('contractors.destroy');
        
        // ============================================
        // سایر منابع مدیریتی
        // ============================================
        Route::resource('departments', DepartmentController::class)->except(['show']);
        Route::resource('posts', PostController::class)->except(['show']);
        Route::resource('feeders', FeederController::class)->except(['show']);
        Route::resource('brands', BrandController::class)->except(['show']);
        Route::resource('equipment-types', MainEquipmentTypeController::class)->except(['show']);
        Route::resource('cell-equipment-types', CellEquipmentTypeController::class)->except(['show']);
        Route::resource('activity-prices', ActivityPriceController::class)->except(['show']);
        Route::resource('checklist-items', ChecklistItemController::class)->except(['show']);
        Route::resource('simcard-types', SimcardTypeController::class)->except(['show']);
    });
    
    // ============================================
    // مسیرهای گزارشات
    // ============================================
Route::prefix('reports')->name('reports.')->group(function () {
    // گزارش اصلی
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    Route::get('/checklist-items', [ReportController::class, 'getChecklistItems'])->name('checklist-items');
    Route::get('/details', [ReportController::class, 'getDetails'])->name('details');
    
    // گزارش‌های تحلیلی
    Route::get('/comprehensive', [ReportController::class, 'comprehensive'])->name('comprehensive');
    Route::get('/contractors-report', [ReportController::class, 'contractorsReport'])->name('contractors-report');
    Route::get('/departments-report', [ReportController::class, 'departmentsReport'])->name('departments-report');
    Route::get('/equipment-report', [ReportController::class, 'equipmentReport'])->name('equipment-report');
    Route::get('/failures', [ReportController::class, 'failures'])->name('failures');
    Route::get('/checklist-results', [ReportController::class, 'checklistResults'])->name('checklist-results');
    Route::get('/charts', [ReportController::class, 'charts'])->name('charts');
    Route::get('/advanced', [ReportController::class, 'advanced'])->name('advanced');
    Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
    Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
    
    // گزارش مالی صورت وضعیت (با متدهای صحیح)
    Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
    Route::get('/financial/export', [ReportController::class, 'exportFinancial'])->name('financial.export');
});

});

// ============================================
// مسیرهای احراز هویت (لاگین و لاگاوت)
// ============================================
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ============================================
// فایل احراز هویت (login, register, etc)
// ============================================
require __DIR__.'/auth.php';