<?php

namespace AwStudio\Fjord\Foundation\Providers;

use Illuminate\Foundation\Providers\ArtisanServiceProvider as LaravelArtisanServiceProvider;
use AwStudio\Fjord\Foundation\Console\FjordPackageDiscoverCommand;
use AwStudio\Fjord\Foundation\Console\PackageDiscoverCommand;

class ArtisanServiceProvider extends LaravelArtisanServiceProvider
{

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerPackageDiscoverCommand()
    {
        $this->app->singleton('command.package.discover', function () {
            return new PackageDiscoverCommand;
        });
    }
}
