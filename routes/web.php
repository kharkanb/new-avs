<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspectionController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==================== صفحه اصلی ====================
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// ==================== مسیرهای فرم بازدید ====================
// نمایش فرم بازدید
Route::get('/inspections', function() {
    return view('inspection-form');
})->name('inspections.create');

// دریافت داده‌های فرم بازدید (POST)
Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');

// ==================== مسیرهای تست ====================
Route::get('/test-page', function() {
    return '<h1>کار میکنه</h1>';
});

Route::get('/inspection-form', function () {
    return view('inspection-form');
})->name('inspection.form');

// ==================== مسیرهای احراز هویت (Breeze) ====================
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// این فایل شامل routeهای مربوط به login, register, password reset و ... است
require __DIR__.'/auth.php';