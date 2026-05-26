<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $departments = Department::orderBy('name')->paginate(15);
        return view('dashboard.settings.departments', compact('departments'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.departments-form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'city' => 'nullable|string|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string'
        ]);
        
        Department::create($validated);
        return redirect()->route('dashboard.departments.index')->with('success', 'امور با موفقیت اضافه شد');
    }

    public function edit(Department $department)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('dashboard.settings.departments-form', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'city' => 'nullable|string|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string'
        ]);
        
        $department->update($validated);
        return redirect()->route('dashboard.departments.index')->with('success', 'امور با موفقیت ویرایش شد');
    }

    public function destroy(Department $department)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        if ($department->mainEquipments()->count() > 0) {
            return back()->with('error', 'این امور در تجهیزات استفاده شده است و قابل حذف نیست');
        }
        
        $department->delete();
        return redirect()->route('dashboard.departments.index')->with('success', 'امور با موفقیت حذف شد');
    }
}