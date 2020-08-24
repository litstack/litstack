<?php

namespace Lit\Application;

use Lit\Application\Composer\HttpErrorComposer;
use Lit\Application\Controllers\NotFoundController;
use Lit\Application\Kernel\HandleRouteMiddleware;
use Lit\Application\Kernel\HandleViewComposer;
use Lit\Commands\LitFormPermissions;
use Lit\Support\Facades\Config;
use Lit\Support\Facades\LitRoute;
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
        $this->app->afterResolving('lit.app', function ($litApp) {
            $files = config('lit.assets.css') ?? [];
            foreach ($files as $file) {
                $litApp->style($file);
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
        View::composer('lit::app', HandleViewComposer::class);
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
            LitRoute::get('{any}', NotFoundController::class)
                ->where('any', '.*')
                ->name('not_found');
        });

        // Set view composer for error pages to use lit error pages when needed.
        View::composer('errors::*', HttpErrorComposer::class);

        // This macro is needed to override the error page view.
        ViewClass::macro('setView', function (string $view) {
            $this->view = $view;
            $this->setPath(view('lit::app')->getPath());

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
