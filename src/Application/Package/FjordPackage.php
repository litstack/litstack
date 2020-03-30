<?php

namespace AwStudio\Fjord\Application\Package;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

abstract class FjordPackage
{
    /**
     * Composer package name.
     * 
     * @var string
     */
    protected $name;

    /**
     * Configuration for the package that is defined in its composer.json
     * 
     * @var array
     */
    protected $extra;

    /**
     * List of service providers to be registered for this package.
     * 
     * @var array
     */
    protected $providers = [];

    /**
     * List of components this package contains.
     * 
     * @var array
     */
    protected $components = [];

    /**
     * List of handlers for config files.
     * 
     * @var array
     */
    protected $configFiles = [];

    /**
     * Create a new Package instance.
     * 
     * @param array $config
     * @return void
     */
    public function __construct($name, $extra)
    {
        $this->name = $name;

        if(! is_array($extra)) {
            $extra = [];
        }

        $this->extra = $extra;
    }

    /**
     * Check if package has root access.
     * 
     * @return bool
     */
    public function hasRootAccess()
    {
        return fjord()->app()->get('packages')->hasRootAccess($this->name);
    }


    /**
     * Create route for package.
     * 
     * @return $route
     */
    public function route()
    {
        return FjordRoute::package($this->name);
    }

    /**
     * Get route prefix.
     * 
     * @return string $prefix
     */
    public function getRoutePrefix()
    {
        return config('fjord.route_prefix') . ($this->hasRootAccess()
            ? "/"
            : "/" . $this->name);
    }

    /**
     * Get route as.
     * 
     * @return string $as
     */
    public function getRouteAs()
    {
        return 'fjord.' . str_replace('/', '.', $this->name);
    }

    /**
     * Get providers from config.
     * 
     * @return array $providers
     */
    public function providers()
    {
        return $this->providers;
    }

    /**
     * Get components.
     * 
     * @return array $components
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Get path to config directory.
     * 
     * @param string $name
     * @return string
     */
    protected function getConfigPath(string $name = '')
    {
        $path = resource_path(config('fjord.resource_path') . '/');

        $path .= $this->hasRootAccess()
            ? ""
            : "packages/{$this->name}/";

        if(! $name) {
            return $path;
        }

        return $path . str_replace('.', '/', $name) .'.php';
    }

    /**
     * Load config and execute handler if exists.
     * 
     * @param string $name
     * @return array $config
     */
    public function config($name)
    {
        $path = $this->getConfigPath($name);

        if(! File::exists($path)) {
            return [];
        }

        $attributes = require $path;
       
        // Find handler for config file.
        foreach($this->configFiles as $location => $handler) {
            if(Str::is($location, $name)) {
                return with(new $handler($attributes))->getAttributes();
            }
        }

        // No handler found, return config attributes.
        return $attributes;
    }
}