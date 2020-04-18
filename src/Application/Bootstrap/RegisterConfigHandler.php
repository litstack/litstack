<?php

namespace Fjord\Application\Bootstrap;

use Fjord\Support\Facades\Package;
use Fjord\Application\Application;

class RegisterConfigHandler
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
            foreach ($package->getConfigHandler() as $dependency => $handler) {
                $app->registerConfigHandler($dependency, $handler);
            }
        }
    }
}
