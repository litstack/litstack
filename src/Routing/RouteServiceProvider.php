<?php
namespace AwStudio\Fjord\Routing;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Illuminate\Support\Facades\Route;
use AwStudio\Fjord\Auth\AuthController;
use AwStudio\Fjord\Form\Crud;
use AwStudio\Fjord\Fjord\Controllers\FjordController;
use AwStudio\Fjord\Fjord\Controllers\RolePermissionController;
use AwStudio\Fjord\Fjord\Controllers\UserRoleController;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    protected $namespace = 'AwStudio\Fjord';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapAuthRoutes();
        $this->mapFjordRoutes();
    }

    protected function mapAuthRoutes()
    {
        FjordRoute::public()
            ->get('login', AuthController::class . '@login')
            ->name('login');

        FjordRoute::public()
            ->post('login', AuthController::class . '@postLogin')
            ->name('login.post');

        FjordRoute::post('logout', AuthController::class . '@logout')
            ->name('logout');
    }

    protected function mapFjordRoutes()
    {
        FjordRoute::group(function() {
            Route::view('/', 'fjord::app')->name('dashboard');
        });

        FjordRoute::put('/order', FjordController::class . '@order')
            ->name('order');

        Route::prefix(config('fjord.route_prefix'))
            ->namespace($this->namespace)
            ->middleware('web')
            ->group(fjord_path('routes/fjord.php'));
    }
}
