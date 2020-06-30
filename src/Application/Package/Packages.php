<?php

namespace Fjord\Application\Package;

use Illuminate\Support\Arr;
use InvalidArgumentException;

class Packages
{
    /**
     * List of all packages found in composer.json files.
     *
     * @var array
     */
    protected $packages = [];

    /**
     * Fjord application instance.
     *
     * @var \Fjord\Application\Application
     */
    protected $app;

    /**
     * List of packages with access to root routes and root config path.
     *
     * @var array
     */
    protected $rootAccess = [
        'aw-studio/fjord',
        'aw-studio/fjord-permissions',
    ];

    /**
     * Create instance.
     *
     * @param array $packages
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
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
     * Add one ore many packages.
     *
     * @param string|array $packages
     *
     * @return void
     */
    public function add($packages)
    {
        foreach (Arr::wrap($packages) as $name => $package) {
            $this->packages[$name] = $package;
        }
    }

    /**
     * Get Navigation entry preset.
     *
     * @param string $package
     * @param string $name
     * @param array  $merge
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function navPreset(string $package, $name = null, array $merge = [])
    {
        if (is_string($name)) {
            return $this->get($package)->navPreset($name, $merge);
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

            return $rootPackage->navPreset($name, $merge);
        }

        throw new InvalidArgumentException('No navigation entry preset with name "'.$name.'" found.');
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
     *
     * @return bool
     */
    public function hasRootAccess($name)
    {
        return in_array($name, $this->rootAccess);
    }
}
