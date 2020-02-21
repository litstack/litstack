<?php
namespace AwStudio\Fjord\Routing;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Illuminate\Support\Facades\Route;
use AwStudio\Fjord\Auth\AuthController;
use AwStudio\Fjord\Form\Crud;
use AwStudio\Fjord\Fjord\Controllers\FjordController;
use AwStudio\Fjord\Fjord\Controllers\FileController;
use App\Http\Controllers\Fjord\DashboardController;
use Exception;

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
        $this->mapFileRoutes();
        $this->mapDashboardRoutes();
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
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::post('/set-locale', 'AwStudio\Fjord\Actions\SetLocale')->name('set-locale');
            Route::get('/lang.js', 'AwStudio\Fjord\Fjord\Controllers\FjordTranslationsController')->name('fjord-translations');
            Route::put('/order', [FjordController::class, 'order'])->name('order');
        });

        Route::prefix(config('fjord.route_prefix'))
            ->namespace($this->namespace)
            ->middleware('web')
            ->group(fjord_path('routes/fjord.php'));
    }

    protected function mapFileRoutes()
    {
        FjordRoute::public()
            ->get('js/app.js', FileController::class . '@fjordJs')
            ->name('js');
        FjordRoute::public()
            ->get('css/app.css', FileController::class . '@fjordCss')
            ->name('css');
        FjordRoute::public()
            ->get('images/fjord-logo.png', FileController::class . '@fjordLogo')
            ->name('logo');
        FjordRoute::public()
            ->get('favicon/favicon-32x32.png', FileController::class . '@fjordFaviconBig')
            ->name('favicon-big');
        FjordRoute::public()
            ->get('favicon/favicon-16x16.png', FileController::class . '@fjordFaviconSmall')
            ->name('favicon-small');
    }

    protected function mapDashboardRoutes()
    {
        if(\App::runningInConsole()){
            return;
        }
        if(
            !\File::exists(app_path('Http/Controllers/Fjord/DashboardController.php'))
        ){
            throw new Exception("The App/Http/Controllers/Fjord/DashboardController.php does not exist. Run php artisan fjord:install to create it.");
        }
        FjordRoute::extensionRoutes(\App\Http\Controllers\Fjord\DashboardController::class);
    }
}
