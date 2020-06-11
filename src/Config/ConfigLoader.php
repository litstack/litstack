<?php

namespace Fjord\Config;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

/**
 * Config singleton.
 * 
 * @see \Fjord\Support\Facades\Config
 */
class ConfigLoader
{
    /**
     * Namespace to config files.
     *
     * @var string
     */
    protected $namespace = "FjordApp\Config";

    /**
     * Stack of loaded instances.
     *
     * @var array
     */
    protected $loaded = [];

    /**
     * Get key.
     *
     * @param sring $key
     * @return string
     */
    public function getKey(string $key)
    {
        if ($this->isKeyNamespace($key)) {
            return $this->getKeyFromNamespace($key);
        }
        if ($this->isKeyPath($key)) {
            return $this->getKeyFromPath($key);
        }
        return $key;
    }

    /**
     * Is key namespace.
     *
     * @param string $key
     * @return boolean
     */
    protected function isKeyNamespace(string $key)
    {
        return !Str::contains($key, '.') && Str::contains($key, '\\');
    }

    /**
     * Is key path.
     *
     * @param string $key
     * @return boolean
     */
    protected function isKeyPath(string $key)
    {
        return !Str::contains($key, '.') && Str::contains($key, '/');
    }

    /**
     * Get config by key.
     *
     * @param string $key
     * @param array ...$params
     * @return mixed
     */
    public function get(string $key, ...$params)
    {
        $key = $this->getKey($key);

        // Return from stack if already loaded.
        if (array_key_exists($key, $this->loaded)) {
            return $this->loaded[$key];
        }

        if (!$this->exists($key)) {
            return;
        }

        // Get classname by key.
        $class = $this->getNamespaceFromKey($key);

        // Initialize new config handler.
        $instance = new ConfigHandler(
            // Passing params to new instance.
            new $class(...$params)
        );

        // Add config to stack.
        $this->loaded[$key] = $instance;

        return $instance;
    }

    /**
     * Get namespace of config by key.
     *
     * @param string $key
     * @return string
     */
    public function getNamespaceFromKey(string $key)
    {
        $name = '';
        foreach (explode('.', $key) as $part) {
            $name .= "\\" . ucfirst(Str::camel($part));
        }

        return $this->namespace . $name . "Config";
    }

    /**
     * Get config from path.
     *
     * @param string $path
     * @return ConfigHandler
     */
    public function getFromPath(string $path)
    {
        return $this->get(
            $this->getKeyFromPath($path)
        );
    }

    /**
     * Get path from key.
     *
     * @param string $key
     * @return string
     */
    public function getPathFromKey(string $key)
    {
        $path = collect(explode('.', $key))
            ->map(fn ($item) => ucfirst(Str::camel($item)))
            ->implode('/');
        return base_path("fjord/app/Config/{$path}Config.php");
    }

    /**
     * Get key from path
     *
     * @param string $path
     * @return string
     */
    public function getKeyFromPath(string $path)
    {
        return collect(explode('/', str_replace('Config.php', '', str_replace(base_path('fjord/app/Config') . '/', '', $path))))
            ->map(fn ($item) => Str::snake($item))
            ->implode('.');
    }

    /**
     * Get key from namespace.
     *
     * @param string $namespace
     * @return string
     */
    public function getKeyFromNamespace(string $namespace)
    {
        return collect(explode('\\', Str::replaceLast('Config', '', str_replace('FjordApp\\Config\\', '', $namespace))))
            ->map(fn ($item) => Str::snake($item))
            ->implode('.');
    }

    /**
     * Check if config class exists.
     *
     * @param string $key
     * @return boolean
     */
    public function exists(string $key)
    {
        return File::exists(
            $this->getPathFromKey($key),
        );
    }
}
