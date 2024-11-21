<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Exceptions\Handler as CustomHandler;

use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/api_v1.php'));
  
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'securityHeaders' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'verifyCsrfToken' => \App\Http\Middleware\VerifyCsrfTokenMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })
    ->withSingletons([
        ExceptionHandler::class => CustomHandler::class,
    ])
    ->create();
