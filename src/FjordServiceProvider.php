<?php

namespace AwStudio\Fjord;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use AwStudio\Fjord\Facades\Fjord as FjordFacade;
use Illuminate\Support\Facades\Route;

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
        $this->app->register('AwStudio\Fjord\FjordRouteServiceProvider');

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
        $router->aliasMiddleware('fjord.auth', FjordAuthenticate::class);


        /**
         * Register Fjord Blade Directives
         *
         */
        Blade::directive('block', function($expression){
            return "<?php
                    \$loop = (object) [
                        'iteration' => 0
                    ];
                    foreach (\$repeatables[($expression)] as \$repeatable) {
                        \$view = 'repeatables.'.\$repeatable->type;
                        echo \$__env->make(\$view, array_except(get_defined_vars(), ['__data', '__path']))->render();
                        \$loop->iteration++;
                    }
                    ?>";
        });
        Blade::directive('repeatable', function($expression){
            return "<?php
                    echo \$repeatable->content[($expression)];
                    ?>";
        });
        Blade::directive('imageUrl', function($expression){
            return "<?php
                    echo \$repeatable->getFirstMediaUrl('image', ($expression));
                    ?>";
        });
        Blade::directive('field', function($expression){
            return "<?php
                    echo \$content->where('field_name', ($expression))->first()->content;
                    ?>";
        });
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
