<?php

namespace Fjord\Application\Package;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Fjord\Application\Application;
use Fjord\Support\Facades\FjordRoute;

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
     * List of artisan commands to be registered for this package.
     * 
     * @var array
     */
    protected $commands = [];

    /**
     * List of components this package contains.
     * 
     * @var array
     */
    protected $components = [];

    /**
     * List of extensions for other packages.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * List of handlers for config files.
     * 
     * @var array
     */
    protected $configHandler = [];

    /**
     * Fjord navigation entry presets for this package.
     *
     * @var array
     */
    protected $navPresets = [];

    /**
     * Create a new Package instance.
     * 
     * @param array $config
     * @return void
     */
    public function __construct($name, $extra)
    {
        $this->name = $name;

        if (!is_array($extra)) {
            $extra = [];
        }

        $this->extra = $extra;
    }

    /**
     * Add navigation entry preset.
     *
     * @param string $name
     * @param array $entry
     * @return void
     */
    public function addNavPreset(string $name, array $entry)
    {
        $this->navPresets[$name] = $entry;
    }

    /**
     * Get navigation entry preset.
     *
     * @param string $name
     * @param array $merge
     * @return void
     */
    public function navEntry(string $name, array $merge = [])
    {
        return array_merge($this->navPresets[$name], $merge);
    }

    /**
     * Get all navigation entry presets.
     *
     * @return array
     */
    public function getNavPresets()
    {
        return $this->navPresets;
    }

    /**
     * Check if navigation entry preset exists.
     *
     * @param string $name
     * @return boolean
     */
    public function hasNavPreset(string $name)
    {
        return array_key_exists($name, $this->navPresets);
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
        return 'fjord.' . str_replace('/', '.', $this->name) . '.';
    }

    /**
     * Get providers.
     * 
     * @return array $providers
     */
    public function providers()
    {
        return $this->providers;
    }

    /**
     * Get commands.
     * 
     * @return array $commands
     */
    public function commands()
    {
        return $this->commands;
    }

    /**
     * Get list of extensions for other packages.
     *
     * @return array $extensions
     */
    public function extensions()
    {
        return $this->extensions;
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
     * Get config handler.
     *
     * @return array
     */
    public function getConfigHandler()
    {
        return $this->configHandler;
    }

    /**
     * Boot package.
     *
     * @return void
     */
    public function boot(Application $app)
    {
        //
    }
}
