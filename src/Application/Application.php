<?php

namespace AwStudio\Fjord\Application;

use Illuminate\View\View;

class Application
{
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
     * Build Vue application.
     * 
     * @param Illuminate\View\View $view
     * @return void
     */
    public function build(View $view)
    {
        $this->get('vue')->build($view);
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
}