<?php

namespace Ignite\Application;

use Ignite\Translation\Translator;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\Support\Str;
use Illuminate\View\View;

class Application
{
    use Concerns\ManagesAssets;

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
     * Get locale for the Lit application.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->laravel[Translator::class]->getLocale();
    }

    /**
     * Check if the Lit application is running in a locale.
     *
     * @param  string $locale
     * @return bool
     */
    public function isLocale(string $locale)
    {
        return $this->laravel[Translator::class]->isLocale($locale);
    }

    /**
     * Bind composer to litstack::app view.
     *
     * @param  string                   $composer
     * @return \Illuminate\View\Factory
     */
    public function composer(string $composer)
    {
        return ViewFactory::composer('litstack::app', $composer);
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
     *  Build Vue application.
     *
     * @param  Illuminate\View\View $view
     * @return void
     */
    public function build(View $view)
    {
        $this->get('vue.app')->bindView($view);
    }

    /**
     * Get Lit application binding.
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
     * Get abstract for lit application.
     *
     * @param  string $abstract
     * @return string
     */
    public function getAbstract($abstract)
    {
        return Str::start($abstract, 'lit.');
    }
}
