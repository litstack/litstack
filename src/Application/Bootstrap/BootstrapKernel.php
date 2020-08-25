<?php

namespace Ignite\Application\Bootstrap;

use Ignite\Application\Application;
use Lit\Kernel;
use Illuminate\Console\Application as Artisan;
use Illuminate\Support\Facades\App;

class BootstrapKernel
{
    /**
     * Registers artisan commands of all lit packages.
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
     * Register package providers.
     *
     * @param  mixed $package
     * @return void
     */
    public function registerProviders($providers)
    {
        foreach ($providers as $provider) {
            app()->register($provider);
        }
    }
}
