<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InspectionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inspections', [PaymentController::class, 'inspections']);

Route::get('/inspection-form', function () {
    return view('inspection-form');
});

// اضافه کردن route برای ذخیره فرم
Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');