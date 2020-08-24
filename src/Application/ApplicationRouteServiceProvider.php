<?php

namespace Lit\Application;

use Lit\Application\Controllers\FileController;
use Lit\Support\Facades\LitRoute;
use Lit\Translation\Controllers\LoadTranslationsController;
use Lit\Translation\Controllers\SetLocaleController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ApplicationRouteServiceProvider extends LaravelRouteServiceProvider
{
    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        $this->mapLitRoutes();
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
            LitRoute::group(base_path('lit/routes/lit.php'));
        }
    }

    /**
     * Map lit routes.
     *
     * @return void
     */
    protected function mapLitRoutes()
    {
        LitRoute::group(function () {
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
        LitRoute::get('js/app.js', FileController::class.'@litJs')->name('js');
        LitRoute::public()->get('js/app2.js', FileController::class.'@lit2Js')->name('app2.js');
        LitRoute::public()->get('js/prism.js', FileController::class.'@prismJs')->name('prism.js');
        LitRoute::public()->get('js/ctk.js', FileController::class.'@ctkJs')->name('ctk.js');
        LitRoute::public()->get('css/app.css', FileController::class.'@litCss')->name('css');
        LitRoute::public()->get('images/lit-logo.png', FileController::class.'@litLogo')->name('logo');
        LitRoute::public()->get('favicon/favicon-32x32.png', FileController::class.'@litFaviconBig')->name('favicon-big');
        LitRoute::public()->get('favicon/favicon-16x16.png', FileController::class.'@litFaviconSmall')->name('favicon-small');
    }
}
