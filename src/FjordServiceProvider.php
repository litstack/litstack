<?php

namespace Fjord;

use FjordApp\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Service providers and console commands that should be registered without 
 * Fjord being installed. All services that should only be registered if Fjord 
 * is installed are specified in \Fjord\FjordPackage. 
 */
class FjordServiceProvider extends ServiceProvider
{
    /**
     * Service providers.
     *
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        Fjord\Discover\PackageDiscoverServiceProvider::class,
        Support\Macros\BuilderMacros::class,
    ];

    /**
     * Console commands.
     *
     * @var array
     */
    protected $commands = [
        Commands\Install\FjordInstall::class,
        Commands\FjordGuard::class,
    ];

    /**
     * Aliases.
     *
     * @var array
     */
    protected $aliases = [
        'Fjord' => Support\Facades\Fjord::class,
        'FjordApp' => Support\Facades\FjordApp::class,
        'FjordLang' => Support\Facades\FjordLang::class,
    ];

    /**
     * Middlewares.
     *
     * @var array
     */
    protected $middlewares = [
        'fjord.auth' => Auth\Middleware\Authenticate::class,
    ];

    /**
     * Create a new FjordServiceProvider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->aliasLoader = AliasLoader::getInstance();
    }

    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        // Load Fjord views.
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'fjord');

        $this->middlewares($router);

        $this->publish();
    }

    /**
     * Register middlewares.
     *
     * @param Router $router
     * @return void
     */
    protected function middlewares(Router $router)
    {
        foreach ($this->middlewares as $alias => $middleware) {
            $router->aliasMiddleware($alias, $middleware);
        }
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

        $this->app->register(Application\ApplicationServiceProvider::class);

        $this->app->singleton('fjord.app', function () {
            return new Application\Application();
        });

        $this->app->singleton('fjord.kernel', function ($app) {
            return new Kernel($app->get('fjord.app'));
        });

        // Bind fjord
        $this->app['fjord']->bindApp($this->app['fjord.app']);

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

        if (!fjord()->installed()) {
            return;
        }

        $this->app->register(\App\Providers\FjordServiceProvider::class);
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

        $this->publishes([
            __DIR__ . '/../publish/routes' => base_path('routes'),
        ], 'routes');
    }
}
