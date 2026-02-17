<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/test', function() {
    return response()->json(['message' => 'API is working']);
});

Route::post('/test-post', [PaymentController::class, 'testPost']);
Route::post('/callback/mobile', [PaymentController::class, 'mobileCallback']);
Route::get('/inspections', [PaymentController::class, 'inspections']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});