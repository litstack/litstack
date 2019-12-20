<?php

namespace AwStudio\Fjord;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use AwStudio\Fjord\Support\Facades\Fjord as FjordFacade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
        $this->app->register('AwStudio\Fjord\RolesPermissions\ServiceProvider');
        $this->app->register('AwStudio\Fjord\Form\ServiceProvider');
        $this->app->register('AwStudio\Fjord\Blade\BladeServiceProvider');

        /**
         * Load the Fjord views
         *
         */
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fjord');

        /**
         * Register Fjord Auth Middleware
         *
         */
        $router->aliasMiddleware('fjord.auth', Authenticate::class);

        /**
         * Register Fjord Builder
         *
         */
        $this->builder();

        $this->publish();
    }

    protected function builder()
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        Str::contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
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

        $this->app->singleton('fjord.router', function ($app) {
            return new Routing\Router($app['events'], $app);
        });

        $this->app->singleton('fjord', function () {
            return new Fjord\Fjord();
        });

        if (App::runningInConsole()) {
            $this->registerConsoleCommands();
        }

        $this->addFiles();

    }

    public function addFiles()
    {
        if(! fjord()->installed()) {
            return;
        }

        $this->app['fjord']->addCssFile('/' . config('fjord.route_prefix') . '/css/app.css');
        foreach(config('fjord.assets.css') as $path) {
            $this->app['fjord']->addCssFile($path);
        }
    }

    private function registerConsoleCommands()
    {
        $this->commands(Commands\FjordInstall::class);
        $this->commands(Commands\FjordGuard::class);
        $this->commands(Commands\FjordAdmin::class);
        $this->commands(Commands\FjordUser::class);
        $this->commands(Commands\FjordCrud::class);
        $this->commands(Commands\FjordCrudPermissions::class);
        $this->commands(Commands\FjordDefaultPermissions::class);
    }

    protected function publish()
    {
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
    }
}
