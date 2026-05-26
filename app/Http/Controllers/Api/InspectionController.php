<?php

namespace App\Http\Controllers\Api;  // اینجا باید Api باشه

use App\Http\Controllers\Controller;  // اینو بدون تغییر نگه دار
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\Post;
<<<<<<< HEAD
use App\Models\Department;
use App\Models\EquipmentFeeder;
use App\Models\EquipmentLocation;
use App\Models\EquipmentCommunication;
use App\Models\EquipmentChecklist;
use App\Models\EquipmentActivity;
use App\Models\EquipmentConsumable;
use App\Models\EquipmentPhoto;
=======
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InspectionController extends Controller  // اینم همونطور بمونه
{
    public function store(Request $request)
    {
<<<<<<< HEAD
\Log::info('Inspection store request:', $request->all());
=======
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
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

<<<<<<< HEAD
            // دریافت department_id از اولین تجهیز
            $departmentId = null;
            if (!empty($request->equipments[0]['departmentData']['department'])) {
                $department = Department::firstOrCreate(
                    ['name' => $request->equipments[0]['departmentData']['department']]
                );
                $departmentId = $department->id;
            }

            // ذخیره اطلاعات اصلی بازدید
            $inspection = Inspection::create([
                'user_id' => auth()->id() ?? $request->user_id,
                'user_name' => auth()->user()->name ?? $request->user_name,
                'contractor_id' => $request->contractor_id,
                'contractor_name' => $request->contractor_name,
                'department_id' => $departmentId,
                'inspection_date' => $request->inspection_date,
                'daily_start_time' => $request->daily_start_time,
                'daily_end_time' => $request->daily_end_time,
                'contractor' => $request->contractor,
                'contract_coefficient' => $request->contract_coefficient,
                'contract_number' => $request->contract_number,
                'whatsapp_number' => $request->whatsapp_number,
                'status' => 'completed',
                'final_status' => 'approved',
=======
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
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
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

<<<<<<< HEAD
                // پیدا کردن post از اولین feeder
                $postId = null;
                if (!empty($equipmentData['feeders']) && is_array($equipmentData['feeders'])) {
                    if (!empty($equipmentData['feeders'][0]['post'])) {
                        $post = Post::firstOrCreate(
                            ['name' => $equipmentData['feeders'][0]['post']]
                        );
                        $postId = $post->id;
                    }
                }

                // پیدا کردن department از داده‌های تجهیز
                $equipDepartmentId = null;
                if (!empty($equipmentData['departmentData']['department'])) {
                    $dept = Department::firstOrCreate(
                        ['name' => $equipmentData['departmentData']['department']]
                    );
                    $equipDepartmentId = $dept->id;
                }

                // ایجاد تجهیز اصلی
                $equipment = new MainEquipment([
                    'inspection_id' => $inspection->id,
                    'main_equipment_type_id' => $equipmentType->id,
                    'scada_code' => $equipmentData['scadaCode'] ?? null,
                    'installation_type' => $equipmentData['installationType'] ?? null,
                    'post_id' => $postId,
                    'department_id' => $equipmentData['departmentData']['department_id'] ?? null,
                    'brand_id' => $equipmentData['brand_id'] ?? null,
                    'latitude' => $equipmentData['locationData']['latitude'] ?? null,
                    'longitude' => $equipmentData['locationData']['longitude'] ?? null,
                    'height' => $equipmentData['locationData']['cabinetFinalHeight'] ?? null,
                ]);

                $equipment->save();

                // ذخیره موقعیت مکانی
                if (!empty($equipmentData['locationData'])) {
                    $location = $equipmentData['locationData'];
                    
                    $equipment->update([
                        'latitude' => $location['latitude'] ?? null,
                        'longitude' => $location['longitude'] ?? null,
                        'height' => $location['cabinetFinalHeight'] ?? null,
                    ]);
                    
                    EquipmentLocation::create([
                        'main_equipment_id' => $equipment->id,
                        'latitude' => $location['latitude'] ?? null,
                        'longitude' => $location['longitude'] ?? null,
                        'address' => $location['address'] ?? null,
                        'cabinet_initial_height' => $location['cabinetInitialHeight'] ?? null,
                        'cabinet_final_height' => $location['cabinetFinalHeight'] ?? null,
                    ]);
                }

                // ذخیره اطلاعات ارتباطی
                if (!empty($equipmentData['communicationData'])) {
                    $comm = is_string($equipmentData['communicationData']) 
                        ? json_decode($equipmentData['communicationData'], true) 
                        : $equipmentData['communicationData'];
                    
                    EquipmentCommunication::create([
                        'main_equipment_id' => $equipment->id,
                        'simcard_type' => $comm['simcardType'] ?? null,
                        'simcard_number' => $comm['simcardNumber'] ?? null,
                        'simcard_ip' => $comm['simcardIp'] ?? null,
                        'antenna_status' => $comm['antennaStatus'] ?? null,
                        'signal_status' => $comm['signalStatus'] ?? null,
                        'modem_power' => $comm['modemPower'] ?? null,
                        'reset_possible' => $comm['resetPossible'] ?? false,
                    ]);
                }

                // ذخیره فیدرها
                if (!empty($equipmentData['feeders'])) {
                    $feeders = is_string($equipmentData['feeders']) 
                        ? json_decode($equipmentData['feeders'], true) 
                        : $equipmentData['feeders'];
                    
                    foreach ($feeders as $feeder) {
                        EquipmentFeeder::create([
                            'main_equipment_id' => $equipment->id,
                            'post' => $feeder['post'] ?? null,
                            'feeder' => $feeder['feeder'] ?? null,
                        ]);
                    }
                }

                // ذخیره چک‌لیست
                $checklistArray = !empty($equipmentData['checklistData'])
                    ? (is_string($equipmentData['checklistData'])
                        ? json_decode($equipmentData['checklistData'], true)
                        : $equipmentData['checklistData'])
                    : [];

                if (!empty($checklistArray)) {
                    foreach ($checklistArray as $index => $item) {
                        EquipmentChecklist::create([
                            'main_equipment_id' => $equipment->id,
                            'item' => $item['item'] ?? 'آیتم ' . ($index + 1),
                            'status' => $item['status'] ?? 'Not Checked',
                            'description' => $item['description'] ?? null,
                            'sort_order' => $index
                        ]);
                    }
                }

                // ذخیره فعالیت‌ها
                $activitiesArray = !empty($equipmentData['activitiesData'])
                    ? (is_string($equipmentData['activitiesData'])
                        ? json_decode($equipmentData['activitiesData'], true)
                        : $equipmentData['activitiesData'])
                    : [];

                if (!empty($activitiesArray)) {
                    foreach ($activitiesArray as $activity) {
                        EquipmentActivity::create([
                            'main_equipment_id' => $equipment->id,
                            'code' => $activity['code'] ?? 'N/A',
                            'title' => $activity['title'] ?? null,
                            'unit' => $activity['unit'] ?? null,
                            'unit_price' => $activity['unitPrice'] ?? 0,
                            'quantity' => $activity['quantity'] ?? 1,
                            'total' => $activity['total'] ?? 0
                        ]);
                    }
                }

                // ذخیره مصارف
                $consumablesArray = !empty($equipmentData['consumablesData'])
                    ? (is_string($equipmentData['consumablesData'])
                        ? json_decode($equipmentData['consumablesData'], true)
                        : $equipmentData['consumablesData'])
                    : [];

                if (!empty($consumablesArray)) {
                    foreach ($consumablesArray as $consumable) {
                        EquipmentConsumable::create([
                            'main_equipment_id' => $equipment->id,
                            'name' => $consumable['name'] ?? 'سایر',
                            'other_name' => $consumable['otherName'] ?? null,
                            'quantity' => $consumable['quantity'] ?? 1,
                            'unit' => $consumable['unit'] ?? 'عدد',
                            'description' => $consumable['description'] ?? null
                        ]);
                    }
                }

                // ذخیره عکس‌ها
                $photosArray = !empty($equipmentData['photosData'])
                    ? (is_string($equipmentData['photosData'])
                        ? json_decode($equipmentData['photosData'], true)
                        : $equipmentData['photosData'])
                    : [];

                if (!empty($photosArray)) {
                    foreach ($photosArray as $index => $photo) {
                        EquipmentPhoto::create([
                            'main_equipment_id' => $equipment->id,
                            'scan_code' => $photo['scanCode'] ?? null,
                            'description' => $photo['description'] ?? null,
                            'path' => $photo['dataUrl'] ?? 'images/default.jpg',
                            'sort_order' => $index
                        ]);
                    }
                }

            }


// محاسبه total_cost
$totalCost = 0;
foreach ($request->equipments as $equipmentData) {
    $activities = [];
    if (!empty($equipmentData['activitiesData'])) {
        $activities = is_string($equipmentData['activitiesData']) 
            ? json_decode($equipmentData['activitiesData'], true) 
            : $equipmentData['activitiesData'];
        
        foreach ($activities as $activity) {
            $totalCost += ($activity['total'] ?? 0);
        }
    }
}


// به‌روزرسانی جدول inspections
$inspection->update([
    'total_cost' => $totalCost,
]);




            DB::commit();
if (auth()->check()) {
    auth()->user()->logActivity(
        'ثبت بازدید جدید',
        'App\Models\Inspection',
        $inspection->id,
        null,
        [
            'inspection_date' => $request->inspection_date,
            'contractor' => $request->contractor,
            'equipments_count' => count($request->equipments)
        ]
    );
}
=======
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

>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
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
<<<<<<< HEAD
        }
    }

    public function index()
    {
        $inspections = Inspection::with('contractor', 'user')
            ->orderBy('inspection_date', 'desc')
            ->paginate(15);
        
        return view('dashboard.inspections.index', compact('inspections'));
    }

    public function show($id)
    {
        $inspection = Inspection::with([
            'mainEquipments',
            'mainEquipments.feeders',
            'mainEquipments.location',
            'mainEquipments.communication',
            'mainEquipments.checklists',
            'mainEquipments.activities',
            'mainEquipments.consumables',
            'mainEquipments.photos'
        ])->find($id);
        
        if (!$inspection) {
            return response()->json(['message' => 'یافت نشد'], 404);
        }
        
        return response()->json($inspection);
    }

    public function edit($id)
    {
        $inspection = Inspection::with([
            'mainEquipments',
            'mainEquipments.feeders',
            'mainEquipments.location',
            'mainEquipments.communication',
            'mainEquipments.checklists',
            'mainEquipments.activities',
            'mainEquipments.consumables',
            'mainEquipments.photos',
            'user'
        ])->find($id);
        
        if (!$inspection) {
            return response()->json(['message' => 'یافت نشد'], 404);
        }
        
        return response()->json($inspection);
    auth()->user()->logActivity(
        'ویرایش بازدید',
        'App\Models\Inspection',
        $inspection->id,
        $oldData,
        $inspection->toArray()
    );

    }

    public function destroy($id)
    {
        try {
            $inspection = Inspection::with('mainEquipments')->find($id);
            
            if (!$inspection) {
                return response()->json([
                    'success' => false,
                    'message' => 'بازدید یافت نشد'
                ], 404);
            }
        // لاگ قبل از حذف
        $inspectionData = [
            'id' => $inspection->id,
            'date' => $inspection->inspection_date,
            'contractor' => $inspection->contractor
        ];
            
            foreach ($inspection->mainEquipments as $equipment) {
                if ($equipment->location) {
                    $equipment->location->delete();
                }
                if ($equipment->communication) {
                    $equipment->communication->delete();
                }
                $equipment->feeders()->delete();
                $equipment->activities()->delete();
                $equipment->consumables()->delete();
                $equipment->photos()->delete();
                $equipment->checklists()->delete();
            }
            
            $inspection->mainEquipments()->delete();
            $inspection->delete();

        // لاگ فعالیت
        if (auth()->check()) {
            auth()->user()->logActivity(
                'حذف بازدید',
                'App\Models\Inspection',
                $id,
                $inspectionData,
                null
            );
        }
            
            return response()->json([
                'success' => true,
                'message' => 'بازدید و تمام اطلاعات مرتبط با موفقیت حذف شد'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا: ' . $e->getMessage()
            ], 500);
        }
=======
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
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
    }
}