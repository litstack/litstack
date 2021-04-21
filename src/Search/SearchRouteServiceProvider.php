<?php

namespace Ignite\Search;

use Ignite\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class SearchRouteServiceProvider extends RouteServiceProvider
{
    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        Route::post('search', SearchController::class);
    }
}
