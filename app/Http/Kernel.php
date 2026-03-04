<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Puedes dejarlo vacío si quieres
    ];

    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Session\Middleware\StartSession::class,
        ],
    ];

    protected $routeMiddleware = [
        'profesorAuth' => \App\Http\Middleware\ProfesorAuth::class,
    ];
}