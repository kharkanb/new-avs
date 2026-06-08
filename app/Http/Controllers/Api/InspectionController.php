<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\MainEquipmentType;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspection_date' => 'required|date',
            'contractor' => 'nullable|string|max:255',
            'contract_coefficient' => 'nullable|numeric',
            'contract_number' => 'nullable|string|max:255',
            'status' => 'nullable|in:draft,completed,archived',
            'daily_start_time' => 'nullable|string|max:20',
            'daily_end_time' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string',
            'equipments' => 'nullable|array',
            'equipments.*' => 'array',
        ]);

        try {
            DB::beginTransaction();

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
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'بازرسی با موفقیت ثبت شد',
                'data' => $inspection->load('mainEquipments')
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to store inspection', [
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطا در ثبت اطلاعات'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 15), 100);

        $inspections = Inspection::with('mainEquipments')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $inspections,
        ]);
    }

    public function show(Inspection $inspection)
    {
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
    }
}