<?php

namespace Lit\Chart;

use Lit\Support\Facades\LitRoute;
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
        LitRoute::post('/chart-data', ChartController::class);
    }
}
