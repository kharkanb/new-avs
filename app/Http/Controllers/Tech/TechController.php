<?php

namespace App\Http\Controllers\Tech;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\MainEquipment;
use Illuminate\Http\Request;

class TechController extends Controller
{
    public function dashboard()
    {
        $user_id = auth()->id();
        
        $stats = [
            'my_inspections' => Inspection::where('user_id', $user_id)->count(),
            'my_equipments' => MainEquipment::where('user_id', $user_id)->count(),
            'this_month' => Inspection::where('user_id', $user_id)
                                ->whereMonth('inspection_date', now()->month)
                                ->count()
        ];
        
        $my_inspections = Inspection::where('user_id', $user_id)
                            ->with('equipments')
                            ->latest()
                            ->paginate(10);
        
        return view('tech.dashboard', compact('stats', 'my_inspections'));
    }
}