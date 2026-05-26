<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    /**
     * نمایش لیست پیمانکاران
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = Contractor::orderBy('name')->paginate(15);
        return view('dashboard.contractors', compact('items'));
    }

    /**
     * نمایش فرم ایجاد پیمانکار جدید
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.contractors-form');
    }

    /**
     * ذخیره پیمانکار جدید
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:contractors',
            'coefficient' => 'required|numeric|min:0',
            'contract_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);
        
        $contractor = Contractor::create($request->all());
        
        return redirect()->route('dashboard.contractors')
            ->with('success', 'پیمانکار با موفقیت اضافه شد');
    }

    /**
     * نمایش فرم ویرایش پیمانکار
     */
    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $contractor = Contractor::findOrFail($id);
        return view('dashboard.contractors-form', compact('contractor'));
    }

    /**
     * به‌روزرسانی پیمانکار
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $contractor = Contractor::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:contractors,name,' . $contractor->id,
            'coefficient' => 'required|numeric|min:0',
            'contract_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);
        
        $contractor->update($request->all());
        
        return redirect()->route('dashboard.contractors')
            ->with('success', 'پیمانکار با موفقیت ویرایش شد');
    }

    /**
     * حذف پیمانکار
     */
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $contractor = Contractor::findOrFail($id);
        
        // بررسی اینکه آیا پیمانکار در بازدیدی استفاده شده است
        if ($contractor->inspections()->count() > 0) {
            return back()->with('error', 'این پیمانکار در بازدیدها استفاده شده و قابل حذف نیست');
        }
        
        $contractor->delete();
        
        return redirect()->route('dashboard.contractors')
            ->with('success', 'پیمانکار با موفقیت حذف شد');
    }
}