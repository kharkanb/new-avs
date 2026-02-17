<?php

use Illuminate\Support\Facades\Route;

// ===============================================
// تست‌های CSRF
// ===============================================

// تست 1: نمایش فرم تست CSRF
Route::get('/test', function() {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>تست CSRF</title>
        <style>
            body { font-family: Tahoma; direction: rtl; padding: 20px; }
            .success { color: green; }
            .error { color: red; }
        </style>
    </head>
    <body>
        <h3>📋 تست CSRF</h3>
        <form method="POST" action="/test">
            <input type="hidden" name="_token" value="'.csrf_token().'">
            <input type="text" name="test_field" value="test_value">
            <button type="submit">ارسال تست CSRF</button>
        </form>
    </body>
    </html>';
});

// تست 2: دریافت نتیجه تست CSRF
Route::post('/test', function() {
    return '<h3 class="success">✅ تست CSRF با موفقیت انجام شد!</h3>';
});

// تست 3: تست ساده JSON
Route::post('/test-simple', function() {
    return response()->json([
        'success' => true,
        'message' => '✅ تست ساده با موفقیت انجام شد!',
        'data' => request()->all(),
        'method' => request()->method(),
        'timestamp' => now()->toDateTimeString()
    ]);
})->name('test.simple');

// تست 4: تست با پارامتر
Route::post('/test-with-params', function() {
    $test = request('test', 'مقدار پیش‌فرض');
    return response()->json([
        'success' => true,
        'message' => "✅ مقدار دریافتی: $test",
        'all_data' => request()->all()
    ]);
})->name('test.params');

// تست 5: تست GET ساده
Route::get('/test-get', function() {
    return view('inspection-form');
})->name('test.get');

// ===============================================
// فرم اصلی بازدید
// ===============================================

// نمایش فرم بازدید (GET)
Route::get('/inspections', function() {
    return view('inspection-form');
})->name('inspections.create');

// دریافت داده‌های فرم بازدید (POST)
Route::post('/inspections', function() {
    return response()->json([
        'success' => true,
        'message' => '✅ فرم بازدید با موفقیت ارسال شد!',
        'received_data' => request()->except('_token'),
        'timestamp' => now()->toDateTimeString()
    ]);
})->name('inspections.store');

// ===============================================
// تست‌های کنسول (برای PowerShell)
// ===============================================

// تست 6: برای PowerShell (بدون CSRF - موقت)
Route::post('/api/test', function() {
    return response()->json([
        'success' => true,
        'message' => '✅ تست PowerShell موفق بود',
        'data' => request()->all()
    ]);
});

// تست 7: بررسی health
Route::get('/health', function() {
    return response()->json([
        'status' => 'healthy',
        'laravel_version' => app()->version(),
        'php_version' => PHP_VERSION,
        'timestamp' => now()->toDateTimeString()
    ]);
});