<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReferenceController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\MainEquipmentController;
<<<<<<< HEAD
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\UserManagementController;
use App\Http\Controllers\Api\FormDataController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdvancedDashboardController;
=======
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserManagementController;
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d

// ========== مسیرهای عمومی (بدون نیاز به توکن) ==========
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/ping', function() {
    return response()->json([
        'success' => true,
        'message' => 'API is working',
        'time' => now()->toDateTimeString()
    ]);
});

Route::get('/test', function() {
    return response()->json([
        'message' => 'API is working!',
        'time' => now()
    ]);
}); 

// ========== مسیرهای محافظت شده (نیاز به توکن) ==========
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/form-data', [FormDataController::class, 'getFormData']);

    // مدیریت کاربران
    Route::middleware('admin')->group(function () {
        Route::apiResource('users', UserManagementController::class);
        Route::post('users/{user}/change-role', [UserManagementController::class, 'changeRole']);
    });
    
    // احراز هویت
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // داشبورد
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/advanced-dashboard/monthly', [AdvancedDashboardController::class, 'getMonthlyData']);
    Route::get('/advanced-dashboard/stats', [AdvancedDashboardController::class, 'getStats']);
    Route::get('/advanced-dashboard/contractors', [AdvancedDashboardController::class, 'getContractorData']);

    // منابع پایه (Reference)
    Route::get('/reference/all', [ReferenceController::class, 'getAllReferences']);
    Route::get('/reference/main-equipment-types', [ReferenceController::class, 'mainEquipmentTypes']);
    Route::get('/reference/cell-equipment-types', [ReferenceController::class, 'cellEquipmentTypes']);
    Route::get('/reference/brands', [ReferenceController::class, 'brands']);
    Route::get('/reference/departments', [ReferenceController::class, 'departments']);
    Route::get('/reference/posts', [ReferenceController::class, 'posts']);
    Route::get('/reference/feeders', [ReferenceController::class, 'feeders']);
    Route::get('/reference/activity-prices', [ReferenceController::class, 'activityPrices']);
    Route::get('/reference/consumable-items', [ReferenceController::class, 'consumableItems']);
    Route::get('/reference/checklist-templates', [ReferenceController::class, 'checklistTemplates']);
    Route::get('/reference/form-data', [ReferenceController::class, 'formData']);
    
    // بازرسی‌ها
    Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections.index');
    Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
    Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');
    Route::put('/inspections/{inspection}', [InspectionController::class, 'update'])->name('inspections.update');
    Route::delete('/inspections/{id}', [InspectionController::class, 'destroy'])->name('inspections.destroy');
    Route::get('/inspections/{inspection}/edit', [InspectionController::class, 'edit'])->name('inspections.edit');
    Route::get('/inspections/{inspection}/equipments', [InspectionController::class, 'equipments']);
    
    // تجهیزات اصلی
    Route::apiResource('main-equipments', MainEquipmentController::class);
    Route::get('/main-equipments/{mainEquipment}/cells', [MainEquipmentController::class, 'cells']);
    Route::get('/main-equipments/{mainEquipment}/activities', [MainEquipmentController::class, 'activities']);
    Route::get('/main-equipments/{mainEquipment}/consumables', [MainEquipmentController::class, 'consumables']);
    Route::get('/main-equipments/{mainEquipment}/checklist', [MainEquipmentController::class, 'checklist']);
    Route::get('/main-equipments/{mainEquipment}/photos', [MainEquipmentController::class, 'photos']);
    
    // ========== مسیرهای لاگ (Activity Log) ==========
    Route::get('/logs/{id}', [ActivityLogController::class, 'show']);
    Route::delete('/logs/{id}', [ActivityLogController::class, 'destroy']);
    Route::delete('/logs/clear', [ActivityLogController::class, 'clearAll']);
});

Route::get('/contractors/{name}/details', function($name) {
    $inspections = App\Models\Inspection::where('contractor_name', $name)->get();
    
    return response()->json([
        'inspections_count' => $inspections->count(),
        'total_cost' => $inspections->sum('total_cost'),
        'avg_cost' => $inspections->avg('total_cost'),
        'last_inspection' => $inspections->max('created_at'),
    ]);
})->middleware('auth');


// ========== مسیرهای ساده برای تست ==========
Route::get('/simple-data', function() {
    return response()->json([
        'equipment_types' => \App\Models\EquipmentType::where('category', 'main')->get(),
        'brands' => \App\Models\Brand::limit(10)->get(),
        'departments' => \App\Models\Department::all(),
        'posts' => \App\Models\Post::all(),
        'feeders' => \App\Models\Feeder::limit(20)->get(),
        'activity_prices' => \App\Models\ActivityPrice::limit(10)->get(),
        'consumable_items' => \App\Models\ConsumableItem::all(),
        'contractors' => \App\Models\Contractor::all(),
    ]);
});