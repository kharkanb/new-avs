<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\Activity;
use App\Models\Consumable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * آمار کلی داشبورد
     */
    public function stats()
    {
        // آمار کلی
        $stats = [
            'total_inspections' => Inspection::count(),
            'total_equipments' => MainEquipment::count(),
            'total_activities' => Activity::count(),
            'total_consumables' => Consumable::count(),
            
            // بازرسی‌های امروز
            'today_inspections' => Inspection::whereDate('inspection_date', today())->count(),
            
            // بازرسی‌های این هفته
            'week_inspections' => Inspection::whereBetween('inspection_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            
            // بازرسی‌های این ماه
            'month_inspections' => Inspection::whereMonth('inspection_date', now()->month)->count(),
            
            // وضعیت بازرسی‌ها
            'inspections_by_status' => [
                'draft' => Inspection::where('status', 'draft')->count(),
                'completed' => Inspection::where('status', 'completed')->count(),
                'archived' => Inspection::where('status', 'archived')->count(),
            ],
        ];

        // ۱۰ بازرسی آخر
        $latest_inspections = Inspection::with('mainEquipments')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'latest_inspections' => $latest_inspections
            ]
        ]);
    }

    /**
     * نمودار بازرسی‌های ماهانه
     */
    public function monthlyChart(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $monthlyStats = Inspection::select(
            DB::raw('MONTH(inspection_date) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('inspection_date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $monthlyStats
        ]);
    }

    /**
     * نمودار تجهیزات بر اساس نوع
     */
    public function equipmentTypeChart()
    {
        $equipmentByType = MainEquipment::select(
            'main_equipment_types.name as type_name',
            DB::raw('COUNT(*) as total')
        )
        ->join('main_equipment_types', 'main_equipments.main_equipment_type_id', '=', 'main_equipment_types.id')
        ->groupBy('main_equipment_types.name')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $equipmentByType
        ]);
    }

    /**
     * نمودار فعالیت‌ها بر اساس نوع
     */
    public function activityChart()
    {
        $topActivities = Activity::select(
            'activity_code',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(quantity) as total_quantity')
        )
        ->groupBy('activity_code')
        ->orderByDesc('total')
        ->limit(10)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $topActivities
        ]);
    }
}