<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function store(Request $request)
    {
        try {
            // اعتبارسنجی
            $validated = $request->validate([
                'inspection_date' => 'required',
                'daily_start_time' => 'nullable',
                'daily_end_time' => 'nullable',
                'contractor' => 'required',
                'contract_coefficient' => 'required|numeric',
                'contract_number' => 'nullable',
                'whatsapp_number' => 'nullable',
                'equipments' => 'nullable|array',
                'activitiesData' => 'nullable|array',
                'consumablesData' => 'nullable|array'
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
                'equipments_data' => $request->equipments,
                'activities_data' => $request->activitiesData,
                'consumables_data' => $request->consumablesData
            ]);

            return response()->json([
                'success' => true,
                'message' => 'بازدید با موفقیت ثبت شد',
                'data' => $inspection
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ثبت بازدید',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}