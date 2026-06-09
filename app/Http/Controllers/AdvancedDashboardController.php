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

// دریافت مقادیر فیلتر از ریکوئست (در ابتدای تابع index اضافه کنید)
$chartDateFrom = $request->chart_date_from;
$chartDateTo = $request->chart_date_to;
$chartDepartment = $request->chart_department;
$chartEquipmentType = $request->chart_equipment_type;
        
        // ========== ذخیره و بازیابی فیلترهای هر جدول در session ==========
        $activeTable = $request->get('active_table', session('active_table', 'recent'));
        
        // اگر فرم جدیدی ارسال شده، active_table را ذخیره کن
        if ($request->has('active_table')) {
            session(['active_table' => $request->get('active_table')]);
            $activeTable = $request->get('active_table');
        }
        
        // آرایه ذخیره فیلترهای هر جدول
        $tableFilters = [
            'recent' => session('recent_filters', []),
            'ok' => session('ok_filters', []),
            'failure' => session('failure_filters', []),
            'activities' => session('activities_filters', []),
        ];
        
        // بررسی کدام فرم ارسال شده و فیلترهای آن جدول را به‌روز کن
        $submittedTable = null;
        if ($request->has('recent_contractor') || $request->has('recent_date_from') || $request->has('recent_department')) {
            $submittedTable = 'recent';
            $tableFilters['recent'] = [
                'contractor' => $request->get('recent_contractor', ''),
                'department' => $request->get('recent_department', ''),
                'equipment_type' => $request->get('recent_equipment_type', ''),
                'date_from' => $request->get('recent_date_from', ''),
                'date_to' => $request->get('recent_date_to', ''),
            ];
            session(['recent_filters' => $tableFilters['recent']]);
            session(['active_table' => 'recent']);
            $activeTable = 'recent';
        }
        elseif ($request->has('ok_contractor') || $request->has('ok_date_from') || $request->has('ok_department')) {
            $submittedTable = 'ok';
            $tableFilters['ok'] = [
                'contractor' => $request->get('ok_contractor', ''),
                'department' => $request->get('ok_department', ''),
                'equipment_type' => $request->get('ok_equipment_type', ''),
                'date_from' => $request->get('ok_date_from', ''),
                'date_to' => $request->get('ok_date_to', ''),
            ];
            session(['ok_filters' => $tableFilters['ok']]);
            session(['active_table' => 'ok']);
            $activeTable = 'ok';
        }
        elseif ($request->has('failure_contractor') || $request->has('failure_date_from') || $request->has('failure_department')) {
            $submittedTable = 'failure';
            $tableFilters['failure'] = [
                'contractor' => $request->get('failure_contractor', ''),
                'department' => $request->get('failure_department', ''),
                'equipment_type' => $request->get('failure_equipment_type', ''),
                'date_from' => $request->get('failure_date_from', ''),
                'date_to' => $request->get('failure_date_to', ''),
            ];
            session(['failure_filters' => $tableFilters['failure']]);
            session(['active_table' => 'failure']);
            $activeTable = 'failure';
        }
        elseif ($request->has('act_contractor') || $request->has('act_date_from') || $request->has('act_department')) {
            $submittedTable = 'activities';
            $tableFilters['activities'] = [
                'contractor' => $request->get('act_contractor', ''),
                'department' => $request->get('act_department', ''),
                'equipment_type' => $request->get('act_equipment_type', ''),
                'date_from' => $request->get('act_date_from', ''),
                'date_to' => $request->get('act_date_to', ''),
            ];
            session(['activities_filters' => $tableFilters['activities']]);
            session(['active_table' => 'activities']);
            $activeTable = 'activities';
        }
        
        // اگر دکمه reset (پاک کردن فیلترها) کلیک شده باشد
        if ($request->has('reset_table')) {
            $resetTable = $request->get('reset_table');
            $tableFilters[$resetTable] = [
                'contractor' => '',
                'department' => '',
                'equipment_type' => '',
                'date_from' => '',
                'date_to' => '',
            ];
            session(["{$resetTable}_filters" => $tableFilters[$resetTable]]);
        }
        
        $perPage = $request->get('per_page', 20);
        
        // ========== لیست‌ها برای فیلترها ==========
        $contractors = Contractor::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $equipmentTypes = MainEquipmentType::orderBy('name')->get();
        
        // ========== آمارهای پایه (بدون فیلتر) ==========
        $totalCost = Inspection::sum('total_cost') ?? 0;
        $totalInspections = Inspection::count();
        $completedInspections = Inspection::where('status', 'completed')->count();
        $draftInspections = Inspection::where('status', 'draft')->count();
        
        $statusStats = [
            'completed' => $completedInspections,
            'draft' => $draftInspections,
            'archived' => Inspection::where('status', 'archived')->count(),
        ];
        
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

$myCompleted = (clone $myInspections)
    ->where('status', 'completed')
    ->count();

$totalMyInspections = (clone $myInspections)
    ->count();
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
        
        // ========== آمار تجهیزات ==========
        $totalChecklistItems = DB::table('equipment_checklists')->count();
        $okCount = DB::table('equipment_checklists')->where('status', 'OK')->count();
        $notOkCount = DB::table('equipment_checklists')->where('status', 'Not OK')->count();

// ⬇️ این خط را اضافه کنید برای نمودار دایره‌ای
$equipmentChartData = [
    'ok' => $okCount,
    'not_ok' => $notOkCount
];
        
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


        /*
        |--------------------------------------------------------------------------
        | فیلترهای نمودار
        |--------------------------------------------------------------------------
        */

        $chartDateFrom = $request->chart_date_from;

        $chartDateTo = $request->chart_date_to;

        $chartDepartment = $request->chart_department;

        $chartEquipmentType = $request->chart_equipment_type;

        /*
        |--------------------------------------------------------------------------
        | Query پایه تجهیزات
        |--------------------------------------------------------------------------
        */

        $equipmentQuery = MainEquipment::query();

        if ($chartDepartment) {

            $equipmentQuery->where(
                'department_id',
                $chartDepartment
            );
        }

        if ($chartEquipmentType) {

            $equipmentQuery->where(
                'equipment_type_id',
                $chartEquipmentType
            );
        }

/*
|--------------------------------------------------------------------------
| داده‌های ماهانه برای نمودار
|--------------------------------------------------------------------------
*/

$persianMonths = [1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان', 9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'];
$monthRanges = $this->getJalaliRanges($currentJalaliYear);
$monthlyLabels = [];
$monthlyCounts = [];

for ($month = 1; $month <= 12; $month++) {
    $monthlyLabels[] = $persianMonths[$month];
    $count = Inspection::whereBetween('inspection_date', [$monthRanges[$month]['start'], $monthRanges[$month]['end']])->count();
    $monthlyCounts[] = $count;
}

/*
|--------------------------------------------------------------------------
| نمودار خرابی بر اساس نوع تجهیز (با فیلتر)
|--------------------------------------------------------------------------
*/

$equipmentTypeQuery = DB::table('equipment_checklists as ec')
    ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
    ->join('main_equipment_types as met', 'met.id', '=', 'me.main_equipment_type_id')
    ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
    ->where('ec.status', 'Not OK');

// فیلتر تاریخ
if ($chartDateFrom) {
    $gregorianStart = $this->convertJalaliToGregorian($chartDateFrom);
    if ($gregorianStart) $equipmentTypeQuery->whereDate('i.inspection_date', '>=', $gregorianStart);
}
if ($chartDateTo) {
    $gregorianEnd = $this->convertJalaliToGregorian($chartDateTo);
    if ($gregorianEnd) $equipmentTypeQuery->whereDate('i.inspection_date', '<=', $gregorianEnd);
}

// فیلتر امور
if ($chartDepartment) {
    $equipmentTypeQuery->where('me.department_id', $chartDepartment);
}

// فیلتر نوع تجهیز
if ($chartEquipmentType) {
    $equipmentTypeQuery->where('me.main_equipment_type_id', $chartEquipmentType);
}

$equipmentTypeChartData = $equipmentTypeQuery
    ->select('met.name', DB::raw('COUNT(DISTINCT me.id) as total'))
    ->groupBy('met.name')
    ->get();

$chart_equipment_types = [
    'labels' => $equipmentTypeChartData->pluck('name'),
    'data' => $equipmentTypeChartData->pluck('total'),
];


/*
|--------------------------------------------------------------------------
| نمودار خرابی بر اساس امور (با فیلتر)
|--------------------------------------------------------------------------
*/

$departmentQuery = DB::table('equipment_checklists as ec')
    ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
    ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
    ->join('departments', 'departments.id', '=', 'i.department_id')
    ->where('ec.status', 'Not OK');

// فیلتر تاریخ
if ($chartDateFrom) {
    $gregorianStart = $this->convertJalaliToGregorian($chartDateFrom);
    if ($gregorianStart) $departmentQuery->whereDate('i.inspection_date', '>=', $gregorianStart);
}
if ($chartDateTo) {
    $gregorianEnd = $this->convertJalaliToGregorian($chartDateTo);
    if ($gregorianEnd) $departmentQuery->whereDate('i.inspection_date', '<=', $gregorianEnd);
}

// فیلتر امور
if ($chartDepartment) {
    $departmentQuery->where('i.department_id', $chartDepartment);
}

// فیلتر نوع تجهیز
if ($chartEquipmentType) {
    $departmentQuery->where('me.main_equipment_type_id', $chartEquipmentType);
}

$departmentChartData = $departmentQuery
    ->select(
        'departments.name',

        DB::raw('COUNT(DISTINCT me.id) as equipment_count'),

        DB::raw('COUNT(ec.id) as failure_count')
    )
    ->groupBy('departments.name')
    ->get();


$chart_departments = [
    'labels' => $departmentChartData->pluck('name'),

    'equipment_data' => $departmentChartData->pluck('equipment_count'),

    'failure_data' => $departmentChartData->pluck('failure_count'),
];


/*
|--------------------------------------------------------------------------
| بیشترین فعالیت‌ها
|--------------------------------------------------------------------------
*/

$activityChartData = EquipmentActivity::select(
        'title',
        DB::raw('COUNT(id) as total')
    )
    ->groupBy('title')
    ->orderByDesc('total')
    ->limit(10)
    ->get();

$chart_activities = [
    'labels' => $activityChartData->pluck('title'),
    'data' => $activityChartData->pluck('total'),
];

/*
|--------------------------------------------------------------------------
| برندهای دارای خرابی (با فیلتر)
|--------------------------------------------------------------------------
*/

$brandQuery = DB::table('equipment_checklists as ec')
    ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
    ->leftJoin('brands as b', 'me.brand_id', '=', 'b.id')
    ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
    ->where('ec.status', 'Not OK')
    ->whereNotNull('me.brand_id');

// فیلتر تاریخ
if ($chartDateFrom) {
    $gregorianStart = $this->convertJalaliToGregorian($chartDateFrom);
    if ($gregorianStart) $brandQuery->whereDate('i.inspection_date', '>=', $gregorianStart);
}
if ($chartDateTo) {
    $gregorianEnd = $this->convertJalaliToGregorian($chartDateTo);
    if ($gregorianEnd) $brandQuery->whereDate('i.inspection_date', '<=', $gregorianEnd);
}

// فیلتر امور
if ($chartDepartment) {
    $brandQuery->where('me.department_id', $chartDepartment);
}

// فیلتر نوع تجهیز
if ($chartEquipmentType) {
    $brandQuery->where('me.main_equipment_type_id', $chartEquipmentType);
}



$brandChartData = $brandQuery
    ->select(
        'b.name as brand',
        DB::raw('COUNT(DISTINCT me.id) as total')
    )
    ->groupBy('b.id', 'b.name')
    ->orderByDesc('total')
    ->limit(10)
    ->get();

$chart_brands = [
    'labels' => $brandChartData->pluck('brand'),
    'data' => $brandChartData->pluck('total'),
];

/*
|--------------------------------------------------------------------------
| داده‌های ماهانه برای نمودار (با فیلترهای تاریخ و امور و نوع تجهیز)
|--------------------------------------------------------------------------
*/

$persianMonths = [1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان', 9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'];
$monthRanges = $this->getJalaliRanges($currentJalaliYear);
$monthlyLabels = [];
$monthlyCounts = [];

for ($month = 1; $month <= 12; $month++) {
    $monthlyLabels[] = $persianMonths[$month];
    
    $query = Inspection::whereBetween('inspection_date', [$monthRanges[$month]['start'], $monthRanges[$month]['end']]);
    
    // فیلترهای اضافی برای روند ماهانه
    if ($chartDepartment) {
        $query->where('department_id', $chartDepartment);
    }
    
    // اگر نوع تجهیز فیلتر شده، فقط بازدیدهایی که آن تجهیز را دارند
    if ($chartEquipmentType) {
        $query->whereHas('mainEquipments', function($q) use ($chartEquipmentType) {
            $q->where('main_equipment_type_id', $chartEquipmentType);
        });
    }
    
    $monthlyCounts[] = $query->count();
}

$chart_trend = [
    'labels' => $monthlyLabels,
    'data' => $monthlyCounts,
];
// ==============================


/*
|--------------------------------------------------------------------------
| نمودار توزیع Not OK بر اساس نوع تجهیز اصلی
|--------------------------------------------------------------------------
*/

$notOkByEquipmentType = DB::table('equipment_checklists as ec')
    ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
    ->join('main_equipment_types as met', 'me.main_equipment_type_id', '=', 'met.id')
    ->where('ec.status', 'Not OK')
    ->select('met.name as equipment_type', DB::raw('COUNT(DISTINCT ec.id) as not_ok_count'))
    ->groupBy('met.name')
    ->orderByDesc('not_ok_count')
    ->get();

$chart_not_ok_by_equipment = [
    'labels' => $notOkByEquipmentType->pluck('equipment_type'),
    'data' => $notOkByEquipmentType->pluck('not_ok_count'),
];


/*
|--------------------------------------------------------------------------
| نمودارهای 5 آیتم Not OK برتر برای هر نوع تجهیز (با فیلتر)
|--------------------------------------------------------------------------
*/

$equipmentTypesForCharts = ['سکسیونر', 'ریکلوزر', 'سکشنالایزر', 'فالت دتکتور'];
$chart_top_failures = [];

foreach ($equipmentTypesForCharts as $eqType) {
    $equipmentTypeId = MainEquipmentType::where('name', $eqType)->value('id');
    
    if ($equipmentTypeId) {
        $failureQuery = DB::table('equipment_checklists as ec')
            ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
            ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
            ->where('me.main_equipment_type_id', $equipmentTypeId)
            ->where('ec.status', 'Not OK');
        
        // فیلتر تاریخ
        if ($chartDateFrom) {
            $gregorianStart = $this->convertJalaliToGregorian($chartDateFrom);
            if ($gregorianStart) $failureQuery->whereDate('i.inspection_date', '>=', $gregorianStart);
        }
        if ($chartDateTo) {
            $gregorianEnd = $this->convertJalaliToGregorian($chartDateTo);
            if ($gregorianEnd) $failureQuery->whereDate('i.inspection_date', '<=', $gregorianEnd);
        }
        
        // فیلتر امور
        if ($chartDepartment) {
            $failureQuery->where('me.department_id', $chartDepartment);
        }
        
        // فیلتر نوع تجهیز
        if ($chartEquipmentType && $chartEquipmentType != $equipmentTypeId) {
            $chart_top_failures[$eqType] = ['labels' => [], 'data' => []];
            continue;
        }
        
        $topFailures = $failureQuery->select('ec.item', DB::raw('COUNT(*) as total'))
            ->groupBy('ec.item')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        $chart_top_failures[$eqType] = [
            'labels' => $topFailures->pluck('item')->map(function($item) {
                return mb_strlen($item) > 30 ? mb_substr($item, 0, 27) . '...' : $item;
            }),
            'data' => $topFailures->pluck('total'),
        ];
    } else {
        $chart_top_failures[$eqType] = ['labels' => [], 'data' => []];
    }
}

        
        // ========== آخرین بازدیدها با فیلترهای مستقل ==========
        $recentFilters = $tableFilters['recent'];
        $gregorianStartRecent = !empty($recentFilters['date_from']) ? $this->convertJalaliToGregorian($recentFilters['date_from']) : null;
        $gregorianEndRecent = !empty($recentFilters['date_to']) ? $this->convertJalaliToGregorian($recentFilters['date_to']) : null;
        
        $recentInspectionsQuery = Inspection::with(['user', 'mainEquipments.department', 'contractor']);
        
        if (!empty($recentFilters['contractor'])) {
            $recentInspectionsQuery->where('contractor_id', $recentFilters['contractor']);
        }
        if (!empty($recentFilters['department'])) {
            $recentInspectionsQuery->where('department_id', $recentFilters['department']);
        }
        if (!empty($recentFilters['equipment_type'])) {
            $recentInspectionsQuery->whereHas('mainEquipments', function($q) use ($recentFilters) {
                $q->where('main_equipment_type_id', $recentFilters['equipment_type']);
            });
        }
        if ($gregorianStartRecent) {
            $recentInspectionsQuery->whereDate('inspection_date', '>=', $gregorianStartRecent);
        }
        if ($gregorianEndRecent) {
            $recentInspectionsQuery->whereDate('inspection_date', '<=', $gregorianEndRecent);
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
        
        // ========== گزارش تجهیزات سالم (OK) با فیلترهای مستقل ==========
        $okFilters = $tableFilters['ok'];
        $gregorianStartOk = !empty($okFilters['date_from']) ? $this->convertJalaliToGregorian($okFilters['date_from']) : null;
        $gregorianEndOk = !empty($okFilters['date_to']) ? $this->convertJalaliToGregorian($okFilters['date_to']) : null;
        
$okQuery = DB::table('equipment_checklists as ec')
    ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
    ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
    ->leftJoin('contractors as c', 'i.contractor_id', '=', 'c.id')
    ->leftJoin('main_equipment_types as met', 'me.main_equipment_type_id', '=', 'met.id')
    ->leftJoin('posts as p', 'me.post_id', '=', 'p.id')
    ->where('ec.status', 'OK');
        
        if (!empty($okFilters['contractor'])) {
            $okQuery->where('i.contractor_id', $okFilters['contractor']);
        }
        if (!empty($okFilters['department'])) {
            $okQuery->where('i.department_id', $okFilters['department']);
        }
        if (!empty($okFilters['equipment_type'])) {
            $okQuery->where('me.main_equipment_type_id', $okFilters['equipment_type']);
        }
        if ($gregorianStartOk) {
            $okQuery->whereDate('i.inspection_date', '>=', $gregorianStartOk);
        }
        if ($gregorianEndOk) {
            $okQuery->whereDate('i.inspection_date', '<=', $gregorianEndOk);
        }
$okEquipments = $okQuery
    ->select(
        'ec.id',
        'ec.status',
        'me.scada_code',
        'me.id as equipment_id',
        'c.name as contractor_name',
        'i.inspection_date',
        'met.name as equipment_type',
        'p.name as location'
    )
    ->orderBy('ec.created_at', 'desc')
    ->paginate($perPage)
    ->through(function ($item) {
        return [
            'id' => $item->id,
            'equipment_type' => $item->equipment_type ?? 'نامشخص',
            'scada_code' => $item->scada_code ?? '---',
            'inspection_date' => $item->inspection_date
                ? Verta::instance($item->inspection_date)->format('Y/m/d')
                : '-',
            'contractor_name' => $item->contractor_name ?? '-',
            'location' => $item->location ?? '-',
            'status' => 'OK',
        ];
    });
        
$okEquipments = $okQuery
    ->select(
        'ec.id',
        'ec.status',
        'me.scada_code',
        'me.id as equipment_id',
        'c.name as contractor_name',
        'i.inspection_date',
        'met.name as equipment_type',
        'p.name as location'
    )
    ->orderBy('ec.created_at', 'desc')
    ->distinct()
    ->paginate($perPage)
    ->through(function ($item) {
        return [
            'id' => $item->id,
            'equipment_type' => $item->equipment_type ?? 'نامشخص',
            'scada_code' => $item->scada_code ?? '---',
            'inspection_date' => $item->inspection_date
                ? Verta::instance($item->inspection_date)->format('Y/m/d')
                : '-',
            'contractor_name' => $item->contractor_name ?? '-',
            'location' => $item->location ?? '-',
            'status' => 'OK',
        ];
    });        
        // ========== گزارش تجهیزات خراب (Not OK) با فیلترهای مستقل ==========
        $failureFilters = $tableFilters['failure'];
        $gregorianStartFailure = !empty($failureFilters['date_from']) ? $this->convertJalaliToGregorian($failureFilters['date_from']) : null;
        $gregorianEndFailure = !empty($failureFilters['date_to']) ? $this->convertJalaliToGregorian($failureFilters['date_to']) : null;
        
        $failureQuery = DB::table('equipment_checklists as ec')
            ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
->leftJoin('contractors as c', 'i.contractor_id', '=', 'c.id')
            ->leftJoin('main_equipment_types as met', 'me.main_equipment_type_id', '=', 'met.id')
            ->leftJoin('posts as p', 'me.post_id', '=', 'p.id')
            ->where('ec.status', 'Not OK');
        
        if (!empty($failureFilters['contractor'])) {
            $failureQuery->where('i.contractor_id', $failureFilters['contractor']);
        }
        if (!empty($failureFilters['department'])) {
            $failureQuery->where('i.department_id', $failureFilters['department']);
        }
        if (!empty($failureFilters['equipment_type'])) {
            $failureQuery->where('me.main_equipment_type_id', $failureFilters['equipment_type']);
        }
        if ($gregorianStartFailure) {
            $failureQuery->whereDate('i.inspection_date', '>=', $gregorianStartFailure);
        }
        if ($gregorianEndFailure) {
            $failureQuery->whereDate('i.inspection_date', '<=', $gregorianEndFailure);
        }
        
        $failureEquipments = $failureQuery->select(
                'ec.id',
                'ec.item',
                'ec.status',
                'ec.description as failure_description',
                'me.scada_code',
                'me.id as equipment_id',
                'c.name as contractor_name',
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
        
        // ========== فعالیت‌های انجام شده با فیلترهای مستقل ==========
        $activitiesFilters = $tableFilters['activities'];
        $gregorianStartActivities = !empty($activitiesFilters['date_from']) ? $this->convertJalaliToGregorian($activitiesFilters['date_from']) : null;
        $gregorianEndActivities = !empty($activitiesFilters['date_to']) ? $this->convertJalaliToGregorian($activitiesFilters['date_to']) : null;
        
        $activitiesQuery = EquipmentActivity::with(['mainEquipment.inspection']);
        
        if (!empty($activitiesFilters['contractor'])) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($activitiesFilters) {
                $q->where('contractor_id', $activitiesFilters['contractor']);
            });
        }
        if (!empty($activitiesFilters['department'])) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($activitiesFilters) {
                $q->where('department_id', $activitiesFilters['department']);
            });
        }
        if (!empty($activitiesFilters['equipment_type'])) {
            $activitiesQuery->whereHas('mainEquipment', function($q) use ($activitiesFilters) {
                $q->where('main_equipment_type_id', $activitiesFilters['equipment_type']);
            });
        }
        if ($gregorianStartActivities) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($gregorianStartActivities) {
                $q->whereDate('inspection_date', '>=', $gregorianStartActivities);
            });
        }
        if ($gregorianEndActivities) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($gregorianEndActivities) {
                $q->whereDate('inspection_date', '<=', $gregorianEndActivities);
            });
        }
        
        $activities = $activitiesQuery->orderBy('created_at', 'desc')->paginate($perPage);
        
        // ========== داده‌های نمودار ماهانه ==========
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
        
        // ========== پیمانکاران برتر ==========
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
        
        // ========== بازدیدهای سالانه ==========
        $yearlyInspections = [
            'years' => [2025, 2026],
            'counts' => [
                Inspection::whereYear('inspection_date', 2025)->count(),
                Inspection::whereYear('inspection_date', 2026)->count()
            ]
        ];
        
        // ========== فهرست بها ==========
        $priceList = ActivityPrice::orderBy('code')->get()->map(function($price) {
            return [
                'code' => $price->code,
                'title' => $price->title,
                'unit' => $price->unit,
                'unit_price' => $price->unit_price,
                'description' => $price->description,
            ];
        });
        
        // ========== فعالیت‌های اخیر ==========
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

$equipmentChartData = [
    'ok' => $okEquipmentsCount,
    'not_ok' => $notOkEquipmentsCount,
];
        
        $recentActivities = $newInspections->concat($newUsers)->sortByDesc('created_at')->take(6)->values();
        
        // ========== محاسبه درصدها ==========
        $completedPercent = $totalInspections > 0 ? ($completedInspections / $totalInspections) * 100 : 0;
        $draftPercent = $totalInspections > 0 ? ($draftInspections / $totalInspections) * 100 : 0;
        $archivedPercent = $totalInspections > 0 ? (($statusStats['archived'] ?? 0) / $totalInspections) * 100 : 0;
        


// ========== تبدیل Paginator به آرایه برای استفاده در اکسل ==========
$okEquipmentsArray = $okEquipments->items();  // گرفتن آیتم‌های صفحه جاری
$failureEquipmentsArray = $failureEquipments->items();
$activitiesArray = $activities->items();




// ========== بازگشت به ویو ==========
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
    'equipmentChartData' => $equipmentChartData,
    'chart_equipment_types' => $chart_equipment_types,
    'chart_departments' => $chart_departments,
    'chart_activities' => $chart_activities,
    'chart_brands' => $chart_brands,
    'chart_trend' => $chart_trend,
       'equipmentChartData' => $equipmentChartData,
    'recentFilters' => $recentFilters,
    'okFilters' => $okFilters,
    'failureFilters' => $failureFilters,
    'activitiesFilters' => $activitiesFilters,
    'activeTable' => $activeTable,
    'monthlyLabels' => $monthlyLabels,
    'monthlyData' => $monthlyCounts,
    'chart_trend' => $chart_trend,
    'okEquipmentsForExport' => $okEquipmentsArray,
    'failureEquipmentsForExport' => $failureEquipmentsArray,
    'activitiesForExport' => $activitiesArray,
       'chart_not_ok_by_equipment' => $chart_not_ok_by_equipment,
       'chart_top_failures' => $chart_top_failures,

]);
    }

public function exportData()
{
    // همه بازدیدها
    $recentInspections = Inspection::with(['contractor'])->orderBy('created_at', 'desc')->get();
    
    // تجهیزات OK
    $okEquipments = DB::table('equipment_checklists as ec')
        ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
        ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
->leftJoin('contractors as c', 'i.contractor_id', '=', 'c.id')
        ->leftJoin('main_equipment_types as met', 'me.main_equipment_type_id', '=', 'met.id')
        ->leftJoin('posts as p', 'me.post_id', '=', 'p.id')
        ->where('ec.status', 'OK')
        ->select('ec.id', 'me.scada_code', 'c.name as contractor_name', 'i.inspection_date', 'met.name as equipment_type', 'p.name as location')
        ->orderBy('ec.created_at', 'desc')
        ->groupBy(
    'me.id',
    'ec.id',
    'ec.status',
    'me.scada_code',
    'c.name',
    'i.inspection_date',
    'met.name',
    'p.name'
)
        ->get();
    
    // تجهیزات Not OK
    $failureEquipments = DB::table('equipment_checklists as ec')
        ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
        ->leftJoin('inspections as i', 'me.inspection_id', '=', 'i.id')
->leftJoin('contractors as c', 'i.contractor_id', '=', 'c.id')
        ->leftJoin('main_equipment_types as met', 'me.main_equipment_type_id', '=', 'met.id')
        ->leftJoin('posts as p', 'me.post_id', '=', 'p.id')
        ->where('ec.status', 'Not OK')
        ->select('ec.id', 'ec.item', 'ec.description', 'me.scada_code', 'c.name as contractor_name', 'i.inspection_date', 'met.name as equipment_type', 'p.name as location')
        ->orderBy('ec.created_at', 'desc')
        ->get();
    
    // فعالیت‌ها
    $activities = EquipmentActivity::with(['mainEquipment.inspection'])->orderBy('created_at', 'desc')->get();
    
    // فهرست بها
    $priceList = ActivityPrice::orderBy('code')->get();
    
    return response()->json([
        'recentInspections' => $recentInspections,
        'okEquipments' => $okEquipments,
        'failureEquipments' => $failureEquipments,
        'activities' => $activities,
        'priceList' => $priceList,
    ]);
}
    
    public function getContractorDetails($name)
    {
$contractor = Contractor::where('name', $name)->first();

if (!$contractor) {
    return response()->json([
        'inspections_count' => 0,
        'total_cost' => 0,
        'avg_cost' => 0,
        'last_inspection' => '-',
    ]);
}

$inspections = Inspection::where(
    'contractor_id',
    $contractor->id
)->get();
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