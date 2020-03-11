<?php

namespace AwStudio\Fjord\RolesPermissions;

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
        if (config('fjord.roles_permissions')) {
            $this->app->register('AwStudio\Fjord\RolesPermissions\RouteServiceProvider');
        }
    }
}
