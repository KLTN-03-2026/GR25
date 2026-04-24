<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global middleware
     */
    protected $middleware = [
        \Illuminate\Http\Middleware\HandleCors::class,
    ];

    /**
     * Route middleware groups
     */
    protected $middlewareGroups = [
        'web' => [],

        'api' => [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Route middleware
     */
    protected $routeMiddleware = [];
}