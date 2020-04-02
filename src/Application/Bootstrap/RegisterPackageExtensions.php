<?php

namespace AwStudio\Fjord\Application\Bootstrap;

use AwStudio\Fjord\Support\Facades\Package;
use AwStudio\Fjord\Application\Application;

class RegisterPackageExtensions
{
    /**
     * Registers extensions from packages.
     * 
     * @param \AwStudio\Fjord\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        foreach (Package::all() as $package) {
            foreach ($package->extensions() as $component => $extension) {
                $app->registerExtension($component, $extension);
            }
        }
    }
}
