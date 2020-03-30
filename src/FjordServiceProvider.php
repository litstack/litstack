<?php

namespace AwStudio\Fjord;

use App\Fjord\Kernel;
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
use AwStduio\Fjord\Foundation\Console\PackageDiscoverCommand;
use AwStudio\Fjord\Fjord\Extend\ExtensionComposer;

class FjordServiceProvider extends ServiceProvider
{
    protected $providers = [
        \AwStudio\Fjord\Routing\RouteServiceProvider::class,
        //\AwStudio\Fjord\User\ServiceProvider::class,
        //\AwStudio\Fjord\Form\ServiceProvider::class,
        \AwStudio\Fjord\Auth\ServiceProvider::class,
        \AwStudio\Fjord\Application\ApplicationServiceProvider::class,
        \AwStudio\Fjord\Foundation\Providers\ArtisanServiceProvider::class,
        \App\Providers\FjordServiceProvider::class
    ];

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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'fjord');

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

        fjord()->addLangPath(fjord_path('resources/lang/'));
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
        $this->singletons();
        $this->facades();
        $this->fjord();

        if (App::runningInConsole()) {
            $this->registerConsoleCommands();
        }

        $this->addFiles();

        // Register providers then app last.
        $this->providers();
        $this->app();
    }

    protected function fjord()
    {        
        $this->app->singleton('fjord.router', function ($app) {
            return new Routing\FjordRouter($app['events'], $app);
        });

        $this->app->singleton('fjord', function () {
            return new Fjord\Fjord();
        });
    }

    protected function app()
    {
        if(! $this->app->get('fjord')->installed()) {
            return;
        }

        $this->app->singleton('fjord.app', function () {
            return new Application\Application();
        });

        $this->app->singleton('fjord.kernel', function ($app) {
            return new Kernel($app->get('fjord.app'));
        });

        // Initialize kernel singleton.
        $this->app->get('fjord.kernel');
    }

    protected function singletons()
    {

    }

    protected function facades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Fjord', FjordFacade::class);
    }

    protected function providers()
    {
        foreach($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function addFiles()
    {
        if (!fjord()->installed()) {
            return;
        }

        $this->app['fjord']->addCssFile('/' . config('fjord.route_prefix') . '/css/app.css');
        foreach (config('fjord.assets.css') as $path) {
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
            __DIR__ . '/../publish/config' => config_path(),
        ], 'config');

        /**
         * Publish Fjords migrations
         *
         */
        $this->publishes([
            __DIR__ . '/../publish/database/migrations' => database_path('migrations'),
        ], 'migrations');
    }
}
