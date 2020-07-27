<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Application\Application;
use Fjord\Support\Facades\Package;

class RegisterConfigFactories
{
    /**
     * Registers config factories from packages.
     *
     * @param \Fjord\Application\Application $app
     *
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
