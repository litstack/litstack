<?php

namespace Ignite\Vue;

use Ignite\Contracts\Vue\Vue as VueContract;
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

        $this->app->bind(VueContract::class, 'lit.vue');

        $this->app->register(VueRouteServiceProvider::class);
    }
}
