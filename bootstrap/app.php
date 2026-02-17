<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReferenceController;
use App\Http\Controllers\Api\InspectionController;
use App\Http\Controllers\Api\MainEquipmentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserManagementController;

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
    
    // ===== احراز هویت =====
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // ===== مدیریت کاربران =====
    Route::apiResource('users', UserManagementController::class);
    Route::post('users/{user}/change-role', [UserManagementController::class, 'changeRole']);
    
    // ===== داشبورد =====
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // ===== منابع پایه (Reference) =====
    Route::prefix('reference')->group(function () {
        Route::get('/main-equipment-types', [ReferenceController::class, 'mainEquipmentTypes']);
        Route::get('/cell-equipment-types', [ReferenceController::class, 'cellEquipmentTypes']);
        Route::get('/brands', [ReferenceController::class, 'brands']);
        Route::get('/departments', [ReferenceController::class, 'departments']);
        Route::get('/posts', [ReferenceController::class, 'posts']);
        Route::get('/feeders', [ReferenceController::class, 'feeders']);
        Route::get('/activity-prices', [ReferenceController::class, 'activityPrices']);
        Route::get('/consumable-items', [ReferenceController::class, 'consumableItems']);
        Route::get('/checklist-templates', [ReferenceController::class, 'checklistTemplates']);
        Route::get('/all', [ReferenceController::class, 'all']);
    });
    
    // ===== بازرسی‌ها =====
    Route::apiResource('inspections', InspectionController::class);
    Route::get('/inspections/{inspection}/equipments', [InspectionController::class, 'equipments']);
    
    // ===== تجهیزات اصلی =====
    Route::apiResource('main-equipments', MainEquipmentController::class);
    Route::prefix('main-equipments/{mainEquipment}')->group(function () {
        Route::get('/cells', [MainEquipmentController::class, 'cells']);
        Route::get('/activities', [MainEquipmentController::class, 'activities']);
        Route::get('/consumables', [MainEquipmentController::class, 'consumables']);
        Route::get('/checklist', [MainEquipmentController::class, 'checklist']);
        Route::get('/photos', [MainEquipmentController::class, 'photos']);
    });
});