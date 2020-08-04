<?php

namespace Fjord\Application;

use Illuminate\View\View;

class Kernel
{
    /**
     * Fjord application instance.
     *
     * @var Fjord\Application\Application
     */
    protected $app;

    /**
     * List of bootstrappers that should be executed before when the
     * kernel is initialized. They get executed in the given order.
     *
     * @var array
     */
    protected $bootstrappers = [
        Bootstrap\RegisterSingletons::class,
        Bootstrap\BootstrapTranslator::class,
        Bootstrap\BootstrapKernel::class,
        Bootstrap\DiscoverPackages::class,
        Bootstrap\RegisterConfigFactories::class,
        Bootstrap\BootstrapVueApplication::class,
        Bootstrap\RegisterPackages::class,
    ];

    /**
     * Fjord application service providers.
     *
     * @var array
     */
    public $providers = [];

    /**
     * Create a new Fjord kernel instance.
     *
     * @param \Fjord\Application\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->bootstrap();
    }

    /**
     * Handle incomming route.
     *
     * @return void
     */
    public function handleRoute($route)
    {
        // TODO: Find something to do for here.
    }

    /**
     * Handle fjord::app view before it gets executed.
     *
     * @return void
     */
    public function handleView(View $view)
    {
        $this->build($view);

        //$this->extend($view);
    }

    /**
     * Get the bootstrap classes for the application.
     *
     * @return void
     */
    public function bootstrap()
    {
        $this->app->bootstrapWith($this->bootstrappers, $this);
    }

    /**
     * Build application for the given route.
     *
     * @param Illuminate\View\View $view
     *
     * @return void
     */
    public function build(View $view)
    {
        $this->app->build($view);
    }
}
