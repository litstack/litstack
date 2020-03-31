<?php

namespace AwStudio\Fjord\Application\Package;

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
        "aw-studio/fjord"
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

    /**
     * Load config for a package.
     * 
     * @param string $package
     * @param string $config
     * @return array $config
     */
    public function config(string $package, string $config)
    {
        return $this->get($package)->config($config);
    }
}
