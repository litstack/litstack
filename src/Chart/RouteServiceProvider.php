<?php

namespace Fjord\Chart;

use Fjord\Support\Facades\FjordRoute;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->macros();
    }

    /**
     * Register macros.
     *
     * @return void
     */
    public function macros()
    {
        //
    }

    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        $this->mapChartRoutes();
    }

    /**
     * Map chart routes.
     *
     * @return void
     */
    public function mapChartRoutes()
    {
        FjordRoute::post('/chart-data', ChartController::class . '@get');
    }
}
