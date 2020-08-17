<?php

namespace Fjord\Application;

use Fjord\Application\Composer\HttpErrorComposer;
use Fjord\Application\Controllers\NotFoundController;
use Fjord\Application\Kernel\HandleRouteMiddleware;
use Fjord\Application\Kernel\HandleViewComposer;
use Fjord\Commands\FjordFormPermissions;
use Fjord\Support\Facades\Config;
use Fjord\Support\Facades\FjordRoute;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewClass;

class ApplicationServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFormPermissionsCommand();

        $this->registerVueApplication();
    }

    /**
     * Register Vue application component.
     *
     * @return void
     */
    protected function registerVueApplication()
    {
        $this->app->singleton('fjord.vue.app', function () {
            return new AppComponent;
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router): void
    {
        $this->handleKernel($router);
        $this->fjordErrorPages();
        $this->addCssFilesFromConfig();
    }

    /**
     * Add css files from config fjord.assets.css.
     *
     * @return void
     */
    public function addCssFilesFromConfig()
    {
        $this->app->afterResolving('fjord.app', function ($fjordApp) {
            $files = config('fjord.assets.css') ?? [];
            foreach ($files as $file) {
                $fjordApp->style($file);
            }
        });
    }

    /**
     * Handle kernel methods "handleRoute" and "handleView".
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    protected function handleKernel(Router $router)
    {
        // Kernel method handleRoute gets executed here.
        $router->pushMiddlewareToGroup('web', HandleRouteMiddleware::class);

        // Kernel method handleView gets executed here.
        View::composer('fjord::app', HandleViewComposer::class);
    }

    /**
     * Better Fjord error pages.
     *
     * @return void
     */
    public function fjordErrorPages()
    {
        // Register route {any} after all service providers have been booted to
        // not override other routes.
        $this->app->booted(function () {
            FjordRoute::get('{any}', NotFoundController::class)
                ->where('any', '.*')
                ->name('not_found');
        });

        // Set view composer for error pages to use fjord error pages when needed.
        View::composer('errors::*', HttpErrorComposer::class);

        // This macro is needed to override the error page view.
        ViewClass::macro('setView', function (string $view) {
            $this->view = $view;
            $this->setPath(view('fjord::app')->getPath());

            return $this;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerFormPermissionsCommand()
    {
        // Bind singleton.
        $this->app->singleton('fjord.command.form-permissions', function ($app) {
            // Passing migrator to command.
            return new FjordFormPermissions($app['migrator']);
        });
        // Registering command.
        $this->commands(['fjord.command.form-permissions']);
    }
}
