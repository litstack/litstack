<?php

namespace AwStudio\Fjord\Application;

use Illuminate\View\View;

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
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

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
     * @param array $extensions
     * @return void
     */
    public function extend(View $view, array $extensions)
    {
        $this->get('vue')->extend($view, $extensions);
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
}
