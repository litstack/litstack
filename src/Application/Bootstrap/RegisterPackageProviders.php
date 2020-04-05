<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Support\Facades\Package;
use Fjord\Application\Application;

class RegisterPackageProviders
{
    /**
     * Registers service providers of all fjord packages.
     * 
     * @param Fjord\Application\Application $app
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
