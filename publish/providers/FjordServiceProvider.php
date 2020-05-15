<?php

namespace App\Providers;

use Fjord\Support\Facades\FjordLang;
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
        FjordLang::addPath(base_path('fjord/resources/lang/'));
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
