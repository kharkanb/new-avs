<?php

namespace App\Http\Controllers;

use App\Models\CellEquipmentType;
use Illuminate\Http\Request;

class CellEquipmentTypeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = CellEquipmentType::orderBy('name')->paginate(20);
        return view('dashboard.settings.cell-equipment-types', compact('items'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.cell-equipment-types-form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:cell_equipment_types',
            'code' => 'nullable|string|max:50|unique:cell_equipment_types',
            'description' => 'nullable|string'
        ]);
        
        CellEquipmentType::create($request->only('name', 'code', 'description'));
        
        return redirect()->route('dashboard.cell-equipment-types.index')->with('success', 'نوع تجهیز سلولی با موفقیت اضافه شد');
    }

    public function edit(CellEquipmentType $cell_equipment_type)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $item = $cell_equipment_type;
        return view('dashboard.settings.cell-equipment-types-form', compact('cell_equipment_type', 'item'));
    }

    public function update(Request $request, CellEquipmentType $cell_equipment_type)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:cell_equipment_types,name,' . $cell_equipment_type->id,
            'code' => 'nullable|string|max:50|unique:cell_equipment_types,code,' . $cell_equipment_type->id,
            'description' => 'nullable|string'
        ]);
        
        $cell_equipment_type->update($request->only('name', 'code', 'description'));
        
        return redirect()->route('dashboard.cell-equipment-types.index')->with('success', 'نوع تجهیز سلولی با موفقیت ویرایش شد');
    }

    public function destroy(CellEquipmentType $cell_equipment_type)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        // بررسی اینکه آیا برندی برای این نوع تجهیز وجود دارد
        if ($cell_equipment_type->brands()->count() > 0) {
            return back()->with('error', 'این نوع تجهیز دارای برند است و قابل حذف نیست');
        }
        
        $cell_equipment_type->delete();
        
        return redirect()->route('dashboard.cell-equipment-types.index')->with('success', 'نوع تجهیز سلولی با موفقیت حذف شد');
    }
}