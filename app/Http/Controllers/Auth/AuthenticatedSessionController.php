<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // ============================================
        // ثبت لاگ ورود به سیستم
        // ============================================
        try {
            $user = Auth::user();
            activity()
                ->causedBy($user)
                ->event('login')
                ->withProperties([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'email' => $user->email
                ])
                ->log('ورود به سیستم');
        } catch (\Exception $e) {
            // خطا در لاگ نادیده گرفته شود
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        try {
            activity()
                ->causedBy($user)
                ->event('logout')
                ->withProperties([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log('خروج از سیستم');
        } catch (\Exception $e) {
            // خطا در لاگ نادیده گرفته شود
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}