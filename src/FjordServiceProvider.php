<?php

namespace Fjord;

use App\Fjord\Kernel;
use Fjord\Application\Middlewares\StopRedirectForNotFound;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Fjord\Auth\Middleware\Authenticate;

class FjordServiceProvider extends ServiceProvider
{
    /**
     * Service providers that should be registered without Fjord being installed.
     * All service providers that should only be registered if Fjord is 
     * installed are specified in \Fjord\FjordPackage
     *
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        Application\ApplicationServiceProvider::class,
        Foundation\Providers\ArtisanServiceProvider::class,
        Support\Macros\BuilderMacros::class,
        \App\Providers\FjordServiceProvider::class,
    ];

    /**
     * Console commands that should be registered without Fjord being installed.
     * All commands that should only be registered if Fjord is installed are 
     * specified in \Fjord\FjordPackage
     *
     * @var array
     */
    protected $commands = [
        Commands\Install\FjordInstall::class,
        Commands\FjordGuard::class,
        Commands\FjordCrudPermissions::class,
    ];

    /**
     * Aliases.
     *
     * @var array
     */
    protected $aliases = [
        'Fjord' => Support\Facades\Fjord::class,
        'FjordLang' => Support\Facades\FjordLang::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // Load Fjord views.
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'fjord');

        // Middelware
        $router->aliasMiddleware('fjord.auth', Authenticate::class);
        $router->aliasMiddleware('fjord.404', StopRedirectForNotFound::class);

        $this->publish();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->alias();
        $this->fjord();
        $this->artisan();

        // Register providers then app last.
        $this->providers();
        $this->lightsOn();
    }

    /**
     * Register Fjord helper singleton.
     *
     * @return void
     */
    protected function fjord()
    {
        $this->app->singleton('fjord', function () {
            return new Fjord\Fjord();
        });

        $this->app->singleton('fjord.router', function ($app) {
            return new Routing\FjordRouter($app['events'], $app);
        });
    }

    /**
     * Lights on: Fjord application comes to life.
     *
     * @return void
     */
    protected function lightsOn()
    {
        if (!$this->app->get('fjord')->installed()) {
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

    /**
     * Register aliases.
     *
     * @return void
     */
    protected function alias()
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->aliases as $alias => $class) {
            $loader->alias($alias, $class);
        }
    }

    /**
     * Register providers.
     *
     * @return void
     */
    protected function providers()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Register artisan commands.
     *
     * @return void
     */
    protected function artisan()
    {
        if (!App::runningInConsole()) {
            return;
        }

        $this->commands($this->commands);
    }

    /**
     * Define publishers.
     *
     * @return void
     */
    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../publish/config' => config_path(),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../publish/database/migrations' => database_path('migrations'),
        ], 'migrations');
    }
}
