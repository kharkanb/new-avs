<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TechMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if (!auth()->check() || !in_array($user->role, ['admin', 'tech'])) {
            abort(403, 'دسترسی غیرمجاز');
        }
        
        return $next($request);
    }
}