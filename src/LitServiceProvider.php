<?php

namespace Ignite;

use Ignite\Support\Facades\Config;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

/**
 * Service providers and console commands that should be registered without
 * Lit being installed. All services that should only be registered if Lit
 * is installed are specified in \Ignite\LitPackage.
 */
class LitServiceProvider extends ServiceProvider
{
    /**
     * Service providers.
     *
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        Foundation\Discover\PackageDiscoverServiceProvider::class,
        Support\Macros\ServiceProvider::class,
    ];

    /**
     * Console commands.
     *
     * @var array
     */
    protected $commands = [
        Commands\Install\LitInstall::class,
        Commands\LitGuard::class,
    ];

    /**
     * Aliases.
     *
     * @var array
     */
    protected $aliases = [
        'Lit' => Support\Facades\Lit::class,
    ];

    /**
     * Middlewares.
     *
     * @var array
     */
    protected $middlewares = [
        'lit.auth' => Auth\Middleware\Authenticate::class,
    ];

    /**
     * Create a new LitServiceProvider instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
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
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'litstack');

        $this->middlewares($router);

        $this->publish();
    }

    /**
     * Register middlewares.
     *
     * @param Router $router
     *
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
        $this->config();
        $this->alias();
        $this->lit();
        $this->artisan();

        // Register providers then Lit application last.
        $this->providers();
        $this->lightsOn();
    }

    /**
     * Merge lit config.
     * https://laravel.com/docs/7.x/packages#configuration.
     *
     * @return void
     */
    public function config()
    {
        // Merging the new lit config from vendor package folder to the one
        // that is located in config/lit.php, to avoid errors when older
        // version of Lit was installed before.
        $this->mergeConfigFrom(
            __DIR__.'/../publish/config/lit.php',
            'lit'
        );
    }

    /**
     * Register Lit helper singleton.
     *
     * @return void
     */
    protected function lit()
    {
        $this->app->singleton('lit', function ($app) {
            return new Foundation\Lit($app);
        });

        $this->app->singleton('lit.router', function ($app) {
            return new Routing\Router($app['events'], $app);
        });
    }

    /**
     * Lights on: Lit application comes to life.
     *
     * @return void
     */
    protected function lightsOn()
    {
        if (! $this->app['lit']->installed()) {
            return;
        }

        $this->app->register(Application\ApplicationServiceProvider::class);

        $this->app->singleton('lit.app', function ($app) {
            return new Application\Application($app);
        });

        $this->app->singleton(\Lit\Kernel::class, function ($app) {
            return new \Lit\Kernel($app->get('lit.app'));
        });

        // Bind lit
        $this->app['lit']->bindApp($this->app['lit.app']);

        // Initialize kernel singleton.
        $this->app[\Lit\Kernel::class];
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

        if (! lit()->installed()) {
            return;
        }
    }

    /**
     * Register artisan commands.
     *
     * @return void
     */
    protected function artisan()
    {
        if (! App::runningInConsole()) {
            return;
        }

        $this->commands($this->commands);
        $this->loadFactories();
    }

    /**
     * Define publishers.
     *
     * @return void
     */
    protected function publish()
    {
        $this->publishes([
            __DIR__.'/../publish/config' => config_path(),
        ], 'config');

        $this->publishes([
            __DIR__.'/../publish/database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Load Lit factories.
     *
     * @return void
     */
    protected function loadFactories()
    {
        $this->app[Factory::class]->load(__DIR__.'/Factories');
    }
}
