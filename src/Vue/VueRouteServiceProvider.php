<?php

namespace Lit\Vue;

use Lit\Support\Facades\LitRoute;
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
        LitRoute::post('handle-event', EventController::class);
    }
}
