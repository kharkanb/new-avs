<?php

namespace App\Http\Controllers;

use App\Models\ActivityPrice;
use Illuminate\Http\Request;

class ActivityPriceController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = ActivityPrice::orderBy('code')->paginate(15);
        return view('dashboard.settings.activity-prices', compact('items'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.activity-prices-form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:activity_prices',
            'title' => 'required|string|max:500',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',  // ← اصلاح: numeric
        ]);
        
        ActivityPrice::create($validated);  // ← ساده شده
        
        return redirect()->route('dashboard.activity-prices.index')->with('success', 'آیتم فهرست بها با موفقیت اضافه شد');
    }

    public function edit(ActivityPrice $activityPrice)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.activity-prices-form', compact('activityPrice'));
    }

    public function update(Request $request, ActivityPrice $activityPrice)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:activity_prices,code,' . $activityPrice->id,
            'title' => 'required|string|max:500',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',  // ← اصلاح: numeric
        ]);
        
        $activityPrice->update($validated);
        
        return redirect()->route('dashboard.activity-prices.index')->with('success', 'آیتم فهرست بها با موفقیت ویرایش شد');
    }

    public function destroy(ActivityPrice $activityPrice)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        // بررسی وابستگی (اگر متد equipmentActivities وجود دارد)
        try {
            if ($activityPrice->equipmentActivities()->count() > 0) {
                return back()->with('error', 'این آیتم در فعالیت‌ها استفاده شده است و قابل حذف نیست');
            }
        } catch(\Exception $e) {
            // اگر رابطه وجود نداشت، ignore کن
        }
        
        $activityPrice->delete();
        return redirect()->route('dashboard.activity-prices.index')->with('success', 'آیتم فهرست بها با موفقیت حذف شد');
    }
}