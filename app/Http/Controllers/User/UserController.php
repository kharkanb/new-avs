<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        $inspections = Inspection::where('user_id', $user->id)
                        ->latest()
                        ->paginate(10);
        
        return view('user.dashboard', compact('user', 'inspections'));
    }
    
    public function showInspection($id)
    {
        $inspection = Inspection::where('user_id', auth()->id())
                        ->with('equipments')
                        ->findOrFail($id);
        
        return view('user.inspection', compact('inspection'));
    }
}