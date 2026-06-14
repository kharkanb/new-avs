<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\Post;
use App\Models\Department;
use App\Models\EquipmentFeeder;
use App\Models\EquipmentLocation;
use App\Models\EquipmentCommunication;
use App\Models\EquipmentChecklist;
use App\Models\EquipmentActivity;
use App\Models\EquipmentConsumable;
use App\Models\EquipmentPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    public function store(Request $request)
    {
<<<<<<< HEAD
        Log::info('Inspection store request received');

        $validator = Validator::make($request->all(), [
            'inspection_date' => 'required|string',
            'contractor' => 'required|string',
            'contract_coefficient' => 'required|numeric',
            'daily_start_time' => 'nullable|string',
            'daily_end_time' => 'nullable|string',
=======
        $validated = $request->validate([
            'inspection_date' => 'required|date',
            'contractor' => 'nullable|string|max:255',
            'contract_coefficient' => 'nullable|numeric',
            'contract_number' => 'nullable|string|max:255',
            'status' => 'nullable|in:draft,completed,archived',
            'daily_start_time' => 'nullable|string|max:20',
            'daily_end_time' => 'nullable|string|max:20',
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
            'whatsapp_number' => 'nullable|string',
            'equipments' => 'nullable|array',
            'equipments.*' => 'array',
        ]);

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

            // محاسبه total_cost
            $totalCost = 0;
            foreach ($request->equipments as $eq) {
                if (!empty($eq['activitiesData'])) {
                    $activities = is_string($eq['activitiesData']) 
                        ? json_decode($eq['activitiesData'], true) 
                        : $eq['activitiesData'];
                    foreach ($activities as $activity) {
                        $totalCost += ($activity['total'] ?? 0);
                    }
                }
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
                'total_cost' => $totalCost,
                'status' => 'completed',
                'final_status' => 'approved',
            ]);

            // ذخیره تجهیزات
            foreach ($request->equipments as $equipmentData) {
                
                Log::info('Equipment Brand Debug', [
                    'equipment_type' => $equipmentData['equipmentType'] ?? null,
                    'request_brand_id' => $equipmentData['brand_id'] ?? null,
                ]);

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

                // تعیین brand_id
                $brandId = $equipmentData['brand_id'] ?? null;
                if (in_array($equipmentData['equipmentType'], [
                    'پست دو سو تغذیه (مشترک حساس)',
                    'پست دو سو تغذیه (بیمارستانی)',
                    'مشترک ولتاژ اولیه'
                ])) {
                    $brandId = null;
                }

                // ایجاد تجهیز اصلی
                $equipment = new MainEquipment([
                    'inspection_id' => $inspection->id,
                    'main_equipment_type_id' => $equipmentType->id,
                    'scada_code' => $equipmentData['scadaCode'] ?? null,
                    'installation_type' => $equipmentData['installationType'] ?? null,
                    'post_id' => $postId,
                    'department_id' => $equipmentData['departmentData']['department_id'] ?? null,
                    'brand_id' => $brandId,
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
=======
            $inspection = Inspection::create([
                'inspection_date' => $validated['inspection_date'],
                'contractor' => $validated['contractor'] ?? null,
                'contract_coefficient' => $validated['contract_coefficient'] ?? null,
                'contract_number' => $validated['contract_number'] ?? null,
                'daily_start_time' => $validated['daily_start_time'] ?? null,
                'daily_end_time' => $validated['daily_end_time'] ?? null,
                'whatsapp_number' => $validated['whatsapp_number'] ?? null,
                'status' => $validated['status'] ?? 'draft',
            ]);

            foreach ($validated['equipments'] ?? [] as $equipmentData) {
                $inspection->mainEquipments()->save(
                    new MainEquipment($this->normalizeEquipmentData($equipmentData))
                );
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
            }

            DB::commit();

            // ثبت لاگ (اختیاری - اگر متد logActivity وجود دارد)
            if (auth()->check() && method_exists(auth()->user(), 'logActivity')) {
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

            return response()->json([
                'success' => true,
                'message' => 'بازرسی با موفقیت ثبت شد',
                'data' => $inspection->load('mainEquipments')
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
<<<<<<< HEAD
            Log::error('Error in store inspection: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
=======
            Log::error('Failed to store inspection', [
                'exception' => $e,
            ]);

>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
            return response()->json([
                'success' => false,
                'message' => 'خطا در ثبت اطلاعات'
            ], 500);
        }
    }

    public function index(Request $request)
    {
<<<<<<< HEAD
        $inspections = Inspection::with('contractor')->get();
        return response()->json($inspections);
=======
        $perPage = min((int) $request->get('per_page', 15), 100);

        $inspections = Inspection::with('mainEquipments')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $inspections,
        ]);
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
    }

    public function show(Inspection $inspection)
    {
<<<<<<< HEAD
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
            return response()->json(['success' => false, 'message' => 'یافت نشد'], 404);
        }
        
        return response()->json($inspection);
=======
        return response()->json([
            'success' => true,
            'data' => $inspection->load('mainEquipments'),
        ]);
    }

    public function update(Request $request, Inspection $inspection)
    {
        $validated = $request->validate([
            'inspection_date' => 'sometimes|date',
            'contractor' => 'sometimes|nullable|string|max:255',
            'contract_coefficient' => 'sometimes|nullable|numeric',
            'contract_number' => 'sometimes|nullable|string|max:255',
            'daily_start_time' => 'sometimes|nullable|string|max:20',
            'daily_end_time' => 'sometimes|nullable|string|max:20',
            'whatsapp_number' => 'sometimes|nullable|string',
            'status' => 'sometimes|in:draft,completed,archived',
        ]);

        $inspection->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'بازرسی با موفقیت بروزرسانی شد',
            'data' => $inspection->fresh('mainEquipments'),
        ]);
    }

    public function destroy(Inspection $inspection)
    {
        $inspection->delete();

        return response()->json([
            'success' => true,
            'message' => 'بازرسی با موفقیت حذف شد',
        ]);
    }

    public function equipments(Inspection $inspection)
    {
        return response()->json([
            'success' => true,
            'data' => $inspection->mainEquipments()->with(['type', 'post', 'feeders'])->get(),
        ]);
    }

    private function normalizeEquipmentData(array $equipmentData): array
    {
        $equipmentTypeName = $equipmentData['equipmentType']
            ?? $equipmentData['equipment_type']
            ?? 'نامشخص';

        $equipmentType = MainEquipmentType::firstOrCreate(
            ['name' => $equipmentTypeName],
            [
                'feeder_mode' => in_array($equipmentTypeName, [
                    'پست دو سو تغذیه (مشترک حساس)',
                    'پست دو سو تغذیه (بیمارستانی)'
                ], true) ? 'dual' : 'single',
                'has_cells' => in_array($equipmentTypeName, [
                    'پست دو سو تغذیه (مشترک حساس)',
                    'پست دو سو تغذیه (بیمارستانی)',
                    'مشترک ولتاژ اولیه'
                ], true),
                'has_brand' => in_array($equipmentTypeName, [
                    'ریکلوزر', 'سکسیونر', 'سکشنالایزر', 'فالت دتکتور'
                ], true),
                'has_height' => !in_array($equipmentTypeName, [
                    'پست دو سو تغذیه (مشترک حساس)',
                    'پست دو سو تغذیه (بیمارستانی)',
                    'مشترک ولتاژ اولیه'
                ], true)
            ]
        );

        $feeders = $this->toArray($equipmentData['feeders'] ?? []);
        $locationData = $this->toArray($equipmentData['locationData'] ?? $equipmentData['location_data'] ?? []);

        $postId = null;
        $postName = $feeders[0]['post'] ?? null;
        if ($postName) {
            $postId = Post::where('name', $postName)->value('id');
        }

        return [
            'main_equipment_type_id' => $equipmentType->id,
            'post_id' => $postId,
            'scada_code' => $equipmentData['scadaCode'] ?? $equipmentData['scada_code'] ?? null,
            'installation_type' => $equipmentData['installationType'] ?? $equipmentData['installation_type'] ?? null,
            'latitude' => $locationData['latitude'] ?? null,
            'longitude' => $locationData['longitude'] ?? null,
            'height' => $locationData['cabinetFinalHeight'] ?? $locationData['height'] ?? null,
            'feeders' => $feeders,
            'department_data' => $this->toArray($equipmentData['departmentData'] ?? $equipmentData['department_data'] ?? []),
            'location_data' => $locationData,
            'communication_data' => $this->toArray($equipmentData['communicationData'] ?? $equipmentData['communication_data'] ?? []),
            'checklist_data' => $this->toArray($equipmentData['checklistData'] ?? $equipmentData['checklist_data'] ?? []),
            'activities_data' => $this->toArray($equipmentData['activitiesData'] ?? $equipmentData['activities_data'] ?? []),
            'consumables_data' => $this->toArray($equipmentData['consumablesData'] ?? $equipmentData['consumables_data'] ?? []),
            'photos_data' => $this->toArray($equipmentData['photosData'] ?? $equipmentData['photos_data'] ?? []),
            'cell_specs' => $this->toArray($equipmentData['cellSpecs'] ?? $equipmentData['cell_specs'] ?? []),
            'tabs_validated' => $this->toArray($equipmentData['tabsValidated'] ?? $equipmentData['tabs_validated'] ?? []),
        ];
    }

    private function toArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
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
            return response()->json(['success' => false, 'message' => 'یافت نشد'], 404);
        }
        
        return response()->json($inspection);
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

            return response()->json([
                'success' => true,
                'message' => 'بازدید و تمام اطلاعات مرتبط با موفقیت حذف شد'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in destroy inspection: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'خطا: ' . $e->getMessage()
            ], 500);
        }
    }
}