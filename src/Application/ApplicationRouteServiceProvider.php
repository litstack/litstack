<?php

namespace Fjord\Application;

use Illuminate\Support\Facades\File;
use Fjord\Support\Facades\FjordRoute;
use Illuminate\Support\Facades\Route;
use Fjord\Application\Controllers\FileController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class ApplicationRouteServiceProvider extends LaravelRouteServiceProvider
{
    public function map()
    {
        $this->mapFjordRoutes();
        $this->mapFileRoutes();
        $this->mapAppRoutes();
    }

    protected function mapAppRoutes()
    {
        if (File::exists(base_path('fjord/routes/fjord.php'))) {
            FjordRoute::group(base_path('fjord/routes/fjord.php'));
        }
    }

    protected function mapFjordRoutes()
    {
        FjordRoute::group(function () {
            Route::post('/set-locale', 'Fjord\Actions\SetLocale')->name('set-locale');
            Route::get('/lang.js', 'Fjord\Application\Controllers\TranslationsController@i18n')->name('fjord-translations');
        });
    }

    /**
     * Undocumented function
     *
     * @return void
     */
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
