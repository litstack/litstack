<?php

namespace Lit\Vue;

use Illuminate\Support\ServiceProvider;
use Lit\Contracts\Vue\Vue as VueContract;

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
