<?php

namespace AwStudio\Fjord;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use AwStudio\Fjord\Facades\Fjord as FjordFacade;

use AwStudio\Fjord\Http\Middleware\FjordAuthenticate;

class FjordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
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
         * Register Fjord Auth Middleware
         *
         */
        $router->aliasMiddleware('fjord.auth', FjordAuthenticate::class);
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

        $this->app->singleton('fjord', function () {
            return new Fjord();
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
