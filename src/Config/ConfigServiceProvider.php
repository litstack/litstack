<?php

namespace Ignite\Config;

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
        $this->app->singleton('lit.app.config.loader', function ($app) {
            return new ConfigLoader();
        });
    }

    /**
     * Register config factories.
     *
     * @return void
     */
    protected function registerConfigFactories()
    {
        foreach ($this->configFactories as $dependency => $factory) {
            $this->app['lit.app']->registerConfigFactory($dependency, $factory);
        }
    }
}
