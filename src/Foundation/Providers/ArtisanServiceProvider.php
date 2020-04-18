<?php

namespace Fjord\Foundation\Providers;

use Illuminate\Foundation\Providers\ArtisanServiceProvider as LaravelArtisanServiceProvider;
use Fjord\Foundation\Console\PackageDiscoverCommand;

class ArtisanServiceProvider extends LaravelArtisanServiceProvider
{
    /**
     * Override Laravel package:discover command to add discover Fjord packages.
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
