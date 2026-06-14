<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
<<<<<<< HEAD
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

            $middleware->alias([
                'permission' => \App\Http\Middleware\CheckPermission::class,
             'role' => \App\Http\Middleware\CheckRole::class,  // در صورت نیاز
            ]);

=======
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d

        $middleware->validateCsrfTokens(except: [
            'health'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();