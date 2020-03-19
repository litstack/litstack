<?php

namespace AwStudio\Fjord\Auth;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register('AwStudio\Fjord\Auth\RouteServiceProvider');
    }
}
