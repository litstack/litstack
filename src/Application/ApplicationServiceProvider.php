<?php

namespace AwStudio\Fjord\Application;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router): void
    {
        // Kernel method handleRoute gets executed here.
        $router->pushMiddlewareToGroup('web', Kernel\HandleRouteMiddleware::class);

        // Kernel method handleView gets executed here.
        View::composer('fjord::app', Kernel\HandleViewComposer::class);
    }
}
