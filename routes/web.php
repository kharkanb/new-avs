<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Tech\TechController;
use App\Http\Controllers\User\UserController;

// ========== مسیرهای عمومی ==========
Route::get('/', function () {
    return view('welcome');
});

// ========== مسیرهای احراز هویت ==========
require __DIR__.'/auth.php';

// ========== مسیرهای محافظت شده ==========
Route::middleware(['auth'])->group(function () {
    
    // ===== مسیرهای ادمین =====
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', UserManagementController::class);
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });
    
    // ===== مسیرهای کارشناس فنی =====
    Route::middleware(['tech'])->prefix('tech')->name('tech.')->group(function () {
        Route::get('/dashboard', [TechController::class, 'dashboard'])->name('dashboard');
        Route::resource('inspections', InspectionController::class);
        Route::resource('equipments', MainEquipmentController::class);
    });
    
    // ===== مسیرهای کاربر عادی =====
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/inspections/{id}', [UserController::class, 'showInspection'])->name('inspection.show');
    });
});