<?php

namespace Ignite\Vue;

use Illuminate\Support\ServiceProvider;
use Ignite\Contracts\Vue\Vue as VueContract;

class VueServiceProvider extends ServiceProvider
{
    /**
     * Regsiter application services.
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
