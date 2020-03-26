<?php

namespace AwStudio\Fjord\Fjord\Concerns;

trait ManagesPackages
{
    protected $packages = [];

    protected function loadPackageManifest()
    {
        $this->packages = require(base_path('bootstrap/cache/fjord.php'));
    }

    public function getPackages()
    {
        return $this->packages;
    }
}
