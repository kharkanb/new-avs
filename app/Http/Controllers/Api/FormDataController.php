<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityPrice;
use App\Models\Brand;
use App\Models\ChecklistTemplate;
use App\Models\ConsumableItem;
use App\Models\Contractor;
use App\Models\Department;
use App\Models\EquipmentType;
use App\Models\Feeder;
use App\Models\Post;
use Illuminate\Http\Request;

class FormDataController extends Controller
{
    public function getFormData()
    {
        $equipmentTypes = EquipmentType::where('category', 'main')->get();
        
        // ساختار چک‌لیست‌ها
        $checklists = [];
        foreach ($equipmentTypes as $type) {
            $template = ChecklistTemplate::with('items')
                ->where('main_equipment_type_id', $type->id)
                ->first();
            if ($template) {
                $checklists[$type->name] = $template->items->pluck('item_text')->toArray();
            }
        }
        
        // ساختار پست‌ها و فیدرها
        $posts = Post::with('feeders')->get();
        $postsAndFeeders = $posts->map(function($post) {
            return [
                'post' => $post->name,
                'feeders' => $post->feeders->pluck('name')->toArray()
            ];
        });
        
        return response()->json([
            'equipment_types' => $equipmentTypes->pluck('name')->toArray(),
            'equipment_types_with_brand' => $equipmentTypes->where('has_brand', 1)->pluck('name')->toArray(),
            'equipment_types_without_brand' => $equipmentTypes->where('has_brand', 0)->pluck('name')->toArray(),
            'equipment_types_without_height' => $equipmentTypes->where('has_height', 0)->pluck('name')->toArray(),
            'switch_brands' => Brand::whereIn('equipment_type', ['ریکلوزر', 'سکسیونر', 'سکشنالایزر'])->pluck('name')->unique()->values(),
            'modem_brands' => Brand::where('equipment_type', 'مودم')->pluck('name')->unique()->values(),
            'rtu_brands' => Brand::where('equipment_type', 'رله/RTU')->pluck('name')->unique()->values(),
            'city_departments' => Department::pluck('name')->toArray(),
            'price_list' => ActivityPrice::all(),
            'consumables_list' => ConsumableItem::all(),
            'equipment_checklists' => $checklists,
            'posts_and_feeders' => $postsAndFeeders,
            'posts_list' => $posts->pluck('name')->toArray(),
            'contractors' => Contractor::all(),
        ]);
    }
}