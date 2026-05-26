<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\User;
use App\Models\Contractor;  
use App\Models\Department;
use App\Models\MainEquipmentType;
use App\Models\EquipmentActivity;
use App\Models\EquipmentConsumable;
use App\Models\ActivityPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hekmatinasser\Verta\Verta;
use Carbon\Carbon;

class AdvancedDashboardController extends Controller
{   
    private function getJalaliRanges($year)
    {
        $baseRanges = [
            1 => ['start' => '2026-03-21', 'end' => '2026-04-20'],
            2 => ['start' => '2026-04-21', 'end' => '2026-05-21'],
            3 => ['start' => '2026-05-22', 'end' => '2026-06-21'],
            4 => ['start' => '2026-06-22', 'end' => '2026-07-22'],
            5 => ['start' => '2026-07-23', 'end' => '2026-08-22'],
            6 => ['start' => '2026-08-23', 'end' => '2026-09-22'],
            7 => ['start' => '2026-09-23', 'end' => '2026-10-22'],
            8 => ['start' => '2026-10-23', 'end' => '2026-11-21'],
            9 => ['start' => '2026-11-22', 'end' => '2026-12-21'],
            10 => ['start' => '2026-12-22', 'end' => '2027-01-20'],
            11 => ['start' => '2027-01-21', 'end' => '2027-02-19'],
            12 => ['start' => '2027-02-20', 'end' => '2027-03-20'],
        ];
        
        if ($year == 1405) return $baseRanges;
        
        $yearDiff = $year - 1405;
        $result = [];
        foreach ($baseRanges as $month => $range) {
            $startYear = (int)substr($range['start'], 0, 4) + $yearDiff;
            $startDate = $startYear . substr($range['start'], 4);
            $endYear = (int)substr($range['end'], 0, 4) + $yearDiff;
            $endDate = $endYear . substr($range['end'], 4);
            $result[$month] = ['start' => $startDate, 'end' => $endDate];
        }
        return $result;
    }

    private function getCurrentJalaliYear()
    {
        try {
            return Verta::now()->year;
        } catch (\Exception $e) {
            return 1405;
        }
    }
    
    private function getCurrentJalaliMonth()
    {
        try {
            return Verta::now()->month;
        } catch (\Exception $e) {
            return 2;
        }
    }

    // تابع تبدیل اعداد فارسی به انگلیسی
    private function convertPersianToEnglish($string)
    {
        if (empty($string)) return $string;
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($persian, $english, $string);
    }

    private function convertJalaliToGregorian($jalaliDate)
    {
        if (empty($jalaliDate)) return null;
        
        try {
            // تبدیل اعداد فارسی به انگلیسی
            $cleanDate = $this->convertPersianToEnglish($jalaliDate);
            $cleanDate = preg_replace('/[^0-9\/]/', '', $cleanDate);
            $parts = explode('/', $cleanDate);
            if (count($parts) != 3) return null;
            
            $year = (int)$parts[0];
            $month = (int)$parts[1];
            $day = (int)$parts[2];
            
            $gregorian = Verta::jalaliToGregorian($year, $month, $day);
            return sprintf('%04d-%02d-%02d', $gregorian[0], $gregorian[1], $gregorian[2]);
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public function index(Request $request)
    {
        $currentJalaliYear = $this->getCurrentJalaliYear();
        $currentJalaliMonth = $this->getCurrentJalaliMonth();
        $ranges = $this->getJalaliRanges($currentJalaliYear);
        $currentMonthRange = $ranges[$currentJalaliMonth];
        
        // دریافت پارامترهای فیلتر (یکسان برای همه جداول)
        $perPage = $request->get('per_page', 20);
        $contractorFilter = $request->get('contractor');
        $departmentFilter = $request->get('department');
        $equipmentTypeFilter = $request->get('equipment_type');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // تبدیل تاریخ شمسی به میلادی با پشتیبانی از اعداد فارسی
        $gregorianStart = $startDate ? $this->convertJalaliToGregorian($startDate) : null;
        $gregorianEnd = $endDate ? $this->convertJalaliToGregorian($endDate) : null;
        
        // ========== لیست‌ها برای فیلترها ==========
        $contractors = Contractor::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $equipmentTypes = MainEquipmentType::orderBy('name')->get();
        // ========================================
        
        // ================================================
        // آمارهای پایه
        // ================================================
        $totalCost = Inspection::sum('total_cost') ?? 0;
        $totalInspections = Inspection::count();
        $completedInspections = Inspection::where('status', 'completed')->count();
        $draftInspections = Inspection::where('status', 'draft')->count();
        
        $statusStats = [
            'completed' => $completedInspections,
            'draft' => $draftInspections,
            'archived' => Inspection::where('status', 'archived')->count(),
        ];
        
        $completedPercent = $totalInspections > 0 ? ($completedInspections / $totalInspections) * 100 : 0;
        $draftPercent = $totalInspections > 0 ? ($draftInspections / $totalInspections) * 100 : 0;
        $archivedPercent = $totalInspections > 0 ? (($statusStats['archived'] ?? 0) / $totalInspections) * 100 : 0;
        
        $inspectionsThisMonth = Inspection::whereBetween('inspection_date', [$currentMonthRange['start'], $currentMonthRange['end']])->count();
        $totalCostThisMonth = Inspection::whereBetween('inspection_date', [$currentMonthRange['start'], $currentMonthRange['end']])->sum('total_cost') ?? 0;
        $inspectionsToday = Inspection::whereDate('inspection_date', now()->toDateString())->count();
        
        $now = now();
        $lastMonth = now()->subMonth();
        
        $currentMonthInspections = Inspection::whereMonth('inspection_date', $now->month)->whereYear('inspection_date', $now->year)->count();
        $lastMonthInspections = Inspection::whereMonth('inspection_date', $lastMonth->month)->whereYear('inspection_date', $lastMonth->year)->count();
        $inspectionGrowth = $lastMonthInspections > 0 ? round(($currentMonthInspections - $lastMonthInspections) / $lastMonthInspections * 100, 1) : 0;
        
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        
        $myInspections = auth()->user()->inspections();
        $myCompleted = $myInspections->where('status', 'completed')->count();
        $totalMyInspections = $myInspections->count();
        $performanceScore = $totalMyInspections > 0 ? round(($myCompleted / $totalMyInspections) * 100) : 0;
        $performanceText = $performanceScore >= 80 ? 'عالی' : ($performanceScore >= 60 ? 'خوب' : ($performanceScore >= 50 ? 'متوسط' : 'نیاز به تلاش بیشتر'));
        
        $stats = [
            'total_inspections' => $totalInspections,
            'total_equipments' => DB::table('equipment_checklists')->count(),
            'total_activities' => EquipmentActivity::count(),
            'total_consumables' => EquipmentConsumable::count(),
            'total_users' => $totalUsers,
            'total_contractors' => Contractor::count(),
            'pending_inspections' => $draftInspections,
            'my_inspections' => $totalMyInspections,
            'inspection_growth' => $inspectionGrowth,
            'inspection_growth_class' => $inspectionGrowth >= 0 ? 'text-success' : 'text-danger',
            'inspection_growth_icon' => $inspectionGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down',
            'new_users_count' => $newUsersThisMonth,
            'my_completed' => $myCompleted,
            'performance_score' => $performanceScore,
            'performance_text' => $performanceText,
        ];
        
        // ================================================
        // آمار تجهیزات
        // ================================================
        $totalChecklistItems = DB::table('equipment_checklists')->count();
        $okCount = DB::table('equipment_checklists')->where('status', 'OK')->count();
        $notOkCount = DB::table('equipment_checklists')->where('status', 'Not OK')->count();
        
        $equipmentStatus = [
            'labels' => ['سالم (OK)', 'خراب (Not OK)'],
            'data' => [$okCount, $notOkCount],
            'colors' => ['#27ae60', '#e74c3c']
        ];
        
        $totalEquipments = $totalChecklistItems;
        $okEquipmentsCount = $okCount;
        $notOkEquipmentsCount = $notOkCount;
        $okPercent = $totalEquipments > 0 ? ($okEquipmentsCount / $totalEquipments) * 100 : 0;
        $notOkPercent = $totalEquipments > 0 ? ($notOkEquipmentsCount / $totalEquipments) * 100 : 0;
        
        // ================================================
        // آخرین بازدیدها (با فیلترهای کامل)
        // ================================================
        $recentInspectionsQuery = Inspection::with(['user', 'mainEquipments.department', 'contractor']);
        
        if ($contractorFilter) {
            $recentInspectionsQuery->where('contractor_id', $contractorFilter);
        }
        if ($departmentFilter) {
            $recentInspectionsQuery->where('department_id', $departmentFilter);
        }
        if ($equipmentTypeFilter) {
            $recentInspectionsQuery->whereHas('mainEquipments', function($q) use ($equipmentTypeFilter) {
                $q->where('main_equipment_type_id', $equipmentTypeFilter);
            });
        }
        if ($gregorianStart) {
            $recentInspectionsQuery->whereDate('inspection_date', '>=', $gregorianStart);
        }
        if ($gregorianEnd) {
            $recentInspectionsQuery->whereDate('inspection_date', '<=', $gregorianEnd);
        }
        
        $recentInspections = $recentInspectionsQuery->orderBy('created_at', 'desc')->paginate($perPage);
        
        foreach ($recentInspections as $inspection) {
            try {
                $inspection->jalali_date = Verta::instance($inspection->inspection_date)->format('Y/m/d');
                $inspection->jalali_created = Verta::instance($inspection->created_at)->format('Y/m/d H:i');
            } catch (\Exception $e) {
                $inspection->jalali_date = $inspection->inspection_date;
                $inspection->jalali_created = $inspection->created_at;
            }
        }
        
        // ================================================
        // گزارش تجهیزات سالم (OK) - با فیلترهای کامل
        // ================================================
        $okQuery = DB::table('equipment_checklists as ec')
            ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
            ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
            ->leftJoin('main_equipment_types as met', 'me.main_equipment_type_id', '=', 'met.id')
            ->leftJoin('posts as p', 'me.post_id', '=', 'p.id')
            ->where('ec.status', 'OK');
        
        if ($contractorFilter) {
            $okQuery->where('i.contractor_id', $contractorFilter);
        }
        if ($departmentFilter) {
            $okQuery->where('i.department_id', $departmentFilter);
        }
        if ($equipmentTypeFilter) {
            $okQuery->where('me.main_equipment_type_id', $equipmentTypeFilter);
        }
        if ($gregorianStart) {
            $okQuery->whereDate('i.inspection_date', '>=', $gregorianStart);
        }
        if ($gregorianEnd) {
            $okQuery->whereDate('i.inspection_date', '<=', $gregorianEnd);
        }
        
        $okEquipments = $okQuery->select(
                'ec.id',
                'ec.status',
                'me.scada_code',
                'me.id as equipment_id',
                'i.contractor_name',
                'i.inspection_date',
                'met.name as equipment_type',
                'p.name as location'
            )
            ->orderBy('ec.created_at', 'desc')
            ->distinct('me.id')
            ->paginate($perPage)
            ->through(function($item) {
                return [
                    'id' => $item->id,
                    'equipment_type' => $item->equipment_type ?? 'نامشخص',
                    'scada_code' => $item->scada_code ?? '---',
                    'inspection_date' => $item->inspection_date ? Verta::instance($item->inspection_date)->format('Y/m/d') : '-',
                    'contractor_name' => $item->contractor_name ?? '-',
                    'location' => $item->location ?? '-',
                    'status' => 'OK',
                ];
            });
        
        // ================================================
        // گزارش تجهیزات خراب (Not OK) - با فیلترهای کامل
        // ================================================
        $failureQuery = DB::table('equipment_checklists as ec')
            ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
            ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
            ->leftJoin('main_equipment_types as met', 'me.main_equipment_type_id', '=', 'met.id')
            ->leftJoin('posts as p', 'me.post_id', '=', 'p.id')
            ->where('ec.status', 'Not OK');
        
        if ($contractorFilter) {
            $failureQuery->where('i.contractor_id', $contractorFilter);
        }
        if ($departmentFilter) {
            $failureQuery->where('i.department_id', $departmentFilter);
        }
        if ($equipmentTypeFilter) {
            $failureQuery->where('me.main_equipment_type_id', $equipmentTypeFilter);
        }
        if ($gregorianStart) {
            $failureQuery->whereDate('i.inspection_date', '>=', $gregorianStart);
        }
        if ($gregorianEnd) {
            $failureQuery->whereDate('i.inspection_date', '<=', $gregorianEnd);
        }
        
        $failureEquipments = $failureQuery->select(
                'ec.id',
                'ec.item',
                'ec.status',
                'ec.description as failure_description',
                'me.scada_code',
                'me.id as equipment_id',
                'i.contractor_name',
                'i.inspection_date',
                'met.name as equipment_type',
                'p.name as location'
            )
            ->orderBy('ec.created_at', 'desc')
            ->paginate($perPage)
            ->through(function($item) {
                return [
                    'id' => $item->id,
                    'equipment_type' => $item->equipment_type ?? 'نامشخص',
                    'scada_code' => $item->scada_code ?? '---',
                    'inspection_date' => $item->inspection_date ? Verta::instance($item->inspection_date)->format('Y/m/d') : '-',
                    'contractor_name' => $item->contractor_name ?? '-',
                    'location' => $item->location ?? '-',
                    'failure_item' => $item->item,
                    'failure_description' => $item->failure_description ?? '-',
                    'status' => 'Not OK',
                ];
            });
        
        // ================================================
        // فعالیت‌های انجام شده - با فیلترهای کامل
        // ================================================
        $activitiesQuery = EquipmentActivity::with(['mainEquipment.inspection']);
        
        if ($contractorFilter) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($contractorFilter) {
                $q->where('contractor_id', $contractorFilter);
            });
        }
        if ($departmentFilter) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($departmentFilter) {
                $q->where('department_id', $departmentFilter);
            });
        }
        if ($equipmentTypeFilter) {
            $activitiesQuery->whereHas('mainEquipment', function($q) use ($equipmentTypeFilter) {
                $q->where('main_equipment_type_id', $equipmentTypeFilter);
            });
        }
        if ($gregorianStart) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($gregorianStart) {
                $q->whereDate('inspection_date', '>=', $gregorianStart);
            });
        }
        if ($gregorianEnd) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($gregorianEnd) {
                $q->whereDate('inspection_date', '<=', $gregorianEnd);
            });
        }
        
        $activities = $activitiesQuery->orderBy('created_at', 'desc')->paginate($perPage);
        
        // ================================================
        // داده‌های نمودار ماهانه
        // ================================================
        $persianMonths = [1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان', 9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'];
        $monthRanges = $this->getJalaliRanges($currentJalaliYear);
        $monthlyLabels = [];
        $monthlyCounts = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthlyLabels[] = $persianMonths[$month];
            $count = Inspection::whereBetween('inspection_date', [$monthRanges[$month]['start'], $monthRanges[$month]['end']])->count();
            $monthlyCounts[] = $count;
        }
        
        $monthlyInspections = [
            'months' => $monthlyLabels,
            'counts' => $monthlyCounts,
            'year' => $currentJalaliYear
        ];
        
        $maxMonthly = max($monthlyCounts) > 0 ? max($monthlyCounts) : 1;
        
        // ================================================
        // پیمانکاران برتر
        // ================================================
        $topContractorsData = Inspection::select('contractor_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_cost) as total_cost'))
            ->whereNotNull('contractor_id')
            ->groupBy('contractor_id')
            ->with('contractor')
            ->orderBy('total_cost', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->contractor->name ?? 'نامشخص',
                    'count' => $item->count,
                    'total_cost' => $item->total_cost ?? 0
                ];
            })->toArray();
        
        $topContractors = $topContractorsData;
        $maxContractorCost = !empty($topContractors) ? max(array_column($topContractors, 'total_cost')) : 1;
        
        $contractorCosts = [
            'names' => array_column($topContractors, 'name'),
            'costs' => array_column($topContractors, 'total_cost')
        ];
        
        // ================================================
        // بازدیدهای سالانه
        // ================================================
        $yearlyInspections = [
            'years' => [2025, 2026],
            'counts' => [
                Inspection::whereYear('inspection_date', 2025)->count(),
                Inspection::whereYear('inspection_date', 2026)->count()
            ]
        ];
        
        // ================================================
        // فهرست بها
        // ================================================
        $priceList = ActivityPrice::orderBy('code')->get()->map(function($price) {
            return [
                'code' => $price->code,
                'title' => $price->title,
                'unit' => $price->unit,
                'unit_price' => $price->unit_price,
                'description' => $price->description,
            ];
        });
        
        // ================================================
        // فعالیت‌های اخیر
        // ================================================
        $newInspections = Inspection::with('user')->latest()->take(3)->get()->map(function($inspection) {
            return (object)[
                'type' => 'inspection',
                'icon' => 'bi-plus-circle',
                'title' => 'بازدید جدید توسط ' . ($inspection->user->name ?? 'کاربر ناشناس'),
                'time' => $inspection->created_at->diffForHumans(),
                'created_at' => $inspection->created_at
            ];
        });
        
        $newUsers = User::latest()->take(2)->get()->map(function($user) {
            return (object)[
                'type' => 'user',
                'icon' => 'bi-person-plus',
                'title' => 'کاربر جدید: ' . $user->name,
                'time' => $user->created_at->diffForHumans(),
                'created_at' => $user->created_at
            ];
        });
        
        $recentActivities = $newInspections->concat($newUsers)->sortByDesc('created_at')->take(6)->values();
        
        // ================================================
        // بازگشت به ویو
        // ================================================
        return view('dashboard.advanced', [
            'totalCost' => $totalCost,
            'totalCostThisMonth' => $totalCostThisMonth,
            'inspectionsThisMonth' => $inspectionsThisMonth,
            'inspectionsToday' => $inspectionsToday,
            'totalInspections' => $totalInspections,
            'completedInspections' => $completedInspections,
            'draftInspections' => $draftInspections,
            'monthlyLabels' => $monthlyLabels,
            'monthlyData' => $monthlyCounts,
            'monthlyInspections' => $monthlyInspections,
            'maxMonthly' => $maxMonthly,
            'topContractors' => $topContractors,
            'contractorCosts' => $contractorCosts,
            'maxContractorCost' => $maxContractorCost,
            'yearlyInspections' => $yearlyInspections,
            'stats' => $stats,
            'recentInspections' => $recentInspections,
            'recentActivities' => $recentActivities,
            'statusStats' => $statusStats,
            'equipmentStatus' => $equipmentStatus,
            'okPercent' => $okPercent,
            'notOkPercent' => $notOkPercent,
            'totalEquipments' => $totalEquipments,
            'okEquipmentsCount' => $okEquipmentsCount,
            'notOkEquipmentsCount' => $notOkEquipmentsCount,
            'okEquipments' => $okEquipments,
            'failureEquipments' => $failureEquipments,
            'currentJalaliYear' => $currentJalaliYear,
            'totalUsers' => $totalUsers,
            'totalMyInspections' => $totalMyInspections,
            'myCompleted' => $myCompleted,
            'performanceScore' => $performanceScore,
            'performanceText' => $performanceText,
            'priceList' => $priceList,
            'activities' => $activities,
            'contractors' => $contractors,
            'departments' => $departments,
            'equipmentTypes' => $equipmentTypes,
            'completedPercent' => $completedPercent,
            'draftPercent' => $draftPercent,
            'archivedPercent' => $archivedPercent,
        ]);
    }
    
    public function getContractorDetails($name)
    {
        $inspections = Inspection::where('contractor_name', $name)->get();
        return response()->json([
            'inspections_count' => $inspections->count(),
            'total_cost' => $inspections->sum('total_cost'),
            'avg_cost' => $inspections->avg('total_cost'),
            'last_inspection' => $inspections->max('created_at') ? Verta::instance($inspections->max('created_at'))->format('Y/m/d') : '-',
        ]);
    }
    
    public function getMonthlyChartData(Request $request)
    {
        $year = $request->get('year', $this->getCurrentJalaliYear());
        $monthRanges = $this->getJalaliRanges((int)$year);
        
        $persianMonths = [1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان', 9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'];
        
        $monthlyLabels = [];
        $monthlyCounts = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthlyLabels[] = $persianMonths[$month];
            $count = Inspection::whereBetween('inspection_date', [$monthRanges[$month]['start'], $monthRanges[$month]['end']])->count();
            $monthlyCounts[] = $count;
        }
        
        return response()->json([
            'months' => $monthlyLabels,
            'counts' => $monthlyCounts,
            'year' => $year
        ]);
    }
    
    public function getStats()
    {
        $currentJalaliYear = $this->getCurrentJalaliYear();
        $currentJalaliMonth = $this->getCurrentJalaliMonth();
        $ranges = $this->getJalaliRanges($currentJalaliYear);
        $currentMonthRange = $ranges[$currentJalaliMonth];
        
        return response()->json([
            'totalInspections' => Inspection::count(),
            'totalCost' => Inspection::sum('total_cost') ?? 0,
            'totalContractors' => Contractor::count(),
            'inspectionsThisMonth' => Inspection::whereBetween('inspection_date', [$currentMonthRange['start'], $currentMonthRange['end']])->count(),
            'totalCostThisMonth' => Inspection::whereBetween('inspection_date', [$currentMonthRange['start'], $currentMonthRange['end']])->sum('total_cost') ?? 0,
        ]);
    }
}