<?php

namespace Fjord\Chart;

use Fjord\Support\Facades\FjordRoute;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        FjordRoute::post('/chart-data', ChartController::class);
    }
}
