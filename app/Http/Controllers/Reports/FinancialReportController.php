<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\Contractor;
use App\Models\MainEquipmentType;
use App\Models\EquipmentActivity;
use App\Models\EquipmentConsumable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hekmatinasser\Verta\Verta;

class FinancialReportController extends Controller
{
    /**
     * نمایش گزارش مالی صورت وضعیت
     */
    public function index(Request $request)
    {
        // دریافت فیلترها
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $contractorId = $request->contractor_id;
        
        // ضریب قرارداد پیش‌فرض
        $coefficient = 2.35;
        
        // ========== ساخت کوئری پایه ==========
        $query = Inspection::query();
        
        // اعمال فیلتر تاریخ (تبدیل شمسی به میلادی)
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
        
        // اعمال فیلتر پیمانکار
        if ($contractorId) {
            $query->where('contractor_id', $contractorId);
        }
        
        // ========== آمار کلی ==========
        $totalInspections = $query->count();
        $totalCost = $query->sum('total_cost') ?? 0;
        $totalFinalCost = $totalCost * $coefficient;
        $avgCostPerInspection = $totalInspections > 0 ? $totalCost / $totalInspections : 0;
        
        // ========== خلاصه فعالیت‌های فهرست بها ==========
        $activitiesSummary = DB::table('equipment_activities as ea')
            ->join('main_equipments as me', 'ea.main_equipment_id', '=', 'me.id')
            ->join('inspections as i', 'me.inspection_id', '=', 'i.id')
            ->when($dateFrom, function($q) use ($dateFrom) {
                $from = $this->convertToGregorian($dateFrom);
                if ($from) return $q->whereDate('i.inspection_date', '>=', $from);
                return $q;
            })
            ->when($dateTo, function($q) use ($dateTo) {
                $to = $this->convertToGregorian($dateTo);
                if ($to) return $q->whereDate('i.inspection_date', '<=', $to);
                return $q;
            })
            ->when($contractorId, function($q) use ($contractorId) {
                return $q->where('i.contractor_id', $contractorId);
            })
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
        
        // ========== خلاصه اقلام مصرفی ==========
        $consumablesSummary = DB::table('equipment_consumables as ec')
            ->join('main_equipments as me', 'ec.main_equipment_id', '=', 'me.id')
            ->join('inspections as i', 'me.inspection_id', '=', 'i.id')
            ->when($dateFrom, function($q) use ($dateFrom) {
                $from = $this->convertToGregorian($dateFrom);
                if ($from) return $q->whereDate('i.inspection_date', '>=', $from);
                return $q;
            })
            ->when($dateTo, function($q) use ($dateTo) {
                $to = $this->convertToGregorian($dateTo);
                if ($to) return $q->whereDate('i.inspection_date', '<=', $to);
                return $q;
            })
            ->when($contractorId, function($q) use ($contractorId) {
                return $q->where('i.contractor_id', $contractorId);
            })
            ->select(
                'ec.name',
                DB::raw('SUM(ec.quantity) as total_quantity'),
                'ec.unit'
            )
            ->groupBy('ec.name', 'ec.unit')
            ->orderByDesc('total_quantity')
            ->get();
        
        // ========== آمار پیمانکاران ==========
        $contractorStats = Inspection::select(
                'contractor_id',
                'contractor_name',
                DB::raw('COUNT(*) as inspections_count'),
                DB::raw('SUM(total_cost) as total_cost')
            )
            ->whereNotNull('contractor_id')
            ->when($dateFrom, function($q) use ($dateFrom) {
                $from = $this->convertToGregorian($dateFrom);
                if ($from) return $q->whereDate('inspection_date', '>=', $from);
                return $q;
            })
            ->when($dateTo, function($q) use ($dateTo) {
                $to = $this->convertToGregorian($dateTo);
                if ($to) return $q->whereDate('inspection_date', '<=', $to);
                return $q;
            })
            ->when($contractorId, function($q) use ($contractorId) {
                return $q->where('contractor_id', $contractorId);
            })
            ->groupBy('contractor_id', 'contractor_name')
            ->orderByDesc('total_cost')
            ->get();
        
        // ========== آمار ماهانه ==========
        $monthlyStats = $this->getMonthlyStats($dateFrom, $dateTo, $contractorId, $coefficient);
        
        // ========== بازدیدهای اخیر ==========
        $recentInspections = $query->with(['contractor', 'mainEquipments'])
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
        
        // ========== لیست انواع تجهیزات برای فیلتر ==========
        $equipmentTypes = MainEquipmentType::orderBy('name')->get();
        
        return view('reports.financial', compact(
            'totalInspections',
            'totalCost',
            'totalFinalCost',
            'avgCostPerInspection',
            'coefficient',
            'activitiesSummary',
            'consumablesSummary',
            'contractorStats',
            'contractors',
            'equipmentTypes',
            'recentInspections',
            'monthlyStats',
            'dateFrom',
            'dateTo',
            'contractorId'
        ));
    }
    
    /**
     * دریافت آمار ماهانه
     */
    private function getMonthlyStats($dateFrom, $dateTo, $contractorId, $coefficient)
    {
        $months = [];
        $persianMonths = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];
        
        for ($i = 1; $i <= 12; $i++) {
            $query = Inspection::query()
                ->when($dateFrom, function($q) use ($dateFrom) {
                    $from = $this->convertToGregorian($dateFrom);
                    if ($from) return $q->whereDate('inspection_date', '>=', $from);
                    return $q;
                })
                ->when($dateTo, function($q) use ($dateTo) {
                    $to = $this->convertToGregorian($dateTo);
                    if ($to) return $q->whereDate('inspection_date', '<=', $to);
                    return $q;
                })
                ->when($contractorId, function($q) use ($contractorId) {
                    return $q->where('contractor_id', $contractorId);
                })
                ->whereMonth('inspection_date', $i);
            
            $count = $query->count();
            $cost = $query->sum('total_cost') ?? 0;
            
            $months[] = [
                'month' => $persianMonths[$i],
                'count' => $count,
                'cost' => $cost,
                'final_cost' => $cost * $coefficient
            ];
        }
        
        return $months;
    }
    
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
     * خروجی اکسل
     */
    public function exportExcel(Request $request)
    {
        try {
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
            $contractorId = $request->contractor_id;
            $coefficient = 2.35;
            
            // دریافت داده‌ها
            $query = Inspection::query();
            
            if ($dateFrom) {
                $gregorianFrom = $this->convertToGregorian($dateFrom);
                if ($gregorianFrom) $query->whereDate('inspection_date', '>=', $gregorianFrom);
            }
            if ($dateTo) {
                $gregorianTo = $this->convertToGregorian($dateTo);
                if ($gregorianTo) $query->whereDate('inspection_date', '<=', $gregorianTo);
            }
            if ($contractorId) {
                $query->where('contractor_id', $contractorId);
            }
            
            $inspections = $query->with(['contractor', 'mainEquipments.activities'])->get();
            
            // محاسبه مجموع هزینه
            $totalCost = $inspections->sum('total_cost');
            $totalFinalCost = $totalCost * $coefficient;
            
            // ساخت فایل اکسل
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // تنظیم هدر
            $sheet->setCellValue('A1', 'گزارش مالی صورت وضعیت');
            $sheet->setCellValue('A2', 'تاریخ: ' . verta()->format('Y/m/d'));
            $sheet->setCellValue('A3', 'ساعت: ' . verta()->format('H:i'));
            
            $row = 5;
            $sheet->setCellValue('A' . $row, 'خلاصه مالی');
            $row++;
            $sheet->setCellValue('A' . $row, 'تعداد بازدیدها:');
            $sheet->setCellValue('B' . $row, $inspections->count());
            $row++;
            $sheet->setCellValue('A' . $row, 'هزینه کل (بدون ضریب):');
            $sheet->setCellValue('B' . $row, number_format($totalCost) . ' ریال');
            $row++;
            $sheet->setCellValue('A' . $row, 'ضریب قرارداد:');
            $sheet->setCellValue('B' . $row, number_format($coefficient, 2));
            $row++;
            $sheet->setCellValue('A' . $row, 'هزینه نهایی:');
            $sheet->setCellValue('B' . $row, number_format($totalFinalCost) . ' ریال');
            
            $row += 2;
            $sheet->setCellValue('A' . $row, 'صورت وضعیت بر اساس پیمانکار');
            $row++;
            $sheet->setCellValue('A' . $row, 'نام پیمانکار');
            $sheet->setCellValue('B' . $row, 'تعداد بازدید');
            $sheet->setCellValue('C' . $row, 'هزینه کل (ریال)');
            $sheet->setCellValue('D' . $row, 'هزینه نهایی (ریال)');
            
            $contractorTotals = [];
            foreach ($inspections as $inspection) {
                $name = $inspection->contractor_name ?? 'نامشخص';
                if (!isset($contractorTotals[$name])) {
                    $contractorTotals[$name] = ['count' => 0, 'cost' => 0];
                }
                $contractorTotals[$name]['count']++;
                $contractorTotals[$name]['cost'] += $inspection->total_cost;
            }
            
            foreach ($contractorTotals as $name => $data) {
                $row++;
                $sheet->setCellValue('A' . $row, $name);
                $sheet->setCellValue('B' . $row, number_format($data['count']));
                $sheet->setCellValue('C' . $row, number_format($data['cost']));
                $sheet->setCellValue('D' . $row, number_format($data['cost'] * $coefficient));
            }
            
            // تنظیم عرض ستون‌ها
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // ذخیره فایل
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'گزارش_مالی_' . verta()->format('Ymd_His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}