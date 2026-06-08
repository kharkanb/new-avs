<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Brand;
use App\Models\Department;
use App\Models\Feeder;
use App\Models\ActivityPrice;
use App\Models\CellEquipmentType;
use App\Models\ChecklistTemplate;
use App\Models\ConsumableItem;
use App\Models\MainEquipmentType;
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
        return $this->success(Post::orderBy('name')->get());
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
        return $this->success(Brand::orderBy('category')->orderBy('name')->get());
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
        return $this->success(Department::orderBy('name')->get());
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
        $query = Feeder::query()->with('post')->orderBy('name');

        if ($request->filled('post_id')) {
            $query->where('post_id', $request->post_id);
        }

        return $this->success($query->get());
    }

    public function mainEquipmentTypes()
    {
        return $this->success(MainEquipmentType::orderBy('name')->get());
    }

    public function cellEquipmentTypes()
    {
        return $this->success(CellEquipmentType::orderBy('name')->get());
    }

    public function activityPrices()
    {
        return $this->success(ActivityPrice::orderBy('code')->get());
    }

    public function consumableItems()
    {
        return $this->success(ConsumableItem::orderBy('name')->get());
    }

    public function checklistTemplates()
    {
        return $this->success(ChecklistTemplate::with(['mainEquipmentType', 'items'])->get());
    }

    public function all(Request $request)
    {
        return $this->success([
            'main_equipment_types' => MainEquipmentType::orderBy('name')->get(),
            'cell_equipment_types' => CellEquipmentType::orderBy('name')->get(),
            'brands' => Brand::orderBy('category')->orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'posts' => Post::with('feeders')->orderBy('name')->get(),
            'feeders' => Feeder::when($request->filled('post_id'), fn ($query) => $query->where('post_id', $request->post_id))
                ->orderBy('name')
                ->get(),
            'activity_prices' => ActivityPrice::orderBy('code')->get(),
            'consumable_items' => ConsumableItem::orderBy('name')->get(),
            'checklist_templates' => ChecklistTemplate::with(['mainEquipmentType', 'items'])->get(),
        ]);
    }

    private function success($data)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}