<?php

namespace Fjord\Permissions;

use Fjord\Permissions\Composer\PermissionsComposer;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        fjord()->composer(PermissionsComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
