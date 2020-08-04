<?php

namespace Fjord\Config;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fjord.app.config.loader', function ($app) {
            return new ConfigLoader();
        });
    }
}
