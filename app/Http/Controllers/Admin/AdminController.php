<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Inspection;
use App\Models\MainEquipment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_inspections' => Inspection::count(),
            'total_equipments' => MainEquipment::count(),
            'pending_inspections' => Inspection::where('status', 'draft')->count(),
            'completed_inspections' => Inspection::where('status', 'completed')->count(),
            'users_by_role' => User::selectRaw('role, count(*) as count')->groupBy('role')->get()
        ];
        
        $latest_users = User::latest()->take(5)->get();
        $latest_inspections = Inspection::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'latest_users', 'latest_inspections'));
    }
}