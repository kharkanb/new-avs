<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * نمایش لیست کاربران
     */
    public function index()
    {
        $users = User::all();
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * ایجاد کاربر جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'nullable|in:admin,tech,user'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت ایجاد شد',
            'data' => $user
        ], 201);
    }

    /**
     * نمایش یک کاربر
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * بروزرسانی کاربر
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['sometimes', 'confirmed', Password::defaults()],
            'role' => 'nullable|in:admin,tech,user'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت بروزرسانی شد',
            'data' => $user
        ]);
    }

    /**
     * حذف کاربر
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت حذف شد'
        ]);
    }

    /**
     * تغییر نقش کاربر
     */
    public function changeRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,tech,user'
        ]);

        $user->update(['role' => $validated['role']]);

        return response()->json([
            'success' => true,
            'message' => 'نقش کاربر با موفقیت تغییر کرد',
            'data' => $user
        ]);
    }
}