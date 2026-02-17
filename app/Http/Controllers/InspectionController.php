<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    public function store(Request $request)
    {
        try {
            // لاگ برای دیباگ
            Log::info('Data received:', $request->all());
            
            // اعتبارسنجی
            $validated = $request->validate([
                'inspection_date' => 'required',
                'daily_start_time' => 'nullable',
                'daily_end_time' => 'nullable',
                'contractor' => 'required',
                'contract_coefficient' => 'required|numeric',
                'contract_number' => 'nullable',
                'whatsapp_number' => 'nullable',
            ]);

            // ذخیره در دیتابیس
            $inspection = Inspection::create([
                'inspection_date' => $request->inspection_date,
                'daily_start_time' => $request->daily_start_time,
                'daily_end_time' => $request->daily_end_time,
                'contractor' => $request->contractor,
                'contract_coefficient' => $request->contract_coefficient,
                'contract_number' => $request->contract_number,
                'whatsapp_number' => $request->whatsapp_number,
                'equipments_data' => json_encode($request->equipments ?? [])
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ بازدید با موفقیت ثبت شد!',
                'data' => $inspection
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving inspection: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'خطا در ثبت بازدید: ' . $e->getMessage()
            ], 500);
        }
    }
}