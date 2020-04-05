<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Support\Facades\Package;
use Fjord\Application\Application;

class RegisterPackageExtensions
{
    /**
     * Registers extensions from packages.
     * 
     * @param \Fjord\Application\Application $app
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
