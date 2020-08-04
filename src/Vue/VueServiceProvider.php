<?php

namespace Fjord\Vue;

use Fjord\Contracts\Vue\Vue as VueContract;
use Illuminate\Support\ServiceProvider;

class VueServiceProvider extends ServiceProvider
{
    /**
     * Regsiter application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fjord.vue', function ($app) {
            return new Vue();
        });

        $this->app->bind(VueContract::class, 'fjord.vue');

        $this->app->register(VueRouteServiceProvider::class);
    }
}
