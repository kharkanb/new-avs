<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function testPost(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Test successful',
            'data' => $request->all()
        ]);
    }

    public function mobileCallback(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Mobile callback received',
            'data' => $request->all()
        ]);
    }

    public function inspections(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Inspections page',
            'data' => [
                'inspections' => []
            ]
        ]);
    }
}