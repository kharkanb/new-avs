<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);



Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-form', function() {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>تست CSRF</title>
    </head>
    <body>
        <form method="POST" action="/test-post">
            <input type="hidden" name="_token" value="'.csrf_token().'">
            <button type="submit">ارسال تست</button>
        </form>
    </body>
    </html>';
});

Route::post('/test-post', function() {
    return '✅ کار می‌کنه!';
});