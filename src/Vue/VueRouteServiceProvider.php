<?php

namespace Fjord\Vue;

use Fjord\Support\Facades\FjordRoute;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class VueRouteServiceProvider extends RouteServiceProvider
{
    public function map()
    {
        FjordRoute::post('handle-event', EventController::class);
    }
}
