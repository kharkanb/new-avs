<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // فقط ادمین دسترسی داشته باشد
    }
    
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('dashboard.users', compact('users'));
    }
    
    public function create()
    {
        return view('dashboard.users-form');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,supervisor,user'
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('dashboard.users.index')
            ->with('success', 'کاربر با موفقیت اضافه شد');
    }
    
    public function edit(User $user)
    {
        return view('dashboard.users-form', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,supervisor,user'
        ]);
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }
        
        $user->update($validated);
        
        return redirect()->route('dashboard.users.index')
            ->with('success', 'کاربر با موفقیت ویرایش شد');
    }
    
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'نمی‌توانید خودتان را حذف کنید');
        }
        
        $user->delete();
        
        return redirect()->route('dashboard.users.index')
            ->with('success', 'کاربر با موفقیت حذف شد');
    }
}