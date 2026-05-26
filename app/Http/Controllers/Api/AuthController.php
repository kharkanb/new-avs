<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(
 *     title="API مدیریت تجهیزات",
 *     version="1.0.0",
 *     description="API برای مدیریت بازرسی‌ها و تجهیزات اتوماسیون"
 * )
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="سرور محلی"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="ورود به سیستم",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@avs.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ورود موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="اطلاعات نامعتبر")
     * )
     */
    public function login(Request $request)
    {
        // اعتبارسنجی ورودی
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // پیدا کردن کاربر با ایمیل
        $user = User::where('email', $request->email)->first();

        // بررسی وجود کاربر و صحت رمز عبور
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'ایمیل یا رمز عبور اشتباه است'
            ], 401);
        }

        // ایجاد توکن
        $token = $user->createToken('auth-token')->plainTextToken;

        // برگرداندن پاسخ موفق
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user',
            ]
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="خروج از سیستم",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="خروج موفق")
     * )
     */
    public function logout(Request $request)
    {
        // حذف توکن فعلی
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'با موفقیت خارج شدید'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     summary="اطلاعات کاربر جاری",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="اطلاعات کاربر")
     * )
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }
}