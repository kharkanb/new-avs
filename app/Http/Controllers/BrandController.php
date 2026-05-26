<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MainEquipmentType;
use App\Models\CellEquipmentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{


public function index(Request $request)
{
    if (auth()->user()->role !== 'admin') abort(403);
    
    $category = $request->get('category', 'all');
    $equipmentTypeId = $request->get('equipment_type_id', '');
    
    $query = Brand::with('mainEquipmentType', 'cellEquipmentType');
    
    if ($category == 'main') {
        $query->whereNotNull('equipment_type_id');
    } elseif ($category == 'cell') {
        $query->whereNotNull('cell_equipment_type_id');
    }
    
    if (!empty($equipmentTypeId)) {
        $parts = explode('_', $equipmentTypeId);
        if (count($parts) == 2) {
            $typeCategory = $parts[0];
            $typeId = $parts[1];
            
            if ($typeCategory == 'main') {
                $query->where('equipment_type_id', $typeId);
            } elseif ($typeCategory == 'cell') {
                $query->where('cell_equipment_type_id', $typeId);
            }
        }
    }
    
    // گرفتن همه داده‌ها
    $allItems = $query->get();
    
    // مرتب‌سازی: اول تجهیزات اصلی، بعد سلولی، سپس بر اساس نام نوع تجهیز، سپس نام برند
    $allItems = $allItems->sortBy(function($item) {
        // اولویت اصلی: 0 برای اصلی، 1 برای سلولی
        $priority = $item->mainEquipmentType ? 0 : 1;
        
        // دوم: نام نوع تجهیز
        $typeName = '';
        if ($item->mainEquipmentType) {
            $typeName = $item->mainEquipmentType->name;
        } elseif ($item->cellEquipmentType) {
            $typeName = $item->cellEquipmentType->name;
        } else {
            $typeName = 'zzzz';
        }
        
        // سوم: نام برند
        $brandName = $item->name;
        
        return [$priority, $typeName, $brandName];
    })->values();
    
    // تبدیل به paginator
    $currentPage = request()->get('page', 1);
    $perPage = 20;
    $currentItems = $allItems->slice(($currentPage - 1) * $perPage, $perPage);
    $items = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentItems,
        $allItems->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
    
    // حذف آیدی‌های 5، 6، 7 از لیست فیلتر
    $mainEquipmentTypes = MainEquipmentType::whereNotIn('id', [5, 6, 7])
        ->orderBy('name')
        ->get();
    
    $cellEquipmentTypes = CellEquipmentType::orderBy('name')->get();
    
    return view('dashboard.settings.brands', compact('items', 'mainEquipmentTypes', 'cellEquipmentTypes'));
}

    
public function create()
{
    if (auth()->user()->role !== 'admin') abort(403);
    
    // همه نوع تجهیزات اصلی به جز آیدی‌های 5، 6، 7
    $mainEquipmentTypes = MainEquipmentType::whereNotIn('id', [5, 6, 7])
        ->orderBy('name')
        ->get();
    
    $cellEquipmentTypes = CellEquipmentType::orderBy('name')->get();
    
    return view('dashboard.settings.brands-form', compact('mainEquipmentTypes', 'cellEquipmentTypes'));
}



    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $rules = [
            'type_category' => 'required|in:main,cell',
            'name' => 'required|string|max:255'
        ];
        
        if ($request->type_category == 'main') {
            $rules['equipment_type_id'] = 'required|exists:main_equipment_types,id';
            $rules['name'] .= '|unique:brands,name,NULL,id,equipment_type_id,' . $request->equipment_type_id;
        } else {
            $rules['cell_equipment_type_id'] = 'required|exists:cell_equipment_types,id';
            $rules['name'] .= '|unique:brands,name,NULL,id,cell_equipment_type_id,' . $request->cell_equipment_type_id;
        }
        
        $request->validate($rules);
        
        $data = ['name' => $request->name];
        
        if ($request->type_category == 'main') {
            $data['equipment_type_id'] = $request->equipment_type_id;
            $data['cell_equipment_type_id'] = null;
        } else {
            $data['cell_equipment_type_id'] = $request->cell_equipment_type_id;
            $data['equipment_type_id'] = null;
        }
        
        Brand::create($data);
        
        return redirect()->route('dashboard.brands.index')->with('success', 'برند با موفقیت اضافه شد');
    }

public function edit(Brand $brand)
{
    if (auth()->user()->role !== 'admin') abort(403);
    
    // برای ویرایش: همه نوع تجهیزات اصلی را نشان بده (چون ممکن است کاربر بخواهد تغییر دهد)
    // اما اگر می‌خواهی آن سه تا را هم در ویرایش نشان ندهی:
    $mainEquipmentTypes = MainEquipmentType::whereNotIn('id', [5, 6, 7])
        ->orderBy('name')
        ->get();
    
    $cellEquipmentTypes = CellEquipmentType::orderBy('name')->get();
    
    $item = $brand;
    return view('dashboard.settings.brands-form', compact('brand', 'item', 'mainEquipmentTypes', 'cellEquipmentTypes'));
}



    public function update(Request $request, Brand $brand)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $rules = [
            'type_category' => 'required|in:main,cell',
            'name' => 'required|string|max:255'
        ];
        
        if ($request->type_category == 'main') {
            $rules['equipment_type_id'] = 'required|exists:main_equipment_types,id';
            $rules['name'] .= '|unique:brands,name,' . $brand->id . ',id,equipment_type_id,' . $request->equipment_type_id;
        } else {
            $rules['cell_equipment_type_id'] = 'required|exists:cell_equipment_types,id';
            $rules['name'] .= '|unique:brands,name,' . $brand->id . ',id,cell_equipment_type_id,' . $request->cell_equipment_type_id;
        }
        
        $request->validate($rules);
        
        $data = ['name' => $request->name];
        
        if ($request->type_category == 'main') {
            $data['equipment_type_id'] = $request->equipment_type_id;
            $data['cell_equipment_type_id'] = null;
        } else {
            $data['cell_equipment_type_id'] = $request->cell_equipment_type_id;
            $data['equipment_type_id'] = null;
        }
        
        $brand->update($data);
        
        return redirect()->route('dashboard.brands.index')->with('success', 'برند با موفقیت ویرایش شد');
    }

    public function destroy(Brand $brand)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $brand->delete();
        
        return redirect()->route('dashboard.brands.index')->with('success', 'برند با موفقیت حذف شد');
    }
}