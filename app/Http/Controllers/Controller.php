<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API مدیریت تجهیزات",
 *     version="1.0.0",
 *     description="API برای مدیریت بازرسی‌ها و تجهیزات اتوماسیون",
 *     @OA\Contact(
 *         email="admin@avs.com",
 *         name="تیم توسعه"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="سرور محلی"
 * )
 * @OA\Server(
 *     url="https://api.avs.com",
 *     description="سرور اصلی"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="توکن احراز هویت را وارد کنید"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}