<?php

namespace AwStudio\Fjord;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use AwStudio\Fjord\Support\Facades\Fjord as FjordFacade;
use Illuminate\Support\Facades\Route;

use AwStudio\Fjord\Auth\Middleware\Authenticate;

class FjordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->app->register('AwStudio\Fjord\Routing\RouteServiceProvider');
        $this->app->register('AwStudio\Fjord\Form\FormServiceProvider');
        $this->app->register('AwStudio\Fjord\Blade\BladeServiceProvider');

        /**
         * Load the Fjord views
         *
         */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fjord');

        /**
         * Publish Fjords assets
         *
         */
        $this->publishes([
            __DIR__.'/../publish/assets' => public_path('fjord'),
        ], 'public');

        /**
         * Publish Fjords config
         *
         */
        $this->publishes([
            __DIR__.'/../publish/config' => config_path(),
        ], 'config');

        /**
         * Publish Fjords migrations
         *
         */
        $this->publishes([
            __DIR__.'/../publish/database/migrations' => database_path('migrations'),
        ], 'migrations');

        /**
         * Register Fjord Auth Middleware
         *
         */
        $router->aliasMiddleware('fjord.auth', Authenticate::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Fjord', FjordFacade::class);

        $this->app->singleton('fjord.router', function ($app) {
            return new Routing\Router($app['events'], $app);
        });

        $this->app->singleton('fjord', function () {
            return new Fjord\Fjord();
        });

        if (App::runningInConsole()) {
            $this->registerConsoleCommands();
        }
    }

    private function registerConsoleCommands()
    {
        $this->commands(Commands\FjordInstall::class);
        $this->commands(Commands\FjordAdmin::class);
        $this->commands(Commands\FjordCrud::class);
    }
}
