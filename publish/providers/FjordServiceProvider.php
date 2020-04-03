<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FjordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        fjord()->addLangPath(resource_path('lang/'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
