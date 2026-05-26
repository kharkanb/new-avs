<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\Contractor;  
use App\Models\Department; 
use App\Models\MainEquipmentType;
use Hekmatinasser\Verta\Verta;

class InspectionWebController extends Controller
{
    /**
     * نمایش جزئیات یک بازدید
     */
    public function show($id)
    {
        $inspection = Inspection::with([
            'mainEquipments.mainEquipmentType',
            'mainEquipments.cellEquipmentType',
            'mainEquipments.brand',
            'mainEquipments.location',
            'mainEquipments.communication',
            'mainEquipments.checklists',
            'mainEquipments.activities',
            'mainEquipments.consumables',
            'contractor',
            'user'
        ])->findOrFail($id);
        
        return view('inspections.show', compact('inspection'));
    }

    /**
     * نمایش فرم ویرایش بازدید
     */
    public function edit($id)
    {
        $inspection = Inspection::with([
            'mainEquipments.mainEquipmentType',
            'mainEquipments.cellEquipmentType',
            'mainEquipments.brand',
            'mainEquipments.location',
            'mainEquipments.communication',
            'mainEquipments.checklists',
            'mainEquipments.activities',
            'mainEquipments.consumables',
            'contractor'
        ])->findOrFail($id);
        
        return view('inspections.edit', compact('inspection'));
    }

    /**
     * ذخیره بازدید جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contractor_name' => 'nullable|string|max:255',
            'contractor_id' => 'nullable|exists:contractors,id',
            'inspection_date' => 'required|date',
            'total_cost' => 'nullable|numeric',
            'status' => 'required|in:draft,completed,archived',
            'notes' => 'nullable|string',
        ]);

        $inspection = Inspection::create($validated);
        
        activity()
            ->causedBy(auth()->user())
            ->event('created')
            ->performedOn($inspection)
            ->withProperties([
                'ip' => $request->ip(),
                'inspection_data' => $request->except(['_token', '_method'])
            ])
            ->log('ایجاد بازدید جدید');
        
        return redirect()->route('inspection.show', $inspection->id)
            ->with('success', 'بازدید با موفقیت ثبت شد');
    }

    /**
     * به‌روزرسانی بازدید
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'contractor_name' => 'nullable|string|max:255',
            'contractor_id' => 'nullable|exists:contractors,id',
            'inspection_date' => 'required|date',
            'total_cost' => 'nullable|numeric',
            'status' => 'required|in:draft,completed,archived',
            'notes' => 'nullable|string',
        ]);

        $inspection = Inspection::findOrFail($id);
        $oldData = $inspection->toArray();
        
        $inspection->update($validated);
        
        activity()
            ->causedBy(auth()->user())
            ->event('updated')
            ->performedOn($inspection)
            ->withProperties([
                'ip' => $request->ip(),
                'old' => $oldData,
                'new' => $request->except(['_token', '_method'])
            ])
            ->log('ویرایش بازدید');
        
        return redirect()->route('inspection.show', $inspection->id)
            ->with('success', 'بازدید با موفقیت ویرایش شد');
    }

    /**
     * لیست بازدیدهای کاربر جاری
     */
    public function myInspections()
    {
        $inspections = Inspection::with([
            'mainEquipments.mainEquipmentType',
            'mainEquipments.cellEquipmentType',
            'contractor'
        ])
        ->where('user_id', auth()->id())
        ->orderBy('inspection_date', 'desc')
        ->paginate(20);
        
        return view('inspections-list', compact('inspections'));
    }
    
    /**
     * لیست همه بازدیدها (برای مدیر)
     */
 public function index(Request $request)
{
    if (auth()->user()->role !== 'admin') {
        abort(403, 'دسترسی غیرمجاز');
    }
    
    // تابع کمکی برای تبدیل اعداد فارسی به انگلیسی
    function convertPersianToEnglish($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($persian, $english, $string);
    }
    
    // دریافت مقادیر فیلتر
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');
    $contractorId = $request->get('contractor');
    $departmentId = $request->get('department');
    $equipmentTypeId = $request->get('equipment_type');
    
    // تبدیل تاریخ شمسی به میلادی
    $gregorianStart = null;
    $gregorianEnd = null;
    
    if ($startDate) {
        try {
            $cleanDate = convertPersianToEnglish($startDate);
            $cleanDate = preg_replace('/[^0-9\/]/', '', $cleanDate);
            $parts = explode('/', $cleanDate);
            if (count($parts) == 3) {
                $gregorian = Verta::jalaliToGregorian((int)$parts[0], (int)$parts[1], (int)$parts[2]);
                $gregorianStart = sprintf('%04d-%02d-%02d', $gregorian[0], $gregorian[1], $gregorian[2]);
            }
        } catch (\Exception $e) {
            \Log::error('تاریخ شروع نامعتبر: ' . $startDate);
        }
    }
    
    if ($endDate) {
        try {
            $cleanDate = convertPersianToEnglish($endDate);
            $cleanDate = preg_replace('/[^0-9\/]/', '', $cleanDate);
            $parts = explode('/', $cleanDate);
            if (count($parts) == 3) {
                $gregorian = Verta::jalaliToGregorian((int)$parts[0], (int)$parts[1], (int)$parts[2]);
                $gregorianEnd = sprintf('%04d-%02d-%02d', $gregorian[0], $gregorian[1], $gregorian[2]);
            }
        } catch (\Exception $e) {
            \Log::error('تاریخ پایان نامعتبر: ' . $endDate);
        }
    }
    
    // لیست‌ها برای فیلترها
    $contractors = Contractor::orderBy('name')->get();
    $departments = Department::orderBy('name')->get();
    $equipmentTypes = MainEquipmentType::orderBy('name')->get();
    
    // ساخت کوئری
    $query = Inspection::with(['mainEquipments.mainEquipmentType', 'mainEquipments.cellEquipmentType', 'contractor', 'user', 'department']);
    
    // اعمال فیلترها
    if ($contractorId) {
        $query->where('contractor_id', $contractorId);
    }
    if ($departmentId) {
        $query->where('department_id', $departmentId);
    }
    if ($equipmentTypeId) {
        $query->whereHas('mainEquipments', function($q) use ($equipmentTypeId) {
            $q->where('main_equipment_type_id', $equipmentTypeId);
        });
    }
    if ($gregorianStart) {
        $query->whereDate('inspection_date', '>=', $gregorianStart);
    }
    if ($gregorianEnd) {
        $query->whereDate('inspection_date', '<=', $gregorianEnd);
    }
    
    $inspections = $query->orderBy('inspection_date', 'desc')->paginate(20);
    
    return view('dashboard.inspections', compact('inspections', 'contractors', 'departments', 'equipmentTypes'));
}

    /**
     * حذف بازدید
     */
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        $inspection = Inspection::findOrFail($id);
        
        activity()
            ->causedBy(auth()->user())
            ->event('deleted')
            ->performedOn($inspection)
            ->withProperties([
                'ip' => request()->ip(),
                'deleted_data' => $inspection->toArray()
            ])
            ->log('حذف بازدید');
        
        $inspection->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'بازدید با موفقیت حذف شد']);
        }
        
        return redirect()->route('dashboard.inspections')
            ->with('success', 'بازدید با موفقیت حذف شد');
    }
}