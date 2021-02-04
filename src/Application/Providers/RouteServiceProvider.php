<?php

namespace Ignite\Application\Providers;

use Ignite\Application\Controllers\FileController;
use Ignite\Support\Facades\Route as LitstackRoute;
use Ignite\Translation\Controllers\LoadTranslationsController;
use Ignite\Translation\Controllers\SetLocaleController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        $this->mapLitstackRoutes();
        $this->mapFileRoutes();
        $this->mapAppRoutes();
    }

    /**
     * Map application routes.
     *
     * @return void
     */
    protected function mapAppRoutes()
    {
        if (File::exists(base_path('lit/routes/lit.php'))) {
            LitstackRoute::group(base_path('lit/routes/lit.php'));
        }
    }

    /**
     * Map lit routes.
     *
     * @return void
     */
    protected function mapLitstackRoutes()
    {
        LitstackRoute::group(function () {
            Route::post('/set-locale', SetLocaleController::class)->name('set-locale');
            Route::get('/lang.js', LoadTranslationsController::class.'@i18n')->name('lit-translations');
        });
    }

    /**
     * Map file routes.
     *
     * @return void
     */
    protected function mapFileRoutes()
    {
        LitstackRoute::get('js/app.js', FileController::class.'@litJs')->name('js');
        LitstackRoute::public()->get('js/app2.js', FileController::class.'@lit2Js')->name('app2.js');
        LitstackRoute::public()->get('js/prism.js', FileController::class.'@prismJs')->name('prism.js');
        LitstackRoute::public()->get('css/app.css', FileController::class.'@litCss')->name('css');
        LitstackRoute::public()->get('images/lit-logo.png', FileController::class.'@litLogo')->name('logo');

        // Favicon.
        LitstackRoute::public()->get('favicon/favicon-32x32.png', FileController::class.'@litFaviconBig')->name('favicon-big');
        LitstackRoute::public()->get('favicon/favicon-16x16.png', FileController::class.'@litFaviconSmall')->name('favicon-small');
    }
}
