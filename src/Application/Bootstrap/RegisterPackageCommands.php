<?php

namespace AwStudio\Fjord\Application\Bootstrap;

use Illuminate\Console\Application as Artisan;
use AwStudio\Fjord\Support\Facades\Package;
use AwStudio\Fjord\Application\Application;
use Illuminate\Support\Facades\App;

class RegisterPackageCommands
{
    /**
     * Registers artisan commands of all fjord packages.
     * 
     * @param \AwStudio\Fjord\Application\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        if (!App::runningInConsole()) {
            return;
        }

        foreach (Package::all() as $package) {
            Artisan::starting(function ($artisan) use ($package) {
                $artisan->resolveCommands($package->commands());
            });
        }
    }
}
