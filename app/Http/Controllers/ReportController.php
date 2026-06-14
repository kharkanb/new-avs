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
use App\Models\EquipmentConsumable;
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
            $startDate = $this->convertToGregorian($request->start_date);
            if ($startDate) {
                $query->where('inspection_date', '>=', $startDate);
            }
        }
        if ($request->end_date) {
            $endDate = $this->convertToGregorian($request->end_date);
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
            $startDate = $this->convertToGregorian($request->start_date);
            if ($startDate) {
                $query->where('inspection_date', '>=', $startDate);
            }
        }
        if ($request->end_date) {
            $endDate = $this->convertToGregorian($request->end_date);
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
/**
 * تبدیل تاریخ شمسی به میلادی
 */
private function convertToGregorian($jalaliDate)
{
    if (empty($jalaliDate)) return null;
    
    try {
        // حذف کاراکترهای غیر عددی و اسلش
        $cleanDate = preg_replace('/[^0-9\/]/', '', $jalaliDate);
        $parts = explode('/', $cleanDate);
        
        if (count($parts) != 3) return null;
        
        $year = (int)$parts[0];
        $month = (int)$parts[1];
        $day = (int)$parts[2];
        
        // بررسی اعتبار سال و ماه
        if ($year < 1300 || $year > 1500 || $month < 1 || $month > 12) {
            return null;
        }
        
        $gregorian = Verta::jalaliToGregorian($year, $month, $day);
        return sprintf('%04d-%02d-%02d', $gregorian[0], $gregorian[1], $gregorian[2]);
    } catch (\Exception $e) {
        \Log::error('Date conversion error: ' . $e->getMessage());
        return null;
    }
}
    
/**
 * گزارش مالی صورت وضعیت
 */
public function financial(Request $request)
{
    // دریافت فیلترها
    $dateFrom = $request->date_from;
    $dateTo = $request->date_to;
    $contractorId = $request->contractor_id;
    $groupBy = $request->group_by ?? 'contractor';  // مقدار پیش‌فرض: contractor
    
    // ضریب قرارداد پیش‌فرض
    $coefficient = 2.35;
    
    // تبدیل تاریخ‌ها به میلادی
    $gregorianFrom = null;
    $gregorianTo = null;
    
    if ($dateFrom) {
        $gregorianFrom = $this->convertToGregorian($dateFrom);
    }
    if ($dateTo) {
        $gregorianTo = $this->convertToGregorian($dateTo);
    }
    
    // ========== ساخت کوئری پایه برای آمار کلی ==========
    $query = Inspection::query();
    
    if ($gregorianFrom) {
        $query->whereDate('inspection_date', '>=', $gregorianFrom);
    }
    if ($gregorianTo) {
        $query->whereDate('inspection_date', '<=', $gregorianTo);
    }
    if ($contractorId) {
        $query->where('contractor_id', $contractorId);
    }
    
    // آمار کلی
    $totalInspections = $query->count();
    $totalCostAll = $query->sum('total_cost') ?? 0;
    $totalFinalCost = $totalCostAll * $coefficient;
    $avgCostPerInspection = $totalInspections > 0 ? $totalCostAll / $totalInspections : 0;
    
    // تعداد کل تجهیزات
    $totalEquipments = $query->with('mainEquipments')->get()->sum(function($i) {
        return $i->mainEquipments->count();
    });
    
    // ========== آمار پیمانکاران ==========
    $contractorStats = Inspection::select(
            'contractor_id',
            'contractor_name',
            DB::raw('COUNT(*) as inspections_count'),
            DB::raw('SUM(total_cost) as total_cost')
        )
        ->whereNotNull('contractor_id')
        ->when($gregorianFrom, function($q) use ($gregorianFrom) {
            return $q->whereDate('inspection_date', '>=', $gregorianFrom);
        })
        ->when($gregorianTo, function($q) use ($gregorianTo) {
            return $q->whereDate('inspection_date', '<=', $gregorianTo);
        })
        ->when($contractorId, function($q) use ($contractorId) {
            return $q->where('contractor_id', $contractorId);
        })
        ->groupBy('contractor_id', 'contractor_name')
        ->orderByDesc('total_cost')
        ->get()
        ->map(function($item) use ($coefficient) {
            $contractor = Contractor::find($item->contractor_id);
            $contractorCoefficient = $contractor ? ($contractor->coefficient ?? 2.35) : 2.35;
            
            return (object) [
                'contractor_id' => $item->contractor_id,
                'contractor_name' => $item->contractor_name,
                'inspections_count' => $item->inspections_count,
                'total_cost' => $item->total_cost,
                'coefficient' => $contractorCoefficient,
                'final_cost' => $item->total_cost * $contractorCoefficient,
                'avg_cost' => $item->inspections_count > 0 ? $item->total_cost / $item->inspections_count : 0
            ];
        });
    
    // ========== خلاصه فعالیت‌های فهرست بها ==========
    $activitiesQuery = DB::table('equipment_activities as ea')
        ->join('main_equipments as me', 'ea.main_equipment_id', '=', 'me.id')
        ->join('inspections as i', 'me.inspection_id', '=', 'i.id')
        ->join('contractors as c', 'i.contractor_id', '=', 'c.id')
        ->when($gregorianFrom, function($q) use ($gregorianFrom) {
            return $q->whereDate('i.inspection_date', '>=', $gregorianFrom);
        })
        ->when($gregorianTo, function($q) use ($gregorianTo) {
            return $q->whereDate('i.inspection_date', '<=', $gregorianTo);
        })
        ->when($contractorId, function($q) use ($contractorId) {
            return $q->where('i.contractor_id', $contractorId);
        });
    
    // ========== شرط گروه‌بندی برای فعالیت‌ها ==========
    if ($groupBy == 'contractor') {
        // گروه‌بندی بر اساس پیمانکار (نمایش ستون پیمانکار)
        $activitiesSummary = $activitiesQuery
            ->select(
                'c.name as contractor_name',
                'ea.code',
                'ea.title',
                'ea.unit',
                DB::raw('SUM(ea.quantity) as total_quantity'),
                DB::raw('AVG(ea.unit_price) as avg_price'),
                DB::raw('SUM(ea.total) as total_amount')
            )
            ->groupBy('c.name', 'ea.code', 'ea.title', 'ea.unit')
            ->orderBy('c.name')
            ->orderByDesc('total_amount')
            ->get();
    } else {
        // گروه‌بندی بر اساس فعالیت (بدون ستون پیمانکار)
        $activitiesSummary = $activitiesQuery
            ->select(
                'ea.code',
                'ea.title',
                'ea.unit',
                DB::raw('SUM(ea.quantity) as total_quantity'),
                DB::raw('AVG(ea.unit_price) as avg_price'),
                DB::raw('SUM(ea.total) as total_amount')
            )
            ->groupBy('ea.code', 'ea.title', 'ea.unit')
            ->orderByDesc('total_amount')
            ->get();
    }
    
    // ========== خلاصه اقلام مصرفی ==========
    $consumablesQuery = DB::table('equipment_consumables as ec')
        ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
        ->join('inspections as i', 'me.inspection_id', '=', 'i.id')
        ->join('contractors as c', 'i.contractor_id', '=', 'c.id')
        ->when($gregorianFrom, function($q) use ($gregorianFrom) {
            return $q->whereDate('i.inspection_date', '>=', $gregorianFrom);
        })
        ->when($gregorianTo, function($q) use ($gregorianTo) {
            return $q->whereDate('i.inspection_date', '<=', $gregorianTo);
        })
        ->when($contractorId, function($q) use ($contractorId) {
            return $q->where('i.contractor_id', $contractorId);
        });
    
    // ========== شرط گروه‌بندی برای اقلام مصرفی ==========
    if ($groupBy == 'contractor') {
        // گروه‌بندی بر اساس پیمانکار (نمایش ستون پیمانکار)
        $consumablesSummary = $consumablesQuery
            ->select(
                'c.name as contractor_name',
                'ec.name',
                DB::raw('SUM(ec.quantity) as total_quantity'),
                'ec.unit'
            )
            ->groupBy('c.name', 'ec.name', 'ec.unit')
            ->orderBy('c.name')
            ->orderByDesc('total_quantity')
            ->get();
    } else {
        // گروه‌بندی بر اساس نام قلم مصرفی (بدون ستون پیمانکار)
        $consumablesSummary = $consumablesQuery
            ->select(
                'ec.name',
                DB::raw('SUM(ec.quantity) as total_quantity'),
                'ec.unit'
            )
            ->groupBy('ec.name', 'ec.unit')
            ->orderByDesc('total_quantity')
            ->get();
    }
    
    // ========== بازدیدهای اخیر ==========
    $recentQuery = Inspection::with(['contractor', 'mainEquipments']);
    
    if ($gregorianFrom) {
        $recentQuery->whereDate('inspection_date', '>=', $gregorianFrom);
    }
    if ($gregorianTo) {
        $recentQuery->whereDate('inspection_date', '<=', $gregorianTo);
    }
    if ($contractorId) {
        $recentQuery->where('contractor_id', $contractorId);
    }
    
    $recentInspections = $recentQuery
        ->orderBy('inspection_date', 'desc')
        ->limit(20)
        ->get()
        ->map(function($inspection) {
            try {
                $inspection->jalali_date = Verta::instance($inspection->inspection_date)->format('Y/m/d');
            } catch (\Exception $e) {
                $inspection->jalali_date = $inspection->inspection_date;
            }
            return $inspection;
        });
    
    // ========== لیست پیمانکاران برای فیلتر ==========
    $contractors = Contractor::orderBy('name')->get();
    
    return view('reports.financial', compact(
        'totalInspections',
        'totalCostAll',
        'totalFinalCost',
        'avgCostPerInspection',
        'coefficient',
        'totalEquipments',
        'activitiesSummary',
        'consumablesSummary',
        'contractorStats',
        'contractors',
        'recentInspections',
        'dateFrom',
        'dateTo',
        'contractorId',
        'groupBy'  // ارسال groupBy به ویو
    ));
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
            $startDate = $this->convertToGregorian($request->start_date);
            if ($startDate) {
                $query->whereDate('inspection_date', '>=', $startDate);
            }
        }
        if ($request->filled('end_date')) {
            $endDate = $this->convertToGregorian($request->end_date);
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
            $startGregorian = $this->convertToGregorian($startDate);
            if ($startGregorian) {
                $inspectionsQuery->whereDate('inspection_date', '>=', $startGregorian);
            }
        }
        if ($endDate) {
            $endGregorian = $this->convertToGregorian($endDate);
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
        
        $equipmentTypesList = MainEquipmentType::all();
        foreach ($equipmentTypesList as $type) {
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
        
        $startGregorian = null;
        $endGregorian = null;
        
        if ($startDate) {
            $startGregorian = $this->convertToGregorian($startDate);
        }
        if ($endDate) {
            $endGregorian = $this->convertToGregorian($endDate);
        }
        
        if ($startGregorian) {
            $activitiesQuery->whereHas('mainEquipment.inspection', function($q) use ($startGregorian) {
                $q->whereDate('inspection_date', '>=', $startGregorian);
            });
        }
        if ($endGregorian) {
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
        $equipmentTypesForFilter = MainEquipmentType::orderBy('name')->get();
        
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
            'equipmentTypesForFilter'
        ));
    }
    
    public function advanced(Request $request)
    {
        $inspections = Inspection::with(['mainEquipments', 'contractor', 'user'])
            ->when($request->filled('start_date'), function($q) use ($request) {
                $startDate = $this->convertToGregorian($request->start_date);
                if ($startDate) {
                    $q->whereDate('inspection_date', '>=', $startDate);
                }
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $endDate = $this->convertToGregorian($request->end_date);
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
            $startGregorian = $this->convertToGregorian($startDate);
            if ($startGregorian) {
                $query->whereDate('inspection_date', '>=', $startGregorian);
            }
        } else {
            $query->whereDate('inspection_date', now()->toDateString());
        }
        
        if ($endDate) {
            $endGregorian = $this->convertToGregorian($endDate);
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
            $startGregorian = $this->convertToGregorian($startDate);
            if ($startGregorian) {
                $query->whereDate('inspection_date', '>=', $startGregorian);
            }
        } else {
            $now = Verta::now();
            $startOfMonth = Verta::create($now->year, $now->month, 1, 0, 0, 0);
            $query->whereDate('inspection_date', '>=', $startOfMonth->toCarbon());
        }
        
        if ($endDate) {
            $endGregorian = $this->convertToGregorian($endDate);
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

/**
 * خروجی Excel گزارش مالی (کامل و حرفه‌ای)
 */
public function exportFinancial(Request $request)
{
    try {
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $contractorId = $request->contractor_id;
          $groupBy = $request->group_by ?? 'contractor';  // <-- این خط را اضافه کنید
        
        $query = Inspection::with(['contractor', 'mainEquipments.activities', 'mainEquipments.mainEquipmentType']);
        
        if ($dateFrom) {
            $gregorianFrom = $this->convertToGregorian($dateFrom);
            if ($gregorianFrom) {
                $query->whereDate('inspection_date', '>=', $gregorianFrom);
            }
        }
        
        if ($dateTo) {
            $gregorianTo = $this->convertToGregorian($dateTo);
            if ($gregorianTo) {
                $query->whereDate('inspection_date', '<=', $gregorianTo);
            }
        }
        
        if ($contractorId) {
            $query->where('contractor_id', $contractorId);
        }
        
        $inspections = $query->orderBy('inspection_date', 'desc')->get();
        
        // محاسبات آماری
        $totalCost = $inspections->sum('total_cost');
        $totalFinalCost = $totalCost * $coefficient;
        $totalInspections = $inspections->count();
        $totalEquipments = $inspections->sum(function($i) {
            return $i->mainEquipments->count();
        });
        
        $totalActivities = $inspections->sum(function($i) {
            return $i->mainEquipments->sum(function($e) {
                return $e->activities->sum('quantity');
            });
        });
        
        // جمع‌آوری خلاصه فعالیت‌ها
        $activitiesSummary = [];
        foreach ($inspections as $inspection) {
            foreach ($inspection->mainEquipments as $equipment) {
                foreach ($equipment->activities as $activity) {
                    $key = $activity->code;
                    if (!isset($activitiesSummary[$key])) {
                        $activitiesSummary[$key] = [
                            'code' => $activity->code,
                            'title' => $activity->title,
                            'unit' => $activity->unit,
                            'unit_price' => $activity->unit_price,
                            'total_quantity' => 0,
                            'total_amount' => 0
                        ];
                    }
                    $activitiesSummary[$key]['total_quantity'] += $activity->quantity;
                    $activitiesSummary[$key]['total_amount'] += $activity->total;
                }
            }
        }
        $activitiesSummary = array_values($activitiesSummary);
        usort($activitiesSummary, function($a, $b) {
            return $b['total_amount'] <=> $a['total_amount'];
        });
        
        // جمع‌آوری آمار پیمانکاران
        $contractorStats = [];
        foreach ($inspections as $inspection) {
            $name = $inspection->contractor_name ?? 'نامشخص';
            if (!isset($contractorStats[$name])) {
                $contractorStats[$name] = [
                    'name' => $name,
                    'count' => 0,
                    'cost' => 0,
                    'inspections' => []
                ];
            }
            $contractorStats[$name]['count']++;
            $contractorStats[$name]['cost'] += $inspection->total_cost;
            $contractorStats[$name]['inspections'][] = $inspection;
        }
        
        // ایجاد فایل Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        // ============================================
        // شیت 1: خلاصه مالی
        // ============================================
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('خلاصه مالی');
        
        // استایل هدر
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 14, 'name' => 'Vazirmatn'],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2C3E50']]
        ];
        
        // عنوان اصلی
        $sheet1->mergeCells('A1:F1');
        $sheet1->setCellValue('A1', 'گزارش مالی صورت وضعیت');
        $sheet1->getStyle('A1')->applyFromArray($headerStyle);
        $sheet1->getStyle('A1')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        
        $sheet1->setCellValue('A2', 'تاریخ:');
        $sheet1->setCellValue('B2', verta()->format('Y/m/d'));
        $sheet1->setCellValue('A3', 'ساعت:');
        $sheet1->setCellValue('B3', verta()->format('H:i'));
        
        $row = 5;
        
        // فیلترهای اعمال شده
        if ($dateFrom || $dateTo || $contractorId) {
            $sheet1->setCellValue('A' . $row, 'فیلترهای اعمال شده:');
            $sheet1->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            if ($dateFrom) {
                $sheet1->setCellValue('A' . $row, 'از تاریخ:');
                $sheet1->setCellValue('B' . $row, $dateFrom);
                $row++;
            }
            if ($dateTo) {
                $sheet1->setCellValue('A' . $row, 'تا تاریخ:');
                $sheet1->setCellValue('B' . $row, $dateTo);
                $row++;
            }
            if ($contractorId) {
                $contractor = \App\Models\Contractor::find($contractorId);
                if ($contractor) {
                    $sheet1->setCellValue('A' . $row, 'پیمانکار:');
                    $sheet1->setCellValue('B' . $row, $contractor->name);
                    $row++;
                }
            }
            $row++;
        }
        
        // کارت‌های آماری
        $statsData = [
            ['آمار کلی', '', '', ''],
            ['تعداد بازدیدها', $totalInspections, 'تعداد تجهیزات', $totalEquipments],
            ['کل فعالیت‌ها', $totalActivities, 'تعداد پیمانکاران فعال', count($contractorStats)],
            ['هزینه کل (بدون ضریب)', number_format($totalCost) . ' ریال', 'ضریب قرارداد', number_format($coefficient, 2)],
            ['هزینه نهایی', number_format($totalFinalCost) . ' ریال', '', ''],
        ];
        
        foreach ($statsData as $dataRow) {
            $sheet1->setCellValue('A' . $row, $dataRow[0]);
            $sheet1->setCellValue('B' . $row, $dataRow[1]);
            $sheet1->setCellValue('C' . $row, $dataRow[2] ?? '');
            $sheet1->setCellValue('D' . $row, $dataRow[3] ?? '');
            $row++;
        }
        
        // تنظیم عرض ستون‌ها
        foreach (range('A', 'F') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        
        // ============================================
        // شیت 2: صورت وضعیت پیمانکاران
        // ============================================
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('صورت وضعیت پیمانکاران');
        
        $sheet2->setCellValue('A1', 'صورت وضعیت نهایی بر اساس پیمانکار');
        $sheet2->getStyle('A1')->applyFromArray($headerStyle);
        $sheet2->getStyle('A1')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        $sheet2->mergeCells('A1:F1');
        
        // هدر جدول
        $headers = ['ردیف', 'نام پیمانکار', 'تعداد بازدید', 'تعداد تجهیزات', 'کل فعالیت‌ها', 'هزینه بدون ضریب (ریال)', 'هزینه نهایی (ریال)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet2->setCellValue($col . '3', $header);
            $sheet2->getStyle($col . '3')->getFont()->setBold(true);
            $sheet2->getStyle($col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('3498DB');
            $sheet2->getStyle($col . '3')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
            $col++;
        }
        
        $row = 4;
        $index = 1;
        foreach ($contractorStats as $contractor) {
            $equipmentsCount = 0;
            $activitiesCount = 0;
            foreach ($contractor['inspections'] as $ins) {
                $equipmentsCount += $ins->mainEquipments->count();
                foreach ($ins->mainEquipments as $eq) {
                    $activitiesCount += $eq->activities->sum('quantity');
                }
            }
            
            $sheet2->setCellValue('A' . $row, $index++);
            $sheet2->setCellValue('B' . $row, $contractor['name']);
            $sheet2->setCellValue('C' . $row, $contractor['count']);
            $sheet2->setCellValue('D' . $row, $equipmentsCount);
            $sheet2->setCellValue('E' . $row, $activitiesCount);
            $sheet2->setCellValue('F' . $row, number_format($contractor['cost']));
            $sheet2->setCellValue('G' . $row, number_format($contractor['cost'] * $coefficient));
            $row++;
        }
        
        // ستون جمع کل
        $sheet2->setCellValue('B' . $row, 'جمع کل:');
        $sheet2->getStyle('B' . $row)->getFont()->setBold(true);
        $sheet2->setCellValue('C' . $row, $totalInspections);
        $sheet2->setCellValue('D' . $row, $totalEquipments);
        $sheet2->setCellValue('E' . $row, $totalActivities);
        $sheet2->setCellValue('F' . $row, number_format($totalCost));
        $sheet2->setCellValue('G' . $row, number_format($totalFinalCost));
        
        foreach (range('A', 'G') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }
        
        // ============================================
        // شیت 3: خلاصه فعالیت‌ها
        // ============================================
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('خلاصه فعالیت‌ها');
        
        $sheet3->setCellValue('A1', 'خلاصه فعالیت‌های فهرست بها');
        $sheet3->getStyle('A1')->applyFromArray($headerStyle);
        $sheet3->getStyle('A1')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        $sheet3->mergeCells('A1:F1');
        
        $headers = ['ردیف', 'کد', 'عنوان فعالیت', 'واحد', 'تعداد کل', 'قیمت واحد (ریال)', 'مبلغ کل (ریال)', 'مبلغ با ضریب (ریال)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet3->setCellValue($col . '3', $header);
            $sheet3->getStyle($col . '3')->getFont()->setBold(true);
            $sheet3->getStyle($col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('27AE60');
            $col++;
        }
        
        $row = 4;
        $index = 1;
        $totalAmountAll = 0;
        foreach ($activitiesSummary as $activity) {
            $totalAmountAll += $activity['total_amount'];
            $sheet3->setCellValue('A' . $row, $index++);
            $sheet3->setCellValue('B' . $row, $activity['code']);
            $sheet3->setCellValue('C' . $row, $activity['title']);
            $sheet3->setCellValue('D' . $row, $activity['unit']);
            $sheet3->setCellValue('E' . $row, number_format($activity['total_quantity']));
            $sheet3->setCellValue('F' . $row, number_format($activity['unit_price']));
            $sheet3->setCellValue('G' . $row, number_format($activity['total_amount']));
            $sheet3->setCellValue('H' . $row, number_format($activity['total_amount'] * $coefficient));
            $row++;
        }
        
        $sheet3->setCellValue('A' . $row, 'جمع کل:');
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet3->setCellValue('G' . $row, number_format($totalAmountAll));
        $sheet3->setCellValue('H' . $row, number_format($totalAmountAll * $coefficient));
        
        foreach (range('A', 'H') as $col) {
            $sheet3->getColumnDimension($col)->setAutoSize(true);
        }
        
        // ============================================
        // شیت 4: لیست بازدیدها
        // ============================================
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('لیست بازدیدها');
        
        $sheet4->setCellValue('A1', 'لیست بازدیدها');
        $sheet4->getStyle('A1')->applyFromArray($headerStyle);
        $sheet4->getStyle('A1')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        $sheet4->mergeCells('A1:F1');
        
        $headers = ['ردیف', 'تاریخ بازدید', 'پیمانکار', 'تعداد تجهیزات', 'کل فعالیت‌ها', 'هزینه (ریال)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet4->setCellValue($col . '3', $header);
            $sheet4->getStyle($col . '3')->getFont()->setBold(true);
            $sheet4->getStyle($col . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E67E22');
            $col++;
        }
        
        $row = 4;
        $index = 1;
        foreach ($inspections as $inspection) {
            $equipmentsCount = $inspection->mainEquipments->count();
            $activitiesCount = 0;
            foreach ($inspection->mainEquipments as $eq) {
                $activitiesCount += $eq->activities->sum('quantity');
            }
            
            $jalaliDate = verta($inspection->inspection_date)->format('Y/m/d');
            
            $sheet4->setCellValue('A' . $row, $index++);
            $sheet4->setCellValue('B' . $row, $jalaliDate);
            $sheet4->setCellValue('C' . $row, $inspection->contractor_name ?? '-');
            $sheet4->setCellValue('D' . $row, $equipmentsCount);
            $sheet4->setCellValue('E' . $row, $activitiesCount);
            $sheet4->setCellValue('F' . $row, number_format($inspection->total_cost ?? 0));
            $row++;
        }
        
        foreach (range('A', 'F') as $col) {
            $sheet4->getColumnDimension($col)->setAutoSize(true);
        }
        
        // تنظیم جهت راست به چپ برای همه شیت‌ها
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        }
        
        // ذخیره فایل
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'گزارش_مالی_صورت_وضعیت_' . verta()->format('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
        
    } catch (\Exception $e) {
        \Log::error('Error in exportFinancial: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}