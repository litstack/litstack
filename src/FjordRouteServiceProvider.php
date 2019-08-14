<?php
namespace AwStudio\Fjord;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class FjordRouteServiceProvider extends RouteServiceProvider
{
    protected $namespace = 'AwStudio\Fjord\Http\Controllers';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapFjordRoutes();
    }


    protected function mapFjordRoutes()
    {
        Route::prefix(config('fjord.route_prefix'))
            ->namespace($this->namespace)
            ->middleware('web')
            ->group(fjord_path('routes/fjord.php'));
    }
}
