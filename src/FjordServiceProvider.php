<?php

namespace Fjord;

use Illuminate\Support\Str;
use Illuminate\Routing\Router;
use Fjord\Support\Facades\Config;
use Fjord\Crud\Fields\Block\Block;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

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
        Support\Macros\ServiceProvider::class,
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
        $this->config();
        $this->alias();
        $this->fjord();
        $this->artisan();

        // Register providers then Fjord application last.
        $this->providers();
        $this->lightsOn();
    }

    /**
     * Merge fjord config.
     * https://laravel.com/docs/7.x/packages#configuration
     *
     * @return void
     */
    public function config()
    {
        // Merging the new fjord config from vendor package folder to the one 
        // that is located in config/fjord.php, to avoid errors when older 
        // version of Fjord was installed before.
        $this->mergeConfigFrom(
            __DIR__ . '/../publish/config/fjord.php',
            'fjord'
        );
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
        if (!$this->app['fjord']->installed()) {
            return;
        }

        $this->app->register(Application\ApplicationServiceProvider::class);

        $this->app->singleton('fjord.app', function () {
            return new Application\Application();
        });

        $this->app->singleton(\FjordApp\Kernel::class, function ($app) {
            return new \FjordApp\Kernel($app->get('fjord.app'));
        });

        // Bind fjord
        $this->app['fjord']->bindApp($this->app['fjord.app']);

        // Initialize kernel singleton.
        $this->app[\FjordApp\Kernel::class];

        // Fix: config_type
        if (app()->runningInConsole() || env('APP_ENV') == 'testing' || env('APP_ENV') === null) return;
        if (!DB::table('form_blocks')->where('config_type', '')->exists()) {
            return;
        }
        $this->app->booted(function () {
            foreach (File::allFiles(base_path('fjord/app/Config')) as $configPath) {
                if (!Str::endsWith(basename($configPath), 'Config.php')) {
                    continue;
                }
                try {
                    $namespace = 'FjordApp\\Config\\' . collect(explode('/', str_replace('.php', '', str_replace(base_path('fjord/app/Config') . '/', '', $configPath))))
                        ->map(fn ($item) => ucfirst(Str::camel($item)))
                        ->implode('\\');

                    $config = Config::get($namespace);
                    $fields = $config->show->getRegisteredFields();
                } catch (\Throwable $e) {
                    continue;
                }
                if (!$config->has('show')) {
                    continue;
                }

                foreach ($fields as $field) {
                    if (!$field instanceof Block) {
                        continue;
                    }

                    $query = DB::table('form_blocks')->where('model_type', $config->model)->where('config_type', '');
                    if ($config->model == FormField::class) {
                        $formField = DB::table('form_fields')->where('config_type', $namespace)->first();

                        if (!$formField) {
                            return;
                        }
                        $query->where('model_id', $formField->id);
                    }
                    $query->update([
                        'config_type' => get_class($config->getConfig())
                    ]);
                }
            }
        });
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
            __DIR__ . '/../publish/config' => config_path(),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../publish/database/migrations' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Load Fjord factories.
     *
     * @return void
     */
    protected function loadFactories()
    {
        $this->app[Factory::class]->load(__DIR__ . '/Factories');
    }
}
