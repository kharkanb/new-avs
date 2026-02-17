<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = ['*'];
    
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }
}