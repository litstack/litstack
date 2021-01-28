<?php

namespace Ignite\Info;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class InfoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        //
    }

    /**
     * Register the info services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
