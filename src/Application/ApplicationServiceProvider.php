<?php

namespace Fjord\Application;

use Fjord\Application\Controllers\NotFoundController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewClass;
use Illuminate\Support\ServiceProvider;
use Fjord\Support\Facades\FjordRoute;
use Fjord\Application\Kernel\HandleViewComposer;
use Fjord\Application\Kernel\HandleRouteMiddleware;
use Fjord\Commands\FjordFormPermissions;
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

    public function register()
    {
        $this->registerFormPermissionsCommand();
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
            FjordRoute::get('{any}', NotFoundController::class)
                ->where('any', '.*')
                ->name('not_found');
        });

        View::composer('errors::*', HttpErrorComposer::class);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerFormPermissionsCommand()
    {
        $this->app->singleton('fjord.command.form-permissions', function ($app) {
            return new FjordFormPermissions($app['migrator']);
        });
        $this->commands(['fjord.command.form-permissions']);
    }
}
