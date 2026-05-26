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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hekmatinasser\Verta\Verta;
use Carbon\Carbon;

class DashboardController extends Controller
{   
    /**
     * دریافت محدوده ماه‌های شمسی برای هر سال (از 1400 تا 1425)
     * با استفاده از روش اختلاف سال
     */
    private function getJalaliRanges($year)
    {
        // محدوده‌های پایه برای سال 1405 (دقیق و تست شده)
        $baseRanges = [
            1 => ['start' => '2026-03-21', 'end' => '2026-04-20'],   // فروردین
            2 => ['start' => '2026-04-21', 'end' => '2026-05-21'],   // اردیبهشت
            3 => ['start' => '2026-05-22', 'end' => '2026-06-21'],   // خرداد
            4 => ['start' => '2026-06-22', 'end' => '2026-07-22'],   // تیر
            5 => ['start' => '2026-07-23', 'end' => '2026-08-22'],   // مرداد
            6 => ['start' => '2026-08-23', 'end' => '2026-09-22'],   // شهریور
            7 => ['start' => '2026-09-23', 'end' => '2026-10-22'],   // مهر
            8 => ['start' => '2026-10-23', 'end' => '2026-11-21'],   // آبان
            9 => ['start' => '2026-11-22', 'end' => '2026-12-21'],   // آذر
            10 => ['start' => '2026-12-22', 'end' => '2027-01-20'],  // دی
            11 => ['start' => '2027-01-21', 'end' => '2027-02-19'],  // بهمن
            12 => ['start' => '2027-02-20', 'end' => '2027-03-20'],  // اسفند
        ];
        
        // اگر سال 1405 بود، همان محدوده را برگردان
        if ($year == 1405) {
            return $baseRanges;
        }
        
        // محاسبه اختلاف سال
        $yearDiff = $year - 1405;
        
        $result = [];
        foreach ($baseRanges as $month => $range) {
            // محاسبه سال جدید برای تاریخ شروع
            $startYear = (int)substr($range['start'], 0, 4) + $yearDiff;
            $startDate = $startYear . substr($range['start'], 4);
            
            // محاسبه سال جدید برای تاریخ پایان
            $endYear = (int)substr($range['end'], 0, 4) + $yearDiff;
            $endDate = $endYear . substr($range['end'], 4);
            
            $result[$month] = [
                'start' => $startDate,
                'end' => $endDate,
            ];
        }
        
        return $result;
    }

    /**
     * دریافت سال شمسی جاری با اصلاح خطا
     */
    private function getCurrentJalaliYear()
    {
        try {
            $now = Verta::now();
            $year = $now->year;
            
            // اگر Verta سال اشتباه داد (مثل الان که 1404 میده ولی 1405 هست)
            if ($year == 1404 && $now->month >= 1) {
                $testDate = Verta::parse('1405/1/1');
                if ($testDate->toCarbon() < now()) {
                    return 1405;
                }
            }
            return $year;
        } catch (\Exception $e) {
            return 1405;
        }
    }
    
    /**
     * دریافت ماه شمسی جاری
     */
    private function getCurrentJalaliMonth()
    {
        try {
            return Verta::now()->month;
        } catch (\Exception $e) {
            return 2; // اردیبهشت
        }
    }
    
    // ========== مدیریت پیمانکاران ==========
    
    public function contractors()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        $contractors = Contractor::orderBy('name')->get();
        return view('dashboard.contractors', compact('contractors'));
    }

    public function createContractor()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        return view('dashboard.contractors-form');
    }

    public function storeContractor(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:contractors',
            'coefficient' => 'required|numeric|min:1|max:10',
            'contract_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);
        
        Contractor::create($validated);
        
        return redirect()->route('dashboard.contractors')
            ->with('success', 'پیمانکار با موفقیت اضافه شد');
    }

    public function editContractor(Contractor $contractor)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        return view('dashboard.contractors-form', compact('contractor'));
    }

    public function updateContractor(Request $request, Contractor $contractor)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:contractors,name,' . $contractor->id,
            'coefficient' => 'required|numeric|min:1|max:10',
            'contract_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);
        
        $contractor->update($validated);
        
        return redirect()->route('dashboard.contractors')
            ->with('success', 'پیمانکار با موفقیت ویرایش شد');
    }

    public function destroyContractor(Contractor $contractor)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        if ($contractor->inspections()->count() > 0) {
            return back()->with('error', 'این پیمانکار در بازدیدها استفاده شده است و قابل حذف نیست');
        }
        
        $contractor->delete();
        
        return redirect()->route('dashboard.contractors')
            ->with('success', 'پیمانکار با موفقیت حذف شد');
    }

    // ========== صفحه اصلی داشبورد ==========
    
    public function index()
    {
        // ================================================
        // دریافت سال و ماه شمسی جاری
        // ================================================
        
        $currentJalaliYear = $this->getCurrentJalaliYear();
        $currentJalaliMonth = $this->getCurrentJalaliMonth();
        
        // دریافت محدوده ماه جاری شمسی
        $ranges = $this->getJalaliRanges($currentJalaliYear);
        $currentMonthRange = $ranges[$currentJalaliMonth];
        
        // ================================================
        // آمارهای اصلی
        // ================================================
        
        $totalCost = Inspection::sum('total_cost');
        $totalInspections = Inspection::count();
        $completedInspections = Inspection::where('status', 'completed')->count();
        $draftInspections = Inspection::where('status', 'draft')->count();

        // آمار وضعیت بازدیدها
        $statusStats = [
            'completed' => Inspection::where('status', 'completed')->count(),
            'draft' => Inspection::where('status', 'draft')->count(),
            'archived' => Inspection::where('status', 'archived')->count(),
        ];
        
        // ================================================
        // آمار ماه جاری (بر اساس ماه شمسی - درست)
        // ================================================
        
        // آمار بازدیدهای این ماه (بر اساس محدوده شمسی)
        $inspectionsThisMonth = Inspection::whereBetween('inspection_date', [
            $currentMonthRange['start'], 
            $currentMonthRange['end']
        ])->count();
        
        // هزینه این ماه
        $totalCostThisMonth = Inspection::whereBetween('inspection_date', [
            $currentMonthRange['start'], 
            $currentMonthRange['end']
        ])->sum('total_cost');
        
        // آمار امروز (میلادی)
        $inspectionsToday = Inspection::whereDate('inspection_date', now()->toDateString())->count();
        
        // ================================================
        // آمار رشد
        // ================================================
        
        $now = now();
        $lastMonth = now()->subMonth();
        
        $currentMonthInspections = Inspection::whereMonth('inspection_date', $now->month)
            ->whereYear('inspection_date', $now->year)
            ->count();
        
        $lastMonthInspections = Inspection::whereMonth('inspection_date', $lastMonth->month)
            ->whereYear('inspection_date', $lastMonth->year)
            ->count();
        
        $inspectionGrowth = $lastMonthInspections > 0 
            ? round(($currentMonthInspections - $lastMonthInspections) / $lastMonthInspections * 100, 1)
            : 0;
        
        // ================================================
        // آمار تجهیزات
        // ================================================
        
        $totalEquipments = MainEquipment::count();
        $currentMonthEquipments = MainEquipment::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        
        $lastMonthEquipments = MainEquipment::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        
        $equipmentGrowth = $lastMonthEquipments > 0
            ? round(($currentMonthEquipments - $lastMonthEquipments) / $lastMonthEquipments * 100, 1)
            : 0;
        
        // ================================================
        // آمار کاربران
        // ================================================
        
        $newUsersThisMonth = User::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        
        // ================================================
        // آمار کاربر جاری
        // ================================================
        
        $myInspections = auth()->user()->inspections();
        $myCompleted = $myInspections->where('status', 'completed')->count();
        $totalMyInspections = $myInspections->count();
        
        $performanceScore = $totalMyInspections > 0 
            ? round(($myCompleted / $totalMyInspections) * 100) 
            : 0;

        $performanceText = $performanceScore >= 80 ? 'عالی' : 
                           ($performanceScore >= 60 ? 'خوب' : 
                           ($performanceScore >= 50 ? 'متوسط' : 'نیاز به تلاش بیشتر'));
        
        // ================================================
        // آرایه آمار برای ویو
        // ================================================
        
        $stats = [
            'total_inspections' => $totalInspections,
            'total_equipments' => $totalEquipments,
            'total_activities' => EquipmentActivity::count(),
            'total_consumables' => EquipmentConsumable::count(),
            'total_users' => User::count(),
            'pending_inspections' => $draftInspections,
            'my_inspections' => $totalMyInspections,
            
            'inspection_growth' => $inspectionGrowth,
            'inspection_growth_class' => $inspectionGrowth >= 0 ? 'text-success' : 'text-danger',
            'inspection_growth_icon' => $inspectionGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down',
            
            'equipment_growth' => $equipmentGrowth,
            'equipment_growth_class' => $equipmentGrowth >= 0 ? 'text-success' : 'text-danger',
            'equipment_growth_icon' => $equipmentGrowth >= 0 ? 'bi-arrow-up' : 'bi-arrow-down',
            
            'new_users_count' => $newUsersThisMonth,
            
            'my_completed' => $myCompleted,
            'performance_score' => $performanceScore,
            'performance_text' => $performanceText,
        ];

        // ================================================
        // بازدیدهای اخیر (با تاریخ شمسی)
        // ================================================
        
        $recentInspections = Inspection::with(['user', 'mainEquipments.department'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // تبدیل تاریخ به شمسی برای هر رکورد
        foreach ($recentInspections as $inspection) {
            try {
                $inspection->jalali_date = Verta::instance($inspection->inspection_date)->format('Y/m/d');
            } catch (\Exception $e) {
                $inspection->jalali_date = $inspection->inspection_date;
            }
        }

        // ================================================
        // فعالیت‌های اخیر
        // ================================================
        
        $newInspections = Inspection::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function($inspection) {
                return (object)[
                    'type' => 'inspection',
                    'icon' => 'bi-plus-circle',
                    'title' => 'بازدید جدید توسط ' . ($inspection->user->name ?? 'کاربر ناشناس'),
                    'time' => $inspection->created_at->diffForHumans(),
                    'created_at' => $inspection->created_at
                ];
            });
        
        $newUsers = User::latest()
            ->take(2)
            ->get()
            ->map(function($user) {
                return (object)[
                    'type' => 'user',
                    'icon' => 'bi-person-plus',
                    'title' => 'کاربر جدید: ' . $user->name,
                    'time' => $user->created_at->diffForHumans(),
                    'created_at' => $user->created_at
                ];
            });
        
        $recentActivities = $newInspections
            ->concat($newUsers)
            ->sortByDesc('created_at')
            ->take(6)
            ->values();

        // ================================================
        // نمودار بازدیدهای ماهانه (روش اختلاف سال)
        // ================================================
        
        $persianMonths = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];
        
        // دریافت محدوده ماه‌ها برای سال جاری
        $monthRanges = $this->getJalaliRanges($currentJalaliYear);
        
        $monthlyLabels = [];
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthlyLabels[] = $persianMonths[$month];
            
            $start = $monthRanges[$month]['start'];
            $end = $monthRanges[$month]['end'];
            
            $count = Inspection::whereBetween('inspection_date', [$start, $end])->count();
            $monthlyData[] = $count;
        }

        // ================================================
        // آمار پیمانکاران
        // ================================================
        
        $contractorStats = $this->getContractorStats();

        // ================================================
        // بازگشت به ویو
        // ================================================
        
        return view('dashboard', compact(
            'totalCost',
            'totalCostThisMonth',
            'inspectionsThisMonth',
            'inspectionsToday',
            'totalInspections',
            'completedInspections',
            'draftInspections',
            'monthlyData',
            'contractorStats',
            'stats',
            'recentInspections',
            'recentActivities',
            'monthlyLabels',
            'statusStats'
        ));
    }

    /**
     * دریافت آمار پیمانکاران
     */
    private function getContractorStats()
    {
        $contractorStats = Inspection::select('contractor_id', DB::raw('count(*) as count'))
            ->whereNotNull('contractor_id')
            ->groupBy('contractor_id')
            ->with('contractor')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->contractor->name ?? 'نامشخص',
                    'count' => $item->count
                ];
            });
        
        return $contractorStats;
    }

    // ========== لیست بازدیدها ==========
    
    public function inspections(Request $request)
    {
        $query = Inspection::with(['contractor', 'user', 'mainEquipments.department', 'mainEquipments']);
        
        // فیلترها
        if ($request->start_date) {
            $query->whereDate('inspection_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('inspection_date', '<=', $request->end_date);
        }
        if ($request->contractor_id) {
            $query->where('contractor_id', $request->contractor_id);
        }
        if ($request->department_id) {
            $query->whereHas('mainEquipments', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->equipment_type_id) {
            $query->whereHas('mainEquipments', function($q) use ($request) {
                $q->where('main_equipment_type_id', $request->equipment_type_id);
            });
        }
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        if ($request->min_equipments) {
            $query->has('mainEquipments', '>=', $request->min_equipments);
        }
        
        $inspections = $query->orderBy('inspection_date', 'desc')->paginate(15);
        
        // تبدیل تاریخ به شمسی برای هر بازدید
        foreach ($inspections as $inspection) {
            try {
                $inspection->jalali_date = Verta::instance($inspection->inspection_date)->format('Y/m/d');
            } catch (\Exception $e) {
                $inspection->jalali_date = $inspection->inspection_date;
            }
        }
        
        // داده‌های فیلترها
        $contractors = Contractor::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();  
        $equipmentTypes = MainEquipmentType::whereIn('id', [1,2,3,4])->orderBy('name')->get();
        
        return view('dashboard.inspections', compact('inspections', 'contractors', 'departments', 'equipmentTypes'));
    }

    // ========== سایر صفحات ==========
    
    public function reports()
    {
        if (!in_array(auth()->user()->role, ['admin', 'supervisor'])) {
            abort(403);
        }
        
        return redirect()->route('reports.index');
    }

    public function users()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }

        $users = User::latest()->paginate(15);
        return view('dashboard.users', compact('users'));
    }

    public function getChartData()
    {
        // سال مورد نظر برای نمودار
        $selectedYear = $this->getCurrentJalaliYear();
        
        // دریافت محدوده ماه‌ها برای سال مورد نظر
        $ranges = $this->getJalaliRanges($selectedYear);
        
        $persianMonths = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];
        
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $start = $ranges[$month]['start'];
            $end = $ranges[$month]['end'];
            
            $count = Inspection::whereBetween('inspection_date', [$start, $end])->count();
            $monthlyData[] = $count;
        }

        return response()->json([
            'labels' => array_values($persianMonths),
            'data' => $monthlyData
        ]);
    }
}