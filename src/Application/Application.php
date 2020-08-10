<?php

namespace Fjord\Application;

use Fjord\Translation\Translator;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * The Application class manages all depencies for the view fjord::app:
 * Bootstrapping Application,
 * Registering and booting packages,
 * Registering and calling config handlers,
 * Binding css files for fjord,
 * Bind composer to the fjord::app view.
 */
class Application
{
    use Concerns\ManagesAssets;

    /**
     * Registered config factories.
     *
     * @var array
     */
    protected $configFactories = [];

    /**
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

    /**
     * Laravel Application instance.
     *
     * @var LaravelApplication
     */
    protected $laravel;

    /**
     * Singleton classes.
     *
     * @var array
     */
    protected $singletons = [
        'packages' => Package\Packages::class,
    ];

    /**
     * Create new Application instance.
     *
     * @param  LaravelApplication $laravel
     * @return void
     */
    public function __construct(LaravelApplication $laravel)
    {
        $this->laravel = $laravel;
    }

    /**
     * Get locale for the Fjord application.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->laravel[Translator::class]->getLocale();
    }

    /**
     * Check if the Fjord application is running in a locale.
     *
     * @param  string $locale
     * @return bool
     */
    public function isLocale(string $locale)
    {
        return $this->laravel[Translator::class]->isLocale($locale);
    }

    /**
     * Bind composer to fjord::app view.
     *
     * @param  string                   $composer
     * @return \Illuminate\View\Factory
     */
    public function composer(string $composer)
    {
        return ViewFactory::composer('fjord::app', $composer);
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  array $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers, $kernel)
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            with(new $bootstrapper())->bootstrap($this, $kernel);
        }
    }

    /**
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped()
    {
        return $this->hasBeenBootstrapped;
    }

    /**
     * Boot packages and build Vue application.
     *
     * @param  Illuminate\View\View $view
     * @return void
     */
    public function build(View $view)
    {
        $this->bootPackages();

        $this->get('vue.app')->bindView($view);
    }

    /**
     * Boot all packages.
     *
     * @return void
     */
    protected function bootPackages()
    {
        foreach ($this->get('packages')->all() as $package) {
            $package->boot($this);
        }
    }

    /**
     * Get Fjord application binding.
     *
     * @param  string   $binding
     * @return instance
     */
    public function get($abstract)
    {
        return $this->laravel->get($this->getAbstract($abstract));
    }

    /**
     * Register a binding with the application.
     *
     * @param  string         $abstract
     * @param  Closure|string $concrete
     * @return void
     */
    public function bind($abstract, $concrete)
    {
        $this->laravel->bind($this->getAbstract($abstract), $concrete);
    }

    /**
     * Register config handler.
     *
     * @param  string $dependency
     * @param  string $handler
     * @return void
     */
    public function registerConfigFactory(string $dependency, string $factory)
    {
        $this->configFactories[$dependency] = $factory;
    }

    /**
     * Get config factories.
     *
     * @return array
     */
    public function getConfigFactories()
    {
        return $this->configFactories;
    }

    /**
     * Get all singleton classes.
     *
     * @return array $singletons
     */
    public function singletons()
    {
        return $this->singletons;
    }

    /**
     * Register a shared binding in the application.
     *
     * @param  string         $abstract
     * @param  Closure|string $concrete
     * @return void
     */
    public function singleton($abstract, $concrete)
    {
        $this->laravel->singleton($this->getAbstract($abstract), $concrete);
    }

    /**
     * Get abstract for fjord application.
     *
     * @param  string $abstract
     * @return string
     */
    public function getAbstract($abstract)
    {
        return Str::start($abstract, 'fjord.');
    }
}
