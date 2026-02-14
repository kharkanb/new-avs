<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MainEquipment;
use App\Models\CellEquipment;
use App\Models\Activity;
use App\Models\Consumable;
use App\Models\ChecklistItem;
use App\Models\Photo;
use Illuminate\Http\Request;

class MainEquipmentController extends Controller
{
    /**
     * لیست تجهیزات اصلی
     */
    public function index(Request $request)
    {
        $query = MainEquipment::with(['type', 'post', 'feeders']);
        
        // فیلتر بر اساس بازرسی
        if ($request->has('inspection_id')) {
            $query->where('inspection_id', $request->inspection_id);
        }
        
        // فیلتر بر اساس نوع تجهیز
        if ($request->has('type_id')) {
            $query->where('main_equipment_type_id', $request->type_id);
        }
        
        // فیلتر بر اساس پست
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }
        
        $equipments = $query->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $equipments
        ]);
    }

    /**
     * ثبت تجهیز جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspection_id' => 'required|exists:inspections,id',
            'main_equipment_type_id' => 'required|exists:main_equipment_types,id',
            'scada_code' => 'nullable|string|size:4|unique:main_equipments',
            'post_id' => 'required|exists:posts,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'height' => 'nullable|integer',
            'installation_type' => 'nullable|string|max:50',
            'feeder_ids' => 'nullable|array',
            'feeder_ids.*' => 'exists:feeders,id'
        ]);

        $equipment = MainEquipment::create($validated);
        
        // اتصال فیدرها
        if ($request->has('feeder_ids')) {
            $equipment->feeders()->sync($request->feeder_ids);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'تجهیز با موفقیت ثبت شد',
            'data' => $equipment->load('type', 'post', 'feeders')
        ], 201);
    }

    /**
     * نمایش یک تجهیز
     */
    public function show(MainEquipment $mainEquipment)
    {
        $mainEquipment->load(['type', 'post', 'feeders', 'cells', 'activities', 'consumables', 'checklistItems', 'photos']);
        
        return response()->json([
            'success' => true,
            'data' => $mainEquipment
        ]);
    }

    /**
     * بروزرسانی تجهیز
     */
    public function update(Request $request, MainEquipment $mainEquipment)
    {
        $validated = $request->validate([
            'scada_code' => 'nullable|string|size:4|unique:main_equipments,scada_code,' . $mainEquipment->id,
            'post_id' => 'sometimes|exists:posts,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'height' => 'nullable|integer',
            'installation_type' => 'nullable|string|max:50',
            'feeder_ids' => 'nullable|array',
            'feeder_ids.*' => 'exists:feeders,id'
        ]);

        $mainEquipment->update($validated);
        
        // بروزرسانی فیدرها
        if ($request->has('feeder_ids')) {
            $mainEquipment->feeders()->sync($request->feeder_ids);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'تجهیز با موفقیت بروزرسانی شد',
            'data' => $mainEquipment->load('type', 'post', 'feeders')
        ]);
    }

    /**
     * حذف تجهیز
     */
    public function destroy(MainEquipment $mainEquipment)
    {
        $mainEquipment->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'تجهیز با موفقیت حذف شد'
        ]);
    }

    /**
     * دریافت سلول‌های یک تجهیز
     */
    public function cells(MainEquipment $mainEquipment)
    {
        $cells = $mainEquipment->cells()->with('equipments')->get();
        
        return response()->json([
            'success' => true,
            'data' => $cells
        ]);
    }

    /**
     * دریافت فعالیت‌های یک تجهیز
     */
    public function activities(MainEquipment $mainEquipment)
    {
        $activities = $mainEquipment->activities;
        
        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * دریافت مصرفی‌های یک تجهیز
     */
    public function consumables(MainEquipment $mainEquipment)
    {
        $consumables = $mainEquipment->consumables;
        
        return response()->json([
            'success' => true,
            'data' => $consumables
        ]);
    }

    /**
     * دریافت چک‌لیست یک تجهیز
     */
    public function checklist(MainEquipment $mainEquipment)
    {
        $checklist = $mainEquipment->checklistItems;
        
        return response()->json([
            'success' => true,
            'data' => $checklist
        ]);
    }

    /**
     * دریافت عکس‌های یک تجهیز
     */
    public function photos(MainEquipment $mainEquipment)
    {
        $photos = $mainEquipment->photos;
        
        return response()->json([
            'success' => true,
            'data' => $photos
        ]);
    }
}