<?php

namespace Ignite\Application;

use Illuminate\View\View;

class Kernel
{
    /**
     * Lit application instance.
     *
     * @var Lit\Application\Application
     */
    protected $app;

    /**
     * List of bootstrappers that should be executed before when the
     * kernel is initialized. They get executed in the given order.
     *
     * @var array
     */
    protected $bootstrappers = [
        Bootstrap\BootstrapKernel::class,
    ];

    /**
     * Lit application service providers.
     *
     * @var array
     */
    public $providers = [];

    /**
     * Create a new Lit kernel instance.
     *
     * @param \Ignite\Application\Application $app
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
        // TODO: ...
    }

    /**
     * Handle litstack::app view before it gets executed.
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
