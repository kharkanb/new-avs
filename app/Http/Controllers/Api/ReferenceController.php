<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Brand;
use App\Models\Department;
use App\Models\Feeder;
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
}