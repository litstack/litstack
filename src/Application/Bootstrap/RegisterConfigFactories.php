<?php

namespace Ignite\Application\Bootstrap;

use Ignite\Application\Application;
use Ignite\Support\Facades\Package;

class RegisterConfigFactories
{
    /**
     * Registers config factories from packages.
     *
     * @param  \Ignite\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        foreach (Package::all() as $package) {
            foreach ($package->getConfigFactories() as $dependency => $factory) {
                $app->registerConfigFactory($dependency, $factory);
            }
        }
    }
}
