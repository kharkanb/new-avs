<?php

namespace App\Http\Controllers;

use App\Models\ChecklistTemplate;
use App\Models\ChecklistItem;
use App\Models\MainEquipmentType;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = ChecklistTemplate::withCount('items')->with('mainEquipmentType')->orderBy('id', 'desc')->paginate(20);
        return view('dashboard.settings.checklist-items', compact('items'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $equipmentTypes = MainEquipmentType::orderBy('name')->get();
        return view('dashboard.settings.checklist-items-form', compact('equipmentTypes'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
    // بررسی اینکه آیا قبلاً چک‌لیستی برای این تجهیز وجود دارد
    $exists = ChecklistTemplate::where('main_equipment_type_id', $request->main_equipment_type_id)->exists();
    
    if ($exists) {
        return back()->with('error', 'برای این نوع تجهیز قبلاً چک‌لیست تعریف شده است. لطفاً آن را ویرایش کنید.');
    }

        $request->validate([
            'main_equipment_type_id' => 'required|exists:main_equipment_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*' => 'nullable|string'
        ]);
        
        $template = ChecklistTemplate::create([
            'main_equipment_type_id' => $request->main_equipment_type_id,
            'title' => $request->title,
            'description' => $request->description
        ]);
        
        $sortOrder = 0;
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemText) {
                $itemText = trim($itemText);
                if (!empty($itemText)) {
                    $sortOrder++;
                    ChecklistItem::create([
                        'checklist_template_id' => $template->id,
                        'item_text' => $itemText,
                        'sort_order' => $sortOrder
                    ]);
                }
            }
        }
        
        return redirect()->route('dashboard.checklist-items.index')->with('success', 'چک‌لیست با موفقیت اضافه شد');
    }

    public function edit(ChecklistTemplate $checklist_item)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $equipmentTypes = MainEquipmentType::orderBy('name')->get();
        $item = $checklist_item;
        $item->load('items');
        return view('dashboard.settings.checklist-items-form', compact('item', 'equipmentTypes'));
    }

public function update(Request $request, ChecklistTemplate $checklist_item)
{
    if (auth()->user()->role !== 'admin') abort(403);

    // لاگ برای دیدن مقادیر دریافتی
    \Log::info('=== UPDATE CHECKLIST ===');
    \Log::info('existing_items:', $request->existing_items ?? []);
    \Log::info('items:', $request->items ?? []);

    
    $request->validate([
        'main_equipment_type_id' => 'required|exists:main_equipment_types,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'items' => 'nullable|array',
        'items.*' => 'nullable|string',
        'existing_items' => 'nullable|array',
        'existing_items.*' => 'nullable|exists:checklist_items,id'
    ]);
    
    // 1. به‌روزرسانی خود چک‌لیست
    $checklist_item->update([
        'main_equipment_type_id' => $request->main_equipment_type_id,
        'title' => $request->title,
        'description' => $request->description
    ]);
    
    // 2. حذف آیتم‌هایی که در فرم نیستند
    $keepItemIds = $request->existing_items ?? [];
    
    // لاگ‌گیری بعد از تعریف متغیر
    \Log::info('Keep IDs: ', $keepItemIds);
    \Log::info('All item IDs: ', $checklist_item->items()->pluck('id')->toArray());
    
    // حذف تمام آیتم‌هایی که ID آنها در آرایه keep نیست
    if (!empty($keepItemIds)) {
        $checklist_item->items()->whereNotIn('id', $keepItemIds)->delete();
    } else {
        // اگر هیچ آیتمی نگهداشته نشده، همه را حذف کن
        $checklist_item->items()->delete();
    }
    
    // 3. اضافه کردن آیتم‌های جدید
    if ($request->has('items') && is_array($request->items)) {
        $currentSortOrder = $checklist_item->items()->max('sort_order') ?? 0;
        foreach ($request->items as $itemText) {
            $itemText = trim($itemText);
            if (!empty($itemText)) {
                $currentSortOrder++;
                ChecklistItem::create([
                    'checklist_template_id' => $checklist_item->id,
                    'item_text' => $itemText,
                    'sort_order' => $currentSortOrder
                ]);
            }
        }
    }
    
    return redirect()->route('dashboard.checklist-items.index')->with('success', 'چک‌لیست با موفقیت ویرایش شد');
}

    public function destroy(ChecklistTemplate $checklist_item)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $checklist_item->items()->delete();
        $checklist_item->delete();
        
        return redirect()->route('dashboard.checklist-items.index')->with('success', 'چک‌لیست با موفقیت حذف شد');
    }
}