<?php

namespace Ignite\Application\Bootstrap;

use Ignite\Application\Application;
use Ignite\Application\Kernel;

class BootstrapKernel
{
    /**
     * Bootstrap.
     *
     * @param  \Ignite\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app, Kernel $kernel)
    {
        $this->app = $app;
        $this->kernel = $kernel;

        $this->registerProviders($kernel->providers);
    }

    /**
     * Register providers.
     *
     * @param  array $providers
     * @return void
     */
    public function registerProviders($providers)
    {
        foreach ($providers as $provider) {
            app()->register($provider);
        }
    }
}
