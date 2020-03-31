<?php

namespace AwStudio\Fjord\Application;

use Illuminate\Support\Facades\Route;
use AwStudio\Fjord\Support\Facades\FjordRoute;
use App\Http\Controllers\Fjord\DashboardController;
use AwStudio\Fjord\Application\Controllers\FileController;
use AwStudio\Fjord\Fjord\Controllers\FjordController;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    public function map()
    {
        $this->mapFjordRoutes();
        $this->mapFileRoutes();
    }

    protected function mapFjordRoutes()
    {
        FjordRoute::group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::post('/set-locale', 'AwStudio\Fjord\Actions\SetLocale')->name('set-locale');
            Route::get('/lang.js', 'AwStudio\Fjord\Fjord\Controllers\FjordTranslationsController@translations')->name('fjord-translations');
            Route::get('/test', 'AwStudio\Fjord\Fjord\Controllers\FjordTranslationsController@translations')->name('fjord-transssslations');
            Route::put('/order', [FjordController::class, 'order'])->name('order');
        });
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
}
