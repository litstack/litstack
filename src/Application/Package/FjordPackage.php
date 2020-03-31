<?php

namespace AwStudio\Fjord\Application\Package;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use AwStudio\Fjord\Application\Application;
use AwStudio\Fjord\Support\Facades\FjordRoute;

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
     * List of handlers for config files.
     * 
     * @var array
     */
    protected $configHandler = [];

    /**
     * List of all config files that have already been loaded.
     *
     * @var array
     */
    protected $loadedConfigFiles = [];

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
     * Get components.
     * 
     * @return array $components
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * Get config directory path.
     *
     * @return string $path
     */
    protected function getConfigDirectory()
    {
        $path = resource_path(config('fjord.resource_path') . '/');

        $path .= $this->hasRootAccess()
            ? ""
            : "packages/{$this->name}/";

        return $path;
    }

    /**
     * Get path to config directory.
     * 
     * @param string $name
     * @return string
     */
    protected function getConfigPath(string $name = '')
    {
        $path = $this->getConfigDirectory();

        if (!$name) {
            return $path;
        }

        return $path . str_replace('.', '/', $name) . '.php';
    }

    /**
     * Get raw uncompiled config.
     *
     * @param string $name
     * @return void
     */
    public function rawConfig(string $name)
    {
        return require $this->getConfigPath($name);
    }

    /**
     * Load config once.
     * 
     * @param string $name
     * @return array $config
     */
    public function config(string $name)
    {
        if (!array_key_exists($name, $this->loadedConfigFiles)) {
            return $this->loadConfig($name);
        }

        return $this->loadedConfigFiles[$name];
    }

    /**
     * Load config regardless of whether it has been loaded before.
     *
     * @param string $name
     * @return array $config
     */
    public function loadConfig(string $name)
    {
        return $this->loadedConfigFiles = $this->requireConfig($name);
    }

    /**
     * Require config file and execute config handler.
     *
     * @param string $name
     * @return array $config
     */
    protected function requireConfig(string $name)
    {
        $path = $this->getConfigPath($name);

        if (!File::exists($path)) {
            return [];
        }

        $attributes = require $path;

        // Find handler for config file.
        foreach ($this->configHandler as $location => $handler) {
            if (Str::is($location, $name)) {
                return with(new $handler($attributes))->getAttributes();
            }
        }

        // No handler found, return config attributes.
        return $attributes;
    }

    /**
     * Get list of config files in directory.
     *
     * @param string $name
     * @return array $files
     */
    public function configFiles(string $name)
    {
        $path = $this->getConfigDirectory() . '/' . str_replace('.', '/', $name);

        $files = glob("{$path}/*.php");

        return collect($files)->mapWithKeys(function ($path) use ($name) {
            return ["{$name}." . str_replace('.php', '', basename($path)) => $path];
        });
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
