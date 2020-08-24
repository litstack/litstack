<?php

namespace Lit\Application\Bootstrap;

use Lit\Application\Application;
use Lit\Support\Facades\Package;

class RegisterConfigFactories
{
    /**
     * Registers config factories from packages.
     *
     * @param  \Lit\Application\Application $app
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
