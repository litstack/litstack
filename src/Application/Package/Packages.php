<?php

namespace Fjord\Application\Package;

use Exception;

class Packages
{
    /**
     * List of all packages found in composer.json files.
     * 
     * @var array
     */
    protected $packages = [];

    /**
     * List of packages with access to root routes and root config path.
     * 
     * @var array
     */
    protected $rootAccess = [
        "aw-studio/fjord",
        "aw-studio/fjord-permissions",
    ];

    /**
     * Create instance.
     * 
     * @param array $packages
     * @return void
     */
    public function __construct($packages)
    {
        $this->packages = $packages ?? [];
    }

    /**
     * Get package by name.
     * 
     * @return $package
     */
    public function get($name)
    {
        return $this->packages[$name];
    }

    /**
     * Get Navigation entry preset.
     *
     * @param string $package
     * @param string $name
     * @param array $merge
     * @return void
     * 
     * @throws \Exception
     */
    public function navEntry(string $package, $name = null, array $merge = [])
    {
        if (is_string($name)) {
            return $this->get($package)->navEntry($name, $merge);
        }

        if (is_array($name)) {
            $merge = $name;
        }

        // Search root packages for navigation entry preset.
        $name = $package;

        foreach ($this->rootAccess as $rootPackageName) {
            $rootPackage = $this->get($rootPackageName);
            if (!$rootPackage->hasNavPreset($name)) {
                continue;
            }

            return $rootPackage->navEntry($name, $merge);
        }

        throw new Exception('No navigation entry preset with name "' . $name . '" found.');
    }

    /**
     * Get list of all packages.
     * 
     * @return array
     */
    public function all()
    {
        return $this->packages;
    }

    /**
     * Check if package has root acccess.
     * 
     * @param string $name
     * @return bool
     */
    public function hasRootAccess($name)
    {
        return in_array($name, $this->rootAccess);
    }
}
