<?php

namespace Fjord\Application;

use Illuminate\View\View;
use Illuminate\Support\Str;

class Application
{
    use Concerns\ManagesFiles;

    /**
     * The application's bindings.
     *
     * @var array[]
     */
    protected $bindings = [];

    /**
     * The Fjord extension provided by your application.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

    /**
     * Singleton classes.
     *
     * @var array
     */
    protected $singletons = [
        'config.loader' => \Fjord\Application\Config\ConfigLoader::class
    ];

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  string[]  $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers)
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            with(new $bootstrapper())->bootstrap($this);
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
     * @param Illuminate\View\View $view
     * @return void
     */
    public function build(View $view)
    {
        $this->bootPackages();

        $this->get('vue')->build($view);
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
     * Execute extensions for the given components.
     * 
     * @param Illuminate\View\View $view
     * @return void
     */
    public function extend(View $view)
    {
        $this->get('vue')->extend($view, $this->extensions);
    }

    /**
     * Get Fjord application binding.
     * 
     * @param string $binding
     * @return instance
     */
    public function get($binding)
    {
        return $this->bindings[$binding] ?? null;
    }

    /**
     * Register a binding with the container.
     *
     * @param  string  $abstract
     * @param  Instance  $instance
     * @param  bool  $shared
     * @return void
     */
    public function bind($abstract, $instance)
    {
        $this->bindings[$abstract] = $instance;
    }

    /**
     * Load config for a Fjord package.
     * 
     * @param string $config
     * @return array $config
     */
    public function config(string $config)
    {
        return $this->get('packages')->config('aw-studio/fjord', $config);
    }

    /**
     * Register extension class.
     * 
     * @param string $component
     * @param string $extension
     * @return void
     */
    public function registerExtension(string $key, string $extension)
    {
        $component = $key;
        $name = "";
        if (Str::contains($key, '::')) {
            $component = explode('::', $key)[0];
            $name = explode('::', $key)[1];
        }

        $this->extensions[] = [
            "component" => $component,
            "name" => $name,
            "extension" => $extension
        ];
    }

    /**
     * Get singletons.
     *
     * @return array $singletons
     */
    public function singletons()
    {
        return $this->singletons;
    }
}
