<?php

namespace AwStudio\Fjord\User;

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
        $this->app->register('AwStudio\Fjord\User\RouteServiceProvider');

        fjord()
            ->package('aw-studio/fjord')
            ->configCompiler('users.table', Config\TableConfig::class);
    }
}
