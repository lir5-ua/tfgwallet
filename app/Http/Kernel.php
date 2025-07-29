<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        \App\Http\Middleware\PerformanceMonitor::class,
        \App\Http\Middleware\SeparateSessionGuard::class,
        // Otros middlewares globales de Laravel pueden ir aquí
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Middlewares estándar de Laravel para web pueden ir aquí
        ],

        'api' => [
            // Middlewares estándar de Laravel para API pueden ir aquí
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'cache.response' => \App\Http\Middleware\CacheResponse::class,
        'can.edit.user' => \App\Http\Middleware\CanEditUser::class,
        'is.admin' => \App\Http\Middleware\IsAdmin::class,
        'verified.custom.ultima_conexion' => \App\Http\Middleware\CheckUltimaConexion::class,
    ];
} 