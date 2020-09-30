<?php

namespace Lit\Providers;

use Ignite\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the litstack application.
     *
     * These routes all require the "lit" guard and have the prefix set in the
     * config under "lit.route_prefix".
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group(lit_base_path('routes/web.php'));
    }
}
