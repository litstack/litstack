<?php

namespace Ignite\Foundation;

use Ignite\Foundation\Console\ExtendCommand;
use Ignite\Foundation\Console\InstallCommand;
use Ignite\Support\Facades\Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router as LaravelRouter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class LitstackServiceProvider extends ServiceProvider
{
    /**
     * Service providers.
     *
     * @var array
     */
    protected $providers = [
        \Ignite\Routing\RoutingServiceProvider::class,
        \Ignite\Auth\AuthServiceProvider::class,
        \Ignite\Support\SupportServiceProvider::class,
    ];

    /**
     * Console commands.
     *
     * @var array
     */
    protected $commands = [
        'Install' => 'lit.command.install',
        'Extend'  => 'lit.command.extend',
    ];

    /**
     * Aliases.
     *
     * @var array
     */
    protected $aliases = [
        'Lit' => \Ignite\Support\Facades\Lit::class,
    ];

    /**
     * Middlewares.
     *
     * @var array
     */
    protected $middlewares = [
        'lit.auth.middleware' => \Ignite\Auth\Middleware\Authenticate::class,
    ];

    /**
     * Alias laoder instance.
     *
     * @var AliasLoader
     */
    protected $aliasLoader;

    /**
     * Create a new LitstackServiceProvider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->aliasLoader = AliasLoader::getInstance();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->middlewares();

        $this->publish();
    }

    /**
     * Register middlewares.
     *
     * @return void
     */
    protected function middlewares()
    {
        $this->callAfterResolving('router', function (LaravelRouter $router) {
            foreach ($this->middlewares as $alias => $middleware) {
                $router->aliasMiddleware($alias, $middleware);
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();

        $this->config();
        $this->alias();
        $this->lit();
        $this->artisan();

        // Register providers then Lit application last.
        $this->providers();
        $this->lightsOn();
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach ($this->commands as $command => $abstract) {
            call_user_func_array([$this, "register{$command}Command"], [$abstract]);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerInstallCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new InstallCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerExtendCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new ExtendCommand($app['files']);
        });
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
            __DIR__.'/../../publish/config/lit.php',
            'lit'
        );
    }

    /**
     * Register Lit singleton.
     *
     * @return void
     */
    protected function lit()
    {
        $this->app->singleton('lit', function ($app) {
            return new Litstack($app);
        });

        $this->app->bind(Litstack::class, 'lit');
    }

    /**
     * Lights on: Ignite Litstack application.
     *
     * @return void
     */
    protected function lightsOn()
    {
        $this->app->make(LightsOn::class)->ignite();
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
            __DIR__.'/../../publish/config' => config_path(),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../publish/database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Load Litstack factories.
     *
     * @return void
     */
    protected function loadFactories()
    {
        $this->app->afterResolving(Factory::class, function ($factory) {
            $factory->load(__DIR__.'/../Factories');
        });
    }
}
