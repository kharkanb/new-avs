<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReferenceController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\MainEquipmentController;
use App\Http\Controllers\Api\DashboardController;

// ========== مسیرهای عمومی (بدون نیاز به توکن) ==========
Route::post('/login', [AuthController::class, 'login']);
Route::get('/ping', function() {
    return response()->json([
        'success' => true,
        'message' => 'API is working',
        'time' => now()->toDateTimeString()
    ]);
});

// ========== مسیرهای محافظت شده (نیاز به توکن) ==========
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('users', UserManagementController::class);
    Route::post('users/{user}/change-role', [UserManagementController::class, 'changeRole']);
    
    // احراز هویت
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // داشبورد
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // منابع پایه (Reference)
    Route::get('/reference/main-equipment-types', [ReferenceController::class, 'mainEquipmentTypes']);
    Route::get('/reference/cell-equipment-types', [ReferenceController::class, 'cellEquipmentTypes']);
    Route::get('/reference/brands', [ReferenceController::class, 'brands']);
    Route::get('/reference/departments', [ReferenceController::class, 'departments']);
    Route::get('/reference/posts', [ReferenceController::class, 'posts']);
    Route::get('/reference/feeders', [ReferenceController::class, 'feeders']);
    Route::get('/reference/activity-prices', [ReferenceController::class, 'activityPrices']);
    Route::get('/reference/consumable-items', [ReferenceController::class, 'consumableItems']);
    Route::get('/reference/checklist-templates', [ReferenceController::class, 'checklistTemplates']);
    Route::get('/reference/all', [ReferenceController::class, 'all']);
    
    // بازرسی‌ها
    Route::apiResource('inspections', InspectionController::class);
    
    // ✅ مسیر دریافت تجهیزات یک بازرسی (اینو اضافه کن)
    Route::get('/inspections/{inspection}/equipments', [InspectionController::class, 'equipments']);
    
    // تجهیزات اصلی
    Route::apiResource('main-equipments', MainEquipmentController::class);
    Route::get('/main-equipments/{mainEquipment}/cells', [MainEquipmentController::class, 'cells']);
    Route::get('/main-equipments/{mainEquipment}/activities', [MainEquipmentController::class, 'activities']);
    Route::get('/main-equipments/{mainEquipment}/consumables', [MainEquipmentController::class, 'consumables']);
    Route::get('/main-equipments/{mainEquipment}/checklist', [MainEquipmentController::class, 'checklist']);
    Route::get('/main-equipments/{mainEquipment}/photos', [MainEquipmentController::class, 'photos']);
});