<?php

namespace Ignite\Vue;

use Illuminate\Support\ServiceProvider;

class VueServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('lit.vue', function ($app) {
            return new Vue();
        });

        $this->app->register(VueRouteServiceProvider::class);
    }
}
