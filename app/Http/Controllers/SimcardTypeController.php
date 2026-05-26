<?php

namespace App\Http\Controllers;

use App\Models\SimcardType;
use Illuminate\Http\Request;

class SimcardTypeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = SimcardType::orderBy('name')->paginate(15);
        return view('dashboard.settings.simcard-types', compact('items'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.simcard-types-form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:simcard_types',
            'operator' => 'nullable|string|max:100',
        ]);
        
        SimcardType::create($validated);
        return redirect()->route('dashboard.simcard-types.index')->with('success', 'نوع سیم‌کارت با موفقیت اضافه شد');
    }

    public function edit(SimcardType $simcardType)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.simcard-types-form', compact('item'));
    }

    public function update(Request $request, SimcardType $simcardType)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:simcard_types,name,' . $simcardType->id,
            'operator' => 'nullable|string|max:100',
        ]);
        
        $simcardType->update($validated);
        return redirect()->route('dashboard.simcard-types.index')->with('success', 'نوع سیم‌کارت با موفقیت ویرایش شد');
    }

    public function destroy(SimcardType $simcardType)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $simcardType->delete();
        return redirect()->route('dashboard.simcard-types.index')->with('success', 'نوع سیم‌کارت با موفقیت حذف شد');
    }
}