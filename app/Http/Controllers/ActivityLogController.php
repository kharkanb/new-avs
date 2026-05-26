<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Hekmatinasser\Verta\Verta;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    
    public function index(Request $request)
    {
        $query = Activity::with('causer')->orderBy('created_at', 'desc');
        
        // فیلتر بر اساس نوع رویداد
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }
        
        // فیلتر بر اساس کاربر
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }
        
        // فیلتر بر اساس بازه زمانی
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $activities = $query->paginate(30);
        $users = User::all();
        
        // اضافه کردن متغیر $logs برای سازگاری با ویو
        $logs = $activities;
        
        return view('dashboard.activity-logs', compact('activities', 'logs', 'users'));
    }
    
    public function show($id)
    {
        $activity = Activity::with('causer')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'id' => $activity->id,
            'user_name' => $activity->causer?->name ?? 'سیستم',
            'action' => $activity->description,
            'event' => $activity->event,
            'ip_address' => $activity->properties['ip_address'] ?? $activity->properties['ip'] ?? '-',
            'user_agent' => $activity->properties['user_agent'] ?? '-',
            'old_data' => $activity->properties['old'] ?? $activity->properties['old_data'] ?? null,
            'new_data' => $activity->properties['attributes'] ?? $activity->properties['new_data'] ?? null,
            'created_at' => Verta::instance($activity->created_at)->format('Y/m/d H:i'),
        ]);
    }
    
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'لاگ با موفقیت حذف شد']);
        }
        
        return redirect()->route('dashboard.activity-logs')
            ->with('success', 'لاگ با موفقیت حذف شد');
    }
    
    public function clearAll()
    {
        Activity::truncate();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تمام لاگ‌ها با موفقیت حذف شدند']);
        }
        
        return redirect()->route('dashboard.activity-logs')
            ->with('success', 'تمام لاگ‌ها با موفقیت حذف شدند');
    }
}