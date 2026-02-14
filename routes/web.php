<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-user', function() {
    $user = User::where('email', 'admin@avs.com')->first();
    if ($user) {
        return response()->json([
            'status' => 'success',
            'message' => 'کاربر یافت شد',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
    return response()->json([
        'status' => 'error',
        'message' => 'کاربر یافت نشد'
    ]);
});

Route::get('/test-users', function() {
    $users = User::all();
    return response()->json([
        'status' => 'success',
        'count' => $users->count(),
        'users' => $users
    ]);
});

Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'message' => 'اتصال به دیتابیس برقرار است'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'خطا در اتصال به دیتابیس: ' . $e->getMessage()
        ]);
    }
});