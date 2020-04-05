<?php

namespace AwStudio\Fjord\Application;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewClass;
use Illuminate\Support\ServiceProvider;
use AwStudio\Fjord\Support\Facades\FjordRoute;
use AwStudio\Fjord\Application\Kernel\HandleViewComposer;
use AwStudio\Fjord\Application\Kernel\HandleRouteMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router): void
    {
        // Kernel method handleRoute gets executed here.
        $router->pushMiddlewareToGroup('web', HandleRouteMiddleware::class);

        // Kernel method handleView gets executed here.
        View::composer('fjord::app', HandleViewComposer::class);

        $this->viewMacros();
        $this->fjordErrorPages();
    }

    public function viewMacros()
    {
        ViewClass::macro('setView', function (string $view) {
            $this->view = $view;
            $this->setPath(view('fjord::app')->getPath());
        });
    }

    public function fjordErrorPages()
    {
        $this->app->booted(function () {
            FjordRoute::get('{any}', function () {
                throw new NotFoundHttpException;
            })->where('any', '.*')->name('not_found');
        });

        View::composer('errors::*', HttpErrorComposer::class);
    }
}
