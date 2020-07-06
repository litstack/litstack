<?php

namespace Fjord\Fjord\Discover;

use Illuminate\Foundation\Providers\ArtisanServiceProvider;

class PackageDiscoverServiceProvider extends ArtisanServiceProvider
{
    /**
     * Override Laravel package:discover command to add discover Fjord packages.
     *
     * @return void
     */
    protected function registerPackageDiscoverCommand()
    {
        $this->app->singleton('command.package.discover', function () {
            return new PackageDiscoverCommand();
        });
    }
}
