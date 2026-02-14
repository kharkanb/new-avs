<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Http\Requests\StoreInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;
use App\Http\Resources\InspectionResource;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    /**
     * لیست تمام بازرسی‌ها
     */
    public function index(Request $request)
    {
        $query = Inspection::with('mainEquipments');
        
        // فیلتر بر اساس تاریخ
        if ($request->has('from_date')) {
            $query->whereDate('inspection_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('inspection_date', '<=', $request->to_date);
        }
        
        // فیلتر بر اساس وضعیت
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $inspections = $query->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $inspections
        ]);
    }

    /**
     * ثبت بازرسی جدید
     */
    public function store(StoreInspectionRequest $request)
    {
        $inspection = Inspection::create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'بازرسی با موفقیت ثبت شد',
            'data' => new InspectionResource($inspection)
        ], 201);
    }

    /**
     * نمایش یک بازرسی
     */
    public function show(Inspection $inspection)
    {
        $inspection->load('mainEquipments');
        
        return response()->json([
            'success' => true,
            'data' => new InspectionResource($inspection)
        ]);
    }

    /**
     * بروزرسانی بازرسی
     */
    public function update(UpdateInspectionRequest $request, Inspection $inspection)
    {
        $inspection->update($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'بازرسی با موفقیت بروزرسانی شد',
            'data' => new InspectionResource($inspection)
        ]);
    }

    /**
     * حذف بازرسی
     */
    public function destroy(Inspection $inspection)
    {
        $inspection->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'بازرسی با موفقیت حذف شد'
        ]);
    }

    /**
     * دریافت تجهیزات یک بازرسی
     */
    public function equipments(Inspection $inspection)
    {
        $equipments = $inspection->mainEquipments()->with('type', 'post', 'feeders')->get();
        
        return response()->json([
            'success' => true,
            'data' => $equipments
        ]);
    }

    /**
     * آمار بازرسی‌ها
     */
    public function stats()
    {
        $stats = [
            'total' => Inspection::count(),
            'draft' => Inspection::where('status', 'draft')->count(),
            'completed' => Inspection::where('status', 'completed')->count(),
            'archived' => Inspection::where('status', 'archived')->count(),
            'this_month' => Inspection::whereMonth('inspection_date', now()->month)->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}