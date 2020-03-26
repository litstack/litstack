<?php

namespace AwStudio\Fjord\Fjord\Concerns;

use AwStudio\Fjord\Fjord\Extending\Package;

trait ManagesPackages
{
    protected $packages = [];

    protected function loadPackageManifest()
    {
        $packages = require(base_path('bootstrap/cache/fjord.php'));
        foreach($packages as $name => $packageConfig) {
            $this->packages[$name] = new Package($name, $packageConfig);
        }

        // Fjord Package
        $this->packages['aw-studio/fjord'] = new Package('aw-studio/fjord', $packageConfig);;
    }

    public function package($name)
    {
        return $this->packages[$name] ?? null;
    }

    public function getPackages()
    {
        return $this->packages;
    }

    public function getExtension($name)
    {
        foreach($this->packages as $package) {
            foreach($package->getExtensions() as $route => $extension) {
                if($package->getRouteAs() . $route == $name) {
                    return $extension;
                }
            }
        }
    }
}
