<?php

namespace App\Http\Controllers;

use App\Models\MainEquipmentType;
use Illuminate\Http\Request;

class MainEquipmentTypeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = MainEquipmentType::orderBy('name')->paginate(20);
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
    
    $request->validate([
        'name' => 'required|string|max:255|unique:main_equipment_types',
        'code' => 'nullable|string|max:50|unique:main_equipment_types',
        'description' => 'nullable|string'
    ]);
    
    MainEquipmentType::create($request->only(['name', 'code', 'description']));
    
    return redirect()->route('dashboard.equipment-types.index')->with('success', 'نوع تجهیز با موفقیت اضافه شد');
}

    public function edit(MainEquipmentType $equipment_type)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $item = $equipment_type;
        return view('dashboard.settings.equipment-types-form', compact('equipment_type', 'item'));
    }

public function update(Request $request, MainEquipmentType $equipment_type)
{
    if (auth()->user()->role !== 'admin') abort(403);
    
    $request->validate([
        'name' => 'required|string|max:255|unique:main_equipment_types,name,' . $equipment_type->id,
        'code' => 'nullable|string|max:50|unique:main_equipment_types,code,' . $equipment_type->id,
        'description' => 'nullable|string'
    ]);
    
    $equipment_type->update($request->only(['name', 'code', 'description']));
    
    return redirect()->route('dashboard.equipment-types.index')->with('success', 'نوع تجهیز با موفقیت ویرایش شد');
}

    public function destroy(MainEquipmentType $equipment_type)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $equipment_type->delete();
        
        return redirect()->route('dashboard.equipment-types.index')->with('success', 'نوع تجهیز با موفقیت حذف شد');
    }
}