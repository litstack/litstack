<?php

namespace Ignite\Application;

use Ignite\Application\Composer\HttpErrorComposer;
use Ignite\Application\Controllers\NotFoundController;
use Ignite\Application\Kernel\HandleRouteMiddleware;
use Ignite\Application\Kernel\HandleViewComposer;
use Ignite\Commands\LitFormPermissions;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Route;
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
        $this->app->singleton('lit.vue.app', function () {
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
        $this->litErrorPages();
        $this->addCssFilesFromConfig();
    }

    /**
     * Add css files from config lit.assets.css.
     *
     * @return void
     */
    public function addCssFilesFromConfig()
    {
        $this->app->afterResolving('lit.app', function ($Lit) {
            $files = config('lit.assets.css') ?? [];
            foreach ($files as $file) {
                $Lit->style($file);
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
        View::composer('litstack::app', HandleViewComposer::class);
    }

    /**
     * Better Lit error pages.
     *
     * @return void
     */
    public function litErrorPages()
    {
        // Register route {any} after all service providers have been booted to
        // not override other routes.
        $this->app->booted(function () {
            Route::get('{any}', NotFoundController::class)
                ->where('any', '.*')
                ->name('not_found');
        });

        // Set view composer for error pages to use lit error pages when needed.
        View::composer('errors::*', HttpErrorComposer::class);

        // This macro is needed to override the error page view.
        ViewClass::macro('setView', function (string $view) {
            $this->view = $view;
            $this->setPath(view('litstack::app')->getPath());

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
        $this->app->singleton('lit.command.form-permissions', function ($app) {
            // Passing migrator to command.
            return new LitFormPermissions($app['migrator']);
        });
        // Registering command.
        $this->commands(['lit.command.form-permissions']);
    }
}
