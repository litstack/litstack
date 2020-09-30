<?php

namespace Ignite\Routing;

use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('lit.router', function ($app) {
            return new Router($app['events'], $app);
        });
    }
}
