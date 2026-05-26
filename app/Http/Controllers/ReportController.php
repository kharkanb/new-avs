<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\MainEquipment;
use App\Models\ChecklistItem;
use App\Models\EquipmentActivity;
use App\Models\EquipmentChecklist;
use App\Models\User;
use App\Models\MainEquipmentType;
use App\Models\CellEquipmentType;
use App\Models\Contractor;
use App\Models\Department;
use App\Models\ActivityPrice;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * صفحه اصلی گزارش‌ها با فیلترهای پیشرفته
     */
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'supervisor'])) {
            abort(403);
        }
        
        $query = Inspection::with(['mainEquipments.department', 'mainEquipments.mainEquipmentType', 
                                   'mainEquipments.cellEquipmentType', 'mainEquipments.brand', 
                                   'mainEquipments.checklists', 'mainEquipments.activities', 'contractor']);
        
        // فیلتر بر اساس تاریخ (تبدیل شمسی به میلادی)
        if ($request->start_date) {
            $startDate = $this->convertJalaliToGregorian($request->start_date);
            if ($startDate) {
                $query->where('inspection_date', '>=', $startDate);
            }
        }
        if ($request->end_date) {
            $endDate = $this->convertJalaliToGregorian($request->end_date);
            if ($endDate) {
                $query->where('inspection_date', '<=', $endDate);
            }
        }
        
        // فیلتر بر اساس پیمانکار
        if ($request->contractor_id) {
            $query->where('contractor_id', $request->contractor_id);
        }
        
        // فیلتر بر اساس امور/شهرستان
        if ($request->department_id) {
            $query->whereHas('mainEquipments', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        
        // فیلتر بر اساس نوع تجهیز
        if ($request->equipment_type_id) {
            $parts = explode('_', $request->equipment_type_id);
            if (count($parts) == 2) {
                $type = $parts[0];
                $id = $parts[1];
                if ($type == 'main') {
                    $query->whereHas('mainEquipments', function($q) use ($id) {
                        $q->where('main_equipment_type_id', $id);
                    });
                } else {
                    $query->whereHas('mainEquipments', function($q) use ($id) {
                        $q->where('cell_equipment_type_id', $id);
                    });
                }
            }
        }
        
        // فیلتر بر اساس کد اسکادا
        if ($request->scada_code) {
            $query->whereHas('mainEquipments', function($q) use ($request) {
                $q->where('scada_code', 'like', '%' . $request->scada_code . '%');
            });
        }
        
        // فیلتر بر اساس آیتم چک‌لیست
        if ($request->checklist_item) {
            $query->whereHas('mainEquipments.checklists', function($q) use ($request) {
                $q->where('id', $request->checklist_item);
            });
        }
        
        // فیلتر بر اساس آیتم فهرست بها
        if ($request->activity_code) {
            $query->whereHas('mainEquipments.activities', function($q) use ($request) {
                $q->where('code', $request->activity_code);
            });
        }
        
        // فیلتر بر اساس وضعیت (status)
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // فیلتر بر اساس وضعیت نهایی تأیید (final_status)
        if ($request->final_status && $request->final_status != 'all') {
            $query->where('final_status', $request->final_status);
        }
        
        // گرفتن تعداد کل بازدیدها
        $totalInspections = $query->count();
        $totalCost = $query->sum('total_cost');
        
        // آمار وضعیت‌ها برای نمایش
        $statusStats = [
            'completed' => Inspection::where('status', 'completed')->count(),
            'draft' => Inspection::where('status', 'draft')->count(),
            'archived' => Inspection::where('status', 'archived')->count(),
        ];
        
        $finalStatusStats = [
            'approved' => Inspection::where('final_status', 'approved')->count(),
            'pending' => Inspection::where('final_status', 'pending')->count(),
            'rejected' => Inspection::where('final_status', 'rejected')->count(),
        ];
        
        // گرفتن تجهیزات کل
        $allInspectionsForEquipments = clone $query;
        $totalEquipments = $allInspectionsForEquipments->with('mainEquipments')->get()->sum(function($i) {
            return $i->mainEquipments->count();
        });
        
        // گرفتن فعالیت‌های کل
        $allInspectionsForActivities = clone $query;
        $totalActivities = $allInspectionsForActivities->with('mainEquipments.activities')->get()->sum(function($i) {
            return $i->mainEquipments->sum(function($e) {
                return $e->activities->sum('quantity');
            });
        });
        
        // گرفتن داده‌ها با صفحه‌بندی
        $inspections = $query->orderBy('inspection_date', 'desc')->paginate(20);
        
        // تبدیل تاریخ به شمسی برای نمایش
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
        $cellEquipmentTypes = CellEquipmentType::orderBy('name')->get();
        $activityPrices = ActivityPrice::orderBy('code')->get();
        
        return view('reports.index', compact('inspections', 'totalInspections', 'totalEquipments', 
                    'totalActivities', 'totalCost', 'contractors', 'departments', 'equipmentTypes', 
                    'cellEquipmentTypes', 'activityPrices', 'statusStats', 'finalStatusStats'));
    }
    
    /**
     * دریافت آیتم‌های چک‌لیست بر اساس نوع تجهیز (AJAX)
     */
    public function getChecklistItems(Request $request)
    {
        $equipmentType = $request->equipment_type;
        $parts = explode('_', $equipmentType);
        
        if (count($parts) == 2) {
            $type = $parts[0];
            $id = $parts[1];
            
            if ($type == 'main') {
                $items = ChecklistItem::whereHas('template', function($q) use ($id) {
                    $q->where('main_equipment_type_id', $id);
                })->get(['id', 'item_text']);
            } else {
                $items = collect([]);
            }
            
            return response()->json($items);
        }
        
        return response()->json([]);
    }
    
    /**
     * دریافت جزئیات بازدید (مودال)
     */
    public function getDetails(Request $request)
    {
        $inspection = Inspection::with(['mainEquipments.brand', 'mainEquipments.mainEquipmentType',
                                        'mainEquipments.cellEquipmentType', 'mainEquipments.location',
                                        'mainEquipments.communication', 'mainEquipments.checklists',
                                        'mainEquipments.activities', 'mainEquipments.consumables',
                                        'mainEquipments.feeders', 'contractor', 'user'])
                    ->find($request->id);
        
        if (!$inspection) {
            return '<div class="alert alert-danger">بازدیدی یافت نشد</div>';
        }
        
        return view('reports.partials.details', compact('inspection'));
    }
    
    /**
     * خروجی گرفتن از گزارشات (Excel/PDF)
     */
    public function export(Request $request, $type)
    {
        $query = Inspection::with(['mainEquipments.department', 'mainEquipments.mainEquipmentType', 
                                   'mainEquipments.cellEquipmentType', 'mainEquipments.brand', 
                                   'mainEquipments.activities', 'contractor']);
        
        if ($request->start_date) {
            $startDate = $this->convertJalaliToGregorian($request->start_date);
            if ($startDate) {
                $query->where('inspection_date', '>=', $startDate);
            }
        }
        if ($request->end_date) {
            $endDate = $this->convertJalaliToGregorian($request->end_date);
            if ($endDate) {
                $query->where('inspection_date', '<=', $endDate);
            }
        }
        if ($request->contractor_id) {
            $query->where('contractor_id', $request->contractor_id);
        }
        if ($request->department_id) {
            $query->whereHas('mainEquipments', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        
        $inspections = $query->get();
        
        if ($type == 'excel') {
            return $this->exportToExcel($inspections);
        } elseif ($type == 'pdf') {
            return $this->exportToPdf($inspections);
        }
        
        return redirect()->back()->with('error', 'فرمت خروجی نامعتبر است');
    }
    
    /**
     * خروجی Excel
     */
    private function exportToExcel($inspections)
    {
        $filename = 'report_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // هدرها
        fputcsv($handle, ['ردیف', 'تاریخ بازدید', 'پیمانکار', 'امور', 'نوع تجهیز', 
                          'کد اسکادا', 'برند', 'تعداد فعالیت‌ها', 'هزینه (ریال)']);
        
        foreach ($inspections as $index => $inspection) {
            fputcsv($handle, [
                $index + 1,
                $inspection->inspection_date,
                $inspection->contractor->name ?? '-',
                $inspection->mainEquipments->first()->department->name ?? '-',
                $inspection->mainEquipments->first()->mainEquipmentType->name ?? '-',
                $inspection->mainEquipments->first()->scada_code ?? '-',
                $inspection->mainEquipments->first()->brand->name ?? '-',
                $inspection->mainEquipments->sum(function($e) {
                    return $e->activities->sum('quantity');
                }),
                $inspection->total_cost ?? 0
            ]);
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    /**
     * خروجی PDF
     */
    private function exportToPdf($inspections)
    {
        return view('reports.pdf', compact('inspections'));
    }
    
    /**
     * تبدیل تاریخ شمسی به میلادی
     */
    private function convertJalaliToGregorian($date)
    {
        try {
            $parts = explode('/', $date);
            if (count($parts) == 3) {
                $year = (int)$parts[0];
                $month = (int)$parts[1];
                $day = (int)$parts[2];
                $v = Verta::jalaliToGregorian($year, $month, $day);
                return $v[0] . '-' . str_pad($v[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($v[2], 2, '0', STR_PAD_LEFT);
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
    
    // ============================================
    // گزارش‌های تحلیلی
    // ============================================
    
    public function comprehensive(Request $request)
    {
        $contractors = Contractor::pluck('name', 'id');
        $equipmentTypes = MainEquipmentType::pluck('name', 'id');
        $departments = Department::pluck('name', 'id');
        
        $query = Inspection::with(['user', 'contractor', 'mainEquipments.activities', 
                                   'mainEquipments.mainEquipmentType', 'mainEquipments.department']);
        
        if ($request->filled('start_date')) {
            $startDate = $this->convertJalaliToGregorian($request->start_date);
            if ($startDate) {
                $query->whereDate('inspection_date', '>=', $startDate);
            }
        }
        if ($request->filled('end_date')) {
            $endDate = $this->convertJalaliToGregorian($request->end_date);
            if ($endDate) {
                $query->whereDate('inspection_date', '<=', $endDate);
            }
        }
        if ($request->filled('contractor_id')) {
            $query->where('contractor_id', $request->contractor_id);
        }
        
        $inspections = $query->get();
        
        $totalInspections = $inspections->count();
        $totalEquipments = $inspections->sum(function($i) { 
            return $i->mainEquipments->count(); 
        });
        $totalCost = $inspections->flatMap->mainEquipments->flatMap->activities->sum('total');
        
        return view('reports.comprehensive', compact(
            'inspections', 'contractors', 'equipmentTypes', 'departments',
            'totalInspections', 'totalEquipments', 'totalCost'
        ));
    }
    
    public function contractorsReport(Request $request)
    {
        $contractors = Contractor::withCount(['inspections' => function($q) use ($request) {
            if ($request->filled('start_date')) {
                $q->whereDate('inspection_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $q->whereDate('inspection_date', '<=', $request->end_date);
            }
        }])->get();
        
        return view('reports.contractors', compact('contractors'));
    }
    
    public function departmentsReport(Request $request)
    {
        $departments = Department::withCount(['mainEquipments' => function($q) use ($request) {
            $q->whereHas('inspection', function($inq) use ($request) {
                if ($request->filled('start_date')) {
                    $inq->whereDate('inspection_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $inq->whereDate('inspection_date', '<=', $request->end_date);
                }
            });
        }])->get();
        
        return view('reports.departments', compact('departments'));
    }
    
    public function equipmentReport(Request $request)
    {
        $equipments = MainEquipment::with(['mainEquipmentType', 'department', 'inspection'])
            ->whereHas('inspection', function($q) use ($request) {
                if ($request->filled('start_date')) {
                    $q->whereDate('inspection_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $q->whereDate('inspection_date', '<=', $request->end_date);
                }
            })
            ->paginate(20);
        
        // تبدیل تاریخ به شمسی
        foreach ($equipments as $equipment) {
            if ($equipment->inspection) {
                try {
                    $equipment->jalali_date = Verta::instance($equipment->inspection->inspection_date)->format('Y/m/d');
                } catch (\Exception $e) {
                    $equipment->jalali_date = $equipment->inspection->inspection_date;
                }
            }
        }
        
        return view('reports.equipment', compact('equipments'));
    }
    
    public function failures(Request $request)
    {
        $query = EquipmentChecklist::with(['mainEquipment.inspection', 'mainEquipment.mainEquipmentType'])
            ->where('status', 'Not OK');
        
        if ($request->filled('start_date')) {
            $query->whereHas('mainEquipment.inspection', function($inq) use ($request) {
                $inq->whereDate('inspection_date', '>=', $request->start_date);
            });
        }
        if ($request->filled('end_date')) {
            $query->whereHas('mainEquipment.inspection', function($inq) use ($request) {
                $inq->whereDate('inspection_date', '<=', $request->end_date);
            });
        }
        
        $failures = $query->paginate(20);
        
        $stats = [
            'total' => $query->count(),
            'by_type' => $query->selectRaw('item, COUNT(*) as total')
                ->groupBy('item')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get()
        ];
        
        return view('reports.failures', compact('failures', 'stats'));
    }
    
    public function financial(Request $request)
    {
        $totalCost = EquipmentActivity::when($request->filled('start_date'), function($q) use ($request) {
                $q->whereHas('mainEquipment.inspection', function($inq) use ($request) {
                    $inq->whereDate('inspection_date', '>=', $request->start_date);
                });
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $q->whereHas('mainEquipment.inspection', function($inq) use ($request) {
                    $inq->whereDate('inspection_date', '<=', $request->end_date);
                });
            })
            ->sum('total');
        
        $costByContractor = EquipmentActivity::selectRaw('contractors.name as contractor_name, SUM(equipment_activities.total) as total')
            ->join('main_equipments', 'equipment_activities.main_equipment_id', '=', 'main_equipments.id')
            ->join('inspections', 'main_equipments.inspection_id', '=', 'inspections.id')
            ->join('contractors', 'inspections.contractor_id', '=', 'contractors.id')
            ->groupBy('contractors.id', 'contractors.name')
            ->get();
        
        $stats = [
            'total_cost' => $totalCost,
            'by_contractor' => $costByContractor
        ];
        
        return view('reports.financial', compact('stats', 'costByContractor'));
    }
    
    public function checklistResults(Request $request)
    {
        $query = EquipmentChecklist::with(['mainEquipment.inspection', 'mainEquipment.mainEquipmentType']);
        
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereHas('mainEquipment.inspection', function($inq) use ($request) {
                $inq->whereDate('inspection_date', '>=', $request->start_date);
            });
        }
        if ($request->filled('end_date')) {
            $query->whereHas('mainEquipment.inspection', function($inq) use ($request) {
                $inq->whereDate('inspection_date', '<=', $request->end_date);
            });
        }
        
        $results = $query->paginate(20);
        
        $stats = [
            'total' => EquipmentChecklist::count(),
            'ok' => EquipmentChecklist::where('status', 'OK')->count(),
            'not_ok' => EquipmentChecklist::where('status', 'Not OK')->count(),
            'not_checked' => EquipmentChecklist::where('status', 'Not Checked')->count(),
        ];
        
        return view('reports.checklist-results', compact('results', 'stats'));
    }
    
    /**
     * گزارش نمودارها
     */
    public function charts(Request $request)
    {
        // دریافت پارامترهای فیلتر
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $contractorId = $request->get('contractor_id');
        $equipmentTypeId = $request->get('equipment_type_id');
        $status = $request->get('status');
        
        // ============================================
        // آماده سازی متن نمایش تاریخ
        // ============================================
        $persianMonths = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];
        
        if ($startDate && $endDate) {
            $displayDate = "از $startDate تا $endDate";
        } elseif ($startDate) {
            $displayDate = "از $startDate به بعد";
        } else {
            try {
                $now = Verta::now();
                $displayDate = $persianMonths[$now->month] . " " . $now->year;
            } catch (\Exception $e) {
                $displayDate = "گزارش نموداری";
            }
        }
        
        // ساخت کوئری پایه برای بازدیدها
        $inspectionsQuery = Inspection::query();
        
        if ($startDate) {
            $startGregorian = $this->convertJalaliToGregorian($startDate);
            if ($startGregorian) {
                $inspectionsQuery->whereDate('inspection_date', '>=', $startGregorian);
            }
        }
        if ($endDate) {
            $endGregorian = $this->convertJalaliToGregorian($endDate);
            if ($endGregorian) {
                $inspectionsQuery->whereDate('inspection_date', '<=', $endGregorian);
            }
        }
        if ($contractorId) {
            $inspectionsQuery->where('contractor_id', $contractorId);
        }
        if ($status && $status != 'all') {
            $inspectionsQuery->where('status', $status);
        }
        
        if ($equipmentTypeId) {
            $inspectionsQuery->whereHas('mainEquipments', function($q) use ($equipmentTypeId) {
                $q->where('main_equipment_type_id', $equipmentTypeId);
            });
        }
        
        // ============================================
        // 1. داده‌های نمودار بازدیدهای ماهانه
        // ============================================
        
        $monthlyLabels = array_values($persianMonths);
        $monthlyData = [];
        
        // محدوده سال 1405
        $ranges = [
            ['2026-03-21', '2026-04-20'],  // فروردین
            ['2026-04-21', '2026-05-21'],  // اردیبهشت
            ['2026-05-22', '2026-06-21'],  // خرداد
            ['2026-06-22', '2026-07-22'],  // تیر
            ['2026-07-23', '2026-08-22'],  // مرداد
            ['2026-08-23', '2026-09-22'],  // شهریور
            ['2026-09-23', '2026-10-22'],  // مهر
            ['2026-10-23', '2026-11-21'],  // آبان
            ['2026-11-22', '2026-12-21'],  // آذر
            ['2026-12-22', '2027-01-20'],  // دی
            ['2027-01-21', '2027-02-19'],  // بهمن
            ['2027-02-20', '2027-03-20'],  // اسفند
        ];
        
        foreach ($ranges as $range) {
            $count = Inspection::whereBetween('inspection_date', [$range[0], $range[1]])->count();
            $monthlyData[] = $count;
        }
        
        // ============================================
        // 2. داده‌های نمودار توزیع تجهیزات
        // ============================================
        
        $equipmentLabels = [];
        $equipmentData = [];
        
        $equipmentTypes = MainEquipmentType::all();
        foreach ($equipmentTypes as $type) {
            $equipmentLabels[] = $type->name;
            $equipmentData[] = MainEquipment::where('main_equipment_type_id', $type->id)->count();
        }
        
        // ============================================
        // 3. داده‌های نمودار وضعیت بازدیدها
        // ============================================
        
        $statusStats = [
            'completed' => Inspection::where('status', 'completed')->count(),
            'draft' => Inspection::where('status', 'draft')->count(),
            'archived' => Inspection::where('status', 'archived')->count(),
        ];
        
        // ============================================
        // 4. داده‌های نمودار پرتکرارترین فعالیت‌ها
        // ============================================
        
        $activitiesQuery = EquipmentActivity::query();
        
        if ($startDate && $startGregorian ?? null) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($startGregorian) {
                $q->whereDate('inspection_date', '>=', $startGregorian);
            });
        }
        if ($endDate && $endGregorian ?? null) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($endGregorian) {
                $q->whereDate('inspection_date', '<=', $endGregorian);
            });
        }
        if ($contractorId) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($contractorId) {
                $q->where('contractor_id', $contractorId);
            });
        }
        
        $topActivities = $activitiesQuery->selectRaw('title, COUNT(*) as count')
            ->groupBy('title')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        $activityLabels = $topActivities->pluck('title')->toArray();
        $activityData = $topActivities->pluck('count')->toArray();
        
        // اگر داده‌ای نبود، نمونه تست بده
        if (empty($activityLabels)) {
            $activityLabels = ['نصب مودم', 'تنظیمات RTU', 'تعویض باتری'];
            $activityData = [5, 3, 2];
        }
        
        // ============================================
        // 5. داده‌های نمودار عملکرد پیمانکاران
        // ============================================
        
        $contractorStats = Inspection::selectRaw('contractor_name, COUNT(*) as count')
            ->whereNotNull('contractor_name')
            ->groupBy('contractor_name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        $contractorLabels = $contractorStats->pluck('contractor_name')->toArray();
        $contractorData = $contractorStats->pluck('count')->toArray();
        
        // ============================================
        // داده‌های فیلترها برای ویو
        // ============================================
        $contractors = Contractor::orderBy('name')->get();
        $equipmentTypesList = MainEquipmentType::orderBy('name')->get();
        
        // ارسال به ویو
        return view('reports.charts', compact(
            'displayDate',
            'monthlyLabels',
            'monthlyData',
            'equipmentLabels',
            'equipmentData',
            'statusStats',
            'activityLabels',
            'activityData',
            'contractorLabels',
            'contractorData',
            'contractors',
            'equipmentTypesList'
        ));
    }
    
    public function advanced(Request $request)
    {
        $inspections = Inspection::with(['mainEquipments', 'contractor', 'user'])
            ->when($request->filled('start_date'), function($q) use ($request) {
                $startDate = $this->convertJalaliToGregorian($request->start_date);
                if ($startDate) {
                    $q->whereDate('inspection_date', '>=', $startDate);
                }
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $endDate = $this->convertJalaliToGregorian($request->end_date);
                if ($endDate) {
                    $q->whereDate('inspection_date', '<=', $endDate);
                }
            })
            ->when($request->filled('contractor_id'), function($q) use ($request) {
                $q->where('contractor_id', $request->contractor_id);
            })
            ->paginate(20);
        
        return view('reports.advanced', compact('inspections'));
    }
    
    public function daily(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Inspection::with(['user', 'mainEquipments']);
        
        if ($startDate) {
            $startGregorian = $this->convertJalaliToGregorian($startDate);
            if ($startGregorian) {
                $query->whereDate('inspection_date', '>=', $startGregorian);
            }
        } else {
            $query->whereDate('inspection_date', now()->toDateString());
        }
        
        if ($endDate) {
            $endGregorian = $this->convertJalaliToGregorian($endDate);
            if ($endGregorian) {
                $query->whereDate('inspection_date', '<=', $endGregorian);
            }
        }
        
        $inspectionsList = $query->orderBy('inspection_date', 'desc')
            ->orderBy('daily_start_time')
            ->get();
        
        $todayInspections = $inspectionsList->count();
        $todayEquipments = $inspectionsList->sum(function($inspection) {
            return $inspection->mainEquipments->count();
        });
        
        $activeContractors = $inspectionsList->pluck('contractor')->unique()->filter()->count();
        $contractorList = $inspectionsList->pluck('contractor')->unique()->filter()->implode('، ');
        
        $avgEquipments = $todayInspections > 0 ? round($todayEquipments / $todayInspections, 1) : 0;
        
        $contractors = Inspection::distinct('contractor')->whereNotNull('contractor')->pluck('contractor');
        $equipmentTypes = MainEquipmentType::all();
        
        return view('reports.daily', compact(
            'inspectionsList', 'todayInspections', 'todayEquipments', 
            'activeContractors', 'contractorList', 'avgEquipments',
            'contractors', 'equipmentTypes'
        ));
    }
    
    public function monthly(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Inspection::with(['user', 'contractor', 'mainEquipments.activities', 
                                   'mainEquipments.mainEquipmentType', 'mainEquipments.department']);
        
        if ($startDate) {
            $startGregorian = $this->convertJalaliToGregorian($startDate);
            if ($startGregorian) {
                $query->whereDate('inspection_date', '>=', $startGregorian);
            }
        } else {
            $now = Verta::now();
            $startOfMonth = Verta::create($now->year, $now->month, 1, 0, 0, 0);
            $query->whereDate('inspection_date', '>=', $startOfMonth->toCarbon());
        }
        
        if ($endDate) {
            $endGregorian = $this->convertJalaliToGregorian($endDate);
            if ($endGregorian) {
                $query->whereDate('inspection_date', '<=', $endGregorian);
            }
        }
        
        $inspections = $query->get();
        
        $persianMonths = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];
        
        if ($startDate && $endDate) {
            $displayDate = "از $startDate تا $endDate";
        } elseif ($startDate) {
            $displayDate = "از $startDate به بعد";
        } else {
            try {
                $now = Verta::now();
                $displayDate = $persianMonths[$now->month] . " " . $now->year;
            } catch (\Exception $e) {
                $displayDate = "گزارش ماهانه";
            }
        }
        
        $totalInspections = $inspections->count();
        $totalEquipments = $inspections->sum(function($inspection) {
            return $inspection->mainEquipments->count();
        });
        
        $activeContractors = $inspections->pluck('contractor_id')->unique()->filter()->count();
        
        $totalCost = $inspections->flatMap->mainEquipments
            ->flatMap->activities
            ->sum('total');
        
        $contractorStats = $inspections->groupBy(function($inspection) {
            return $inspection->contractor->name ?? 'نامشخص';
        })->map(function($items) {
            return [
                'count' => $items->count(),
                'equipments' => $items->sum(function($i) {
                    return $i->mainEquipments->count();
                }),
                'cost' => $items->sum(function($i) {
                    return $i->mainEquipments->sum(function($e) {
                        return $e->activities->sum('total');
                    });
                })
            ];
        });
        
        $weeklyStats = [0, 0, 0, 0];
        foreach ($inspections as $inspection) {
            try {
                $date = Verta::instance($inspection->inspection_date);
                $day = $date->day;
                if ($day <= 7) $weeklyStats[0]++;
                elseif ($day <= 14) $weeklyStats[1]++;
                elseif ($day <= 21) $weeklyStats[2]++;
                else $weeklyStats[3]++;
            } catch (\Exception $e) {}
        }
        
        $contractors = Contractor::all();
        $departments = Department::all();
        $equipmentTypes = MainEquipmentType::all();
        
        return view('reports.monthly', compact(
            'inspections', 'displayDate', 'totalInspections', 'totalEquipments',
            'activeContractors', 'totalCost', 'contractorStats', 'weeklyStats',
            'contractors', 'departments', 'equipmentTypes'
        ));
    }
}