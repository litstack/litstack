<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Application\Application;
use FjordApp\Kernel;
use Illuminate\Console\Application as Artisan;
use Illuminate\Support\Facades\App;

class BootstrapKernel
{
    /**
     * Registers artisan commands of all fjord packages.
     *
     * @param  \Fjord\Application\Application $app
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
