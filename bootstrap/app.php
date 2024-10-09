<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsCompany;
use App\Http\Middleware\IsDeveloper;
use Illuminate\Foundation\Application;
use App\Http\Middleware\IsCompanyOrAdmin;
use App\Http\Middleware\IsCompanyOrDeveloper;
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
        $middleware->statefulApi();
        $middleware->alias([
            'isAdmin' => IsAdmin::class,
            'isCompany' => IsCompany::class,
            'isDeveloper' => IsDeveloper::class,
            'isCompanyOrDeveloper' => IsCompanyOrDeveloper::class,
            'isCompanyOrAdmin' => IsCompanyOrAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
