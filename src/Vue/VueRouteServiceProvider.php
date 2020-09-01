<?php

namespace Ignite\Vue;

use Ignite\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class VueRouteServiceProvider extends RouteServiceProvider
{
    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        Route::post('handle-event', EventController::class);
    }
}
