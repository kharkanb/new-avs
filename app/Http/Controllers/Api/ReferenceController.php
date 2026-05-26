<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Brand;
use App\Models\Department;
use App\Models\Feeder;
use App\Models\EquipmentType;
use App\Models\CellEquipmentType;
use App\Models\ActivityPrice;
use App\Models\ConsumableItem;
use App\Models\ChecklistTemplate;
use App\Models\Contractor;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{

    /**
     * @OA\Get(
     *     path="/reference/posts",
     *     summary="لیست پست‌ها",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="لیست پست‌ها",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function posts()
    {
        try {
            $posts = Post::all();
            return response()->json([
                'success' => true,
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/reference/brands",
     *     summary="لیست برندها",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="لیست برندها")
     * )
     */
    public function brands()
    {
        try {
            $brands = Brand::all();
            return response()->json([
                'success' => true,
                'data' => $brands
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/reference/departments",
     *     summary="لیست دپارتمان‌ها",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="لیست دپارتمان‌ها")
     * )
     */
    public function departments()
    {
        try {
            $departments = Department::all();
            return response()->json([
                'success' => true,
                'data' => $departments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/reference/feeders",
     *     summary="لیست فیدرها",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         description="فیلتر بر اساس پست",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="لیست فیدرها")
     * )
     */
    public function feeders(Request $request)
    {
        try {
            $query = Feeder::query();
            
            if ($request->has('post_id')) {
                $query->where('post_id', $request->post_id);
            }
            
            $feeders = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $feeders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/reference/all",
     *     summary="دریافت همه دیتاهای مرجع در یک درخواست",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="همه دیتاهای مرجع")
     * )
     */
   public function all()
{
    try {
        // فقط ساده‌ترین دیتاها را برگردان
        $equipmentTypes = EquipmentType::where('category', 'main')->get();
        $brands = Brand::all();
        $departments = Department::all();
        $posts = Post::all();
        $feeders = Feeder::all();
                $activityPrices = ActivityPrice::orderBy('code')->get();  // مرتب شده بدون محدودیت
        $consumableItems = ConsumableItem::all();
        $contractors = Contractor::select('id', 'name', 'coefficient', 'contract_number')->get(); 

        
        return response()->json([
            'success' => true,
            'equipment_types' => $equipmentTypes,
            'brands' => $brands,
            'departments' => $departments,
            'posts' => $posts,
            'feeders' => $feeders,
            'activity_prices' => $activityPrices,
            'consumable_items' => $consumableItems,
            'contractors' => $contractors,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
   

    /**
     * @OA\Get(
     *     path="/reference/main-equipment-types",
     *     summary="لیست انواع تجهیزات اصلی",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="لیست انواع تجهیزات اصلی")
     * )
     */
    public function mainEquipmentTypes()
    {
        try {
            $types = EquipmentType::where('category', 'main')->get();
            return response()->json([
                'success' => true,
                'data' => $types
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/reference/cell-equipment-types",
     *     summary="لیست انواع تجهیزات سلولی",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="لیست انواع تجهیزات سلولی")
     * )
     */
    public function cellEquipmentTypes()
    {
        try {
            $types = CellEquipmentType::all();
            return response()->json([
                'success' => true,
                'data' => $types
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/reference/activity-prices",
     *     summary="لیست فهرست بها",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="لیست فهرست بها")
     * )
     */
    public function activityPrices()
    {
        try {
            $prices = ActivityPrice::all();
            return response()->json([
                'success' => true,
                'data' => $prices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/reference/consumable-items",
     *     summary="لیست اقلام مصرفی",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="لیست اقلام مصرفی")
     * )
     */
    public function consumableItems()
    {
        try {
            $items = ConsumableItem::all();
            return response()->json([
                'success' => true,
                'data' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/reference/checklist-templates",
     *     summary="لیست قالب‌های چک‌لیست",
     *     tags={"Reference"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="equipment_type_id",
     *         in="query",
     *         description="فیلتر بر اساس نوع تجهیز",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="لیست قالب‌های چک‌لیست")
     * )
     */
    public function checklistTemplates(Request $request)
    {
        try {
            $query = ChecklistTemplate::with('items');
            
            if ($request->has('equipment_type_id')) {
                $query->where('main_equipment_type_id', $request->equipment_type_id);
            }
            
            $templates = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

public function getAllReferences()
{
    try {
        return response()->json([
            'success' => true,
            'equipment_types' => \App\Models\EquipmentType::where('category', 'main')->get(),
            'cell_equipment_types' => \App\Models\CellEquipmentType::all(),
            'brands' => \App\Models\Brand::all(),
            'departments' => \App\Models\Department::all(),
            'posts' => \App\Models\Post::all(),
            'feeders' => \App\Models\Feeder::all(),
            'activity_prices' => \App\Models\ActivityPrice::all(),
            'consumable_items' => \App\Models\ConsumableItem::all(),
            'checklist_templates' => \App\Models\ChecklistTemplate::with('items')->get(),
            'contractors' => \App\Models\Contractor::all(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


}