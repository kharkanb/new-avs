<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Gate;

class UserManagementController extends Controller
{
    /**
     * نمایش لیست کاربران با امکان فیلتر و صفحه‌بندی
     */
    public function index(Request $request)
    {
        // فقط ادمین می‌تونه لیست کاربران رو ببینه
        if (!Gate::allows('viewAny', User::class)) {
            return response()->json([
                'success' => false,
                'message' => 'شما مجوز دسترسی به این بخش را ندارید'
            ], 403);
        }

        $query = User::query();

        // فیلتر بر اساس نقش
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // فیلتر بر اساس جستجو
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // مرتب‌سازی
        $orderBy = $request->get('order_by', 'created_at');
        $orderDir = $request->get('order_dir', 'desc');
        $query->orderBy($orderBy, $orderDir);

        // صفحه‌بندی
        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage);

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
        // فقط ادمین می‌تونه کاربر جدید بسازه
        if (!Gate::allows('create', User::class)) {
            return response()->json([
                'success' => false,
                'message' => 'شما مجوز ایجاد کاربر را ندارید'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role' => 'nullable|in:admin,tech,user',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'is_active' => $validated['is_active'] ?? true
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
        // فقط ادمین یا خود کاربر می‌تونه پروفایل رو ببینه
        if (!Gate::allows('view', $user)) {
            return response()->json([
                'success' => false,
                'message' => 'شما مجوز مشاهده این کاربر را ندارید'
            ], 403);
        }

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
        // فقط ادمین می‌تونه کاربر رو بروزرسانی کنه
        if (!Gate::allows('update', $user)) {
            return response()->json([
                'success' => false,
                'message' => 'شما مجوز بروزرسانی این کاربر را ندارید'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['sometimes', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role' => 'nullable|in:admin,tech,user',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean'
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
        // فقط ادمین می‌تونه کاربر رو حذف کنه
        if (!Gate::allows('delete', $user)) {
            return response()->json([
                'success' => false,
                'message' => 'شما مجوز حذف این کاربر را ندارید'
            ], 403);
        }

        // جلوگیری از حذف خودش
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'شما نمی‌توانید حساب کاربری خود را حذف کنید'
            ], 400);
        }

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
        // فقط ادمین می‌تونه نقش رو تغییر بده
        if (!Gate::allows('update', $user)) {
            return response()->json([
                'success' => false,
                'message' => 'شما مجوز تغییر نقش این کاربر را ندارید'
            ], 403);
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,tech,user'
        ]);

        // جلوگیری از تغییر نقش خودش
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'شما نمی‌توانید نقش خود را تغییر دهید'
            ], 400);
        }

        $user->update(['role' => $validated['role']]);

        return response()->json([
            'success' => true,
            'message' => 'نقش کاربر با موفقیت تغییر کرد',
            'data' => $user
        ]);
    }

    /**
     * فعال/غیرفعال کردن کاربر
     */
    public function toggleActive(Request $request, User $user)
    {
        // فقط ادمین می‌تونه کاربر رو فعال/غیرفعال کنه
        if (!Gate::allows('update', $user)) {
            return response()->json([
                'success' => false,
                'message' => 'شما مجوز تغییر وضعیت این کاربر را ندارید'
            ], 403);
        }

        // جلوگیری از غیرفعال کردن خودش
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'شما نمی‌توانید وضعیت حساب خود را تغییر دهید'
            ], 400);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'فعال' : 'غیرفعال';
        return response()->json([
            'success' => true,
            'message' => "کاربر با موفقیت {$status} شد",
            'data' => $user
        ]);
    }

    /**
     * پروفایل کاربر فعلی
     */
    public function profile()
    {
        $user = auth()->user();
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * بروزرسانی پروفایل کاربر فعلی
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100'
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'پروفایل با موفقیت بروزرسانی شد',
            'data' => $user
        ]);
    }

    /**
     * تغییر رمز عبور کاربر فعلی
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'رمز عبور با موفقیت تغییر کرد'
        ]);
    }
}