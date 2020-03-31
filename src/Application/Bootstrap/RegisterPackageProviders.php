<?php

namespace AwStudio\Fjord\Application\Bootstrap;

use AwStudio\Fjord\Support\Facades\Package;
use AwStudio\Fjord\Application\Application;

class RegisterPackageProviders
{
    /**
     * Registers service providers of all fjord packages.
     * 
     * @param AwStudio\Fjord\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        foreach (Package::all() as $name => $package) {
            foreach ($package->providers() as $provider) {
                app()->register($provider);
            }
        }
    }
}
