<?php

namespace App\Http\Controllers;

use App\Models\MainEquipmentType;
use App\Models\ChecklistTemplate;
use Illuminate\Http\Request;

class MainEquipmentTypeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = MainEquipmentType::orderBy('id', 'desc')->paginate(20);
        return view('dashboard.settings.equipment-types', compact('items'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.equipment-types-form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        // فقط یک بار اعتبارسنجی (کافی است)
        $request->validate([
            'name' => 'required|string|max:255|unique:main_equipment_types,name,NULL,id,deleted_at,NULL',
            'code' => 'nullable|string|max:50|unique:main_equipment_types,code,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
        ]);
        
        // ایجاد تجهیز جدید
        $equipmentType = MainEquipmentType::create($request->all());
        
        // ساخت خودکار چک‌لیست فقط اگر وجود نداشت
        if (!ChecklistTemplate::where('main_equipment_type_id', $equipmentType->id)->exists()) {
            ChecklistTemplate::create([
                'main_equipment_type_id' => $equipmentType->id,
                'title' => 'چک‌لیست ' . $equipmentType->name,
                'description' => 'چک‌لیست مربوط به ' . $equipmentType->name
            ]);
        }
        
        return redirect()->route('dashboard.equipment-types.index')
            ->with('success', 'نوع تجهیز با موفقیت اضافه شد و چک‌لیست اولیه برای آن ساخته شد.');
    }

    public function edit(MainEquipmentType $equipment_type)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.equipment-types-form', compact('equipment_type'));
    }

    public function update(Request $request, MainEquipmentType $equipment_type)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:main_equipment_types,name,' . $equipment_type->id . ',id,deleted_at,NULL',
            'code' => 'nullable|string|max:50|unique:main_equipment_types,code,' . $equipment_type->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string',
        ]);
        
        $equipment_type->update($request->all());
        
        // اگر چک‌لیست وجود نداشت، ایجاد کن
        if (!ChecklistTemplate::where('main_equipment_type_id', $equipment_type->id)->exists()) {
            ChecklistTemplate::create([
                'main_equipment_type_id' => $equipment_type->id,
                'title' => 'چک‌لیست ' . $equipment_type->name,
                'description' => 'چک‌لیست مربوط به ' . $equipment_type->name
            ]);
        }
        
        return redirect()->route('dashboard.equipment-types.index')
            ->with('success', 'نوع تجهیز با موفقیت ویرایش شد.');
    }

    public function destroy(MainEquipmentType $equipment_type)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        if ($equipment_type->checklistTemplate) {
            $equipment_type->checklistTemplate->items()->delete();
            $equipment_type->checklistTemplate->delete();
        }
        
        $equipment_type->delete();
        
        return redirect()->route('dashboard.equipment-types.index')
            ->with('success', 'نوع تجهیز با موفقیت حذف شد.');
    }
}