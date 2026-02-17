<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function testPost(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Test successful',
            'data' => $request->all()
        ]);
    }

    public function mobileCallback(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Mobile callback received',
            'data' => $request->all()
        ]);
    }

   public function inspections(Request $request)
{
    try {
        // اعتبارسنجی داده‌های دریافتی
        $validated = $request->validate([
            'inspection_date' => 'required|string',
            'contractor' => 'required|string',
            'contract_coefficient' => 'required|numeric',
            'daily_start_time' => 'required',
            'daily_end_time' => 'required',
            'whatsapp_number' => 'nullable|string',
            'contract_number' => 'nullable|string',
            'equipments' => 'sometimes|array'
        ]);

        // اینجا می‌توانید داده‌ها را در دیتابیس ذخیره کنید
        // ...

        return response()->json([
            'success' => true,
            'message' => 'بازدید با موفقیت ثبت شد',
            'data' => $validated
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطا در اعتبارسنجی داده‌ها',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطا در ثبت بازدید: ' . $e->getMessage()
        ], 500);
    }
}
}