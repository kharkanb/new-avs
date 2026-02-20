<?php

namespace App\Http\Controllers\Api;  // اینجا باید Api باشه

use App\Http\Controllers\Controller;  // اینو بدون تغییر نگه دار
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InspectionController extends Controller  // اینم همونطور بمونه
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inspection_date' => 'required|string',
            'contractor' => 'required|string',
            'contract_coefficient' => 'required|numeric',
            'daily_start_time' => 'nullable|string',
            'daily_end_time' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'equipments' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // ذخیره اطلاعات اصلی بازدید
            $inspection = Inspection::create([
                'inspection_date' => $request->inspection_date,
                'contractor' => $request->contractor,
                'contract_coefficient' => $request->contract_coefficient,
                'contract_number' => $request->contract_number ?? '.../.../.../...',
                'daily_start_time' => $request->daily_start_time,
                'daily_end_time' => $request->daily_end_time,
                'whatsapp_number' => $request->whatsapp_number,
                'status' => 'completed'
            ]);

            // ذخیره تجهیزات
            foreach ($request->equipments as $equipmentData) {
                // پیدا کردن یا ایجاد نوع تجهیز
                $equipmentType = MainEquipmentType::firstOrCreate(
                    ['name' => $equipmentData['equipmentType']],
                    [
                        'feeder_mode' => in_array($equipmentData['equipmentType'], [
                            'پست دو سو تغذیه (مشترک حساس)',
                            'پست دو سو تغذیه (بیمارستانی)'
                        ]) ? 'dual' : 'single',
                        'has_cells' => in_array($equipmentData['equipmentType'], [
                            'پست دو سو تغذیه (مشترک حساس)',
                            'پست دو سو تغذیه (بیمارستانی)',
                            'مشترک ولتاژ اولیه'
                        ]),
                        'has_brand' => in_array($equipmentData['equipmentType'], [
                            'ریکلوزر', 'سکسیونر', 'سکشنالایزر', 'فالت دتکتور'
                        ]),
                        'has_height' => !in_array($equipmentData['equipmentType'], [
                            'پست دو سو تغذیه (مشترک حساس)',
                            'پست دو سو تغذیه (بیمارستانی)',
                            'مشترک ولتاژ اولیه'
                        ])
                    ]
                );

                // آماده‌سازی داده‌های موقعیت
                $locationArray = $equipmentData['locationData'] ? json_decode($equipmentData['locationData'], true) : [];

                // پیدا کردن post از اولین feeder
                $postId = null;
                if (!empty($equipmentData['feeders'])) {
                    $feedersArray = json_decode($equipmentData['feeders'], true);
                    if (!empty($feedersArray) && isset($feedersArray[0]['post'])) {
                        $post = Post::where('name', $feedersArray[0]['post'])->first();
                        $postId = $post ? $post->id : null;
                    }
                }


                // ایجاد تجهیز جدید
                $equipment = new MainEquipment([
                    'main_equipment_type_id' => $equipmentType->id,
                    'post_id' => $postId,
                    'scada_code' => $equipmentData['scadaCode'] ?? null,
                    'installation_type' => $equipmentData['installationType'] ?? null,
                    'latitude' => $locationArray['latitude'] ?? null,
                    'longitude' => $locationArray['longitude'] ?? null,
                    'height' => $locationArray['cabinetFinalHeight'] ?? null,
                    'feeders' => $equipmentData['feeders'] ?? null,
                    'department_data' => $equipmentData['departmentData'] ?? null,
                    'location_data' => $equipmentData['locationData'] ?? null,
                    'communication_data' => $equipmentData['communicationData'] ?? null,
                    'checklist_data' => $equipmentData['checklistData'] ?? null,
                    'activities_data' => $equipmentData['activitiesData'] ?? null,
                    'consumables_data' => $equipmentData['consumablesData'] ?? null,
                    'photos_data' => $equipmentData['photosData'] ?? null,
                    'cell_specs' => $equipmentData['cellSpecs'] ?? null,
                    'tabs_validated' => $equipmentData['tabsValidated'] ?? null
                ]);

                $inspection->mainEquipments()->save($equipment);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'بازرسی با موفقیت ثبت شد',
                'data' => $inspection->load('mainEquipments')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'خطا در ثبت اطلاعات: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $inspections = Inspection::with('mainEquipments')->orderBy('created_at', 'desc')->get();
        return response()->json($inspections);
    }

    public function show($id)
    {
        $inspection = Inspection::with('mainEquipments')->find($id);
        if (!$inspection) {
            return response()->json(['message' => 'یافت نشد'], 404);
        }
        return response()->json($inspection);
    }
}