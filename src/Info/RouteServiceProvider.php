<?php

namespace Ignite\Info;

use Ignite\Info\Controllers\InfoController;
use Ignite\Support\Facades\Route as LitstackRoute;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        $this->mapInfoRoutes();
    }

    /**
     * Map info routes.
     *
     * @return void
     */
    protected function mapInfoRoutes()
    {
        LitstackRoute::get('/litstack-info', InfoController::class.'@showInfo')
            ->name('info');
    }
}
