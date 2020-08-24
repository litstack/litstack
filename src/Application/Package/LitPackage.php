<?php

namespace Lit\Application\Package;

use Closure;
use Lit\Application\Application;
use Lit\Support\Facades\LitRoute;

abstract class LitPackage
{
    /**
     * Composer package name.
     *
     * @var string
     */
    protected $name;

    /**
     * Configuration for the package that is defined in its composer.json.
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
     * List of factories for config files.
     *
     * @var array
     */
    protected $configFactories = [];

    /**
     * Lit navigation entry presets for this package.
     *
     * @var array
     */
    protected $navPresets = [];

    /**
     * Create a new Package instance.
     *
     * @param array $config
     *
     * @return void
     */
    public function __construct($name, $extra)
    {
        $this->name = $name;

        if (! is_array($extra)) {
            $extra = [];
        }

        $this->extra = $extra;
    }

    /**
     * Add navigation entry preset.
     *
     * @param string $name
     * @param array  $entry
     *
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
     * @param array  $merge
     *
     * @return array
     */
    public function navPreset(string $name, array $merge = [])
    {
        $preset = array_merge($this->navPresets[$name], $merge);

        $title = $preset['title'] ?? null;
        if ($title instanceof Closure) {
            $preset['title'] = $preset['title']();
        }

        if ($preset['link'] instanceof Closure) {
            $preset['link'] = $preset['link']();
        }

        return $preset;
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
     *
     * @return bool
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
        return lit()->get('packages')->hasRootAccess($this->name);
    }

    /**
     * Create route for package.
     *
     * @return $route
     */
    public function route()
    {
        return LitRoute::package($this->name);
    }

    /**
     * Get route prefix.
     *
     * @return string $prefix
     */
    public function getRoutePrefix()
    {
        return config('lit.route_prefix').($this->hasRootAccess()
            ? '/'
            : '/'.$this->name);
    }

    /**
     * Get route as.
     *
     * @return string $as
     */
    public function getRouteAs()
    {
        return 'lit.';
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
     * @return array $commands
     */
    public function components()
    {
        return $this->components;
    }

    /**
     * Get config handler.
     *
     * @return array
     */
    public function getConfigFactories()
    {
        return $this->configFactories;
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
