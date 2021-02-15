<?php

namespace Ignite\Config;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * Config singleton.
 *
 * @see \Ignite\Support\Facades\Config
 */
class ConfigLoader
{
    /**
     * Namespace to config files.
     *
     * @var string
     */
    protected $namespace = 'Lit\\Config';

    /**
     * Stack of loaded instances.
     *
     * @var array
     */
    protected $loaded = [];

    /**
     * Registered config factories.
     *
     * @var array
     */
    protected $factories = [];

    /**
     * Register config factory for the given dependency.
     *
     * @param  string $dependency
     * @param  string $factory
     * @return $this
     */
    public function factory($dependency, $factory)
    {
        $this->factories[$dependency] = $factory;

        return $this;
    }

    /**
     * Get config factories.
     *
     * @return array
     */
    public function factories()
    {
        return $this->factories;
    }

    /**
     * Get key.
     *
     * @param  sring  $key
     * @return string
     */
    public function getKey($key)
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
     * @param  string $key
     * @return bool
     */
    protected function isKeyNamespace($key)
    {
        return ! Str::contains($key, '.') && Str::contains($key, '\\');
    }

    /**
     * Is key path.
     *
     * @param  string $key
     * @return bool
     */
    protected function isKeyPath($key)
    {
        return ! Str::contains($key, '.') && Str::contains($key, '/');
    }

    /**
     * Get config by key.
     *
     * @param  string $key
     * @param  array  ...$params
     * @return mixed
     */
    public function get($key, ...$params)
    {
        if (class_exists($key)) {
            $class = $key;
            $key = $this->getKey($key);
        } else {
            $key = $this->getKey($key);
            $class = $this->getNamespaceFromKey($key);
        }

        // Return from stack if already loaded.
        if ($this->loaded($class)) {
            return $this->loaded[$class];
        }

        if (! $this->exists($class)) {
            return;
        }

        $instance = $this->make($class, ...$params);

        // Add config to stack.
        $this->loaded[$class] = $instance;

        return $instance;
    }

    public function make($class, ...$params)
    {
        // Initialize new config handler.
        $handler = new ConfigHandler(
            // Passing params to new instance.
            $config = new $class(...$params)
        );

        $this->registerConfigFactories($handler, $config);

        return $handler;
    }

    /**
     * Find factories by config depenecies.
     *
     * @param  ConfigHandler $handler
     * @param  mixed         $config
     * @return void
     */
    protected function registerConfigFactories($handler, $config)
    {
        $reflector = new ReflectionClass($config);
        $parent = $reflector->getParentClass();
        $uses = class_uses_recursive($config);

        foreach ($this->factories() as $dependency => $factory) {
            // Matching parent class.
            if ($parent) {
                if ($config instanceof $dependency) {
                    $handler->registerFactory($factory);
                }
            }

            if (in_array($dependency, $uses)) {
                $handler->registerFactory($factory);
            }
        }
    }

    /**
     * Determines wheter a config for the given key has been loaded before.
     *
     * @param  string $class
     * @return bool
     */
    public function loaded($class)
    {
        if (! $this->isNamespace($class)) {
            $class = $this->getNamespaceFromKey($this->getKey($class));
        }

        return array_key_exists($class, $this->loaded);
    }

    /**
     * Determine if a key is a namespace.
     *
     * @param  string $key
     * @return bool
     */
    protected function isNamespace($key)
    {
        if (class_exists($key)) {
            return true;
        }

        return Str::contains($key, '\\');
    }

    /**
     * Get namespace of config by key.
     *
     * @param  string $key
     * @return string
     */
    public function getNamespaceFromKey($key)
    {
        $name = '';
        foreach (explode('.', $key) as $part) {
            $name .= '\\'.ucfirst(Str::camel($part));
        }

        return $this->namespace.$name.'Config';
    }

    /**
     * Get config from path.
     *
     * @param  string        $path
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
     * @param  string $key
     * @return string
     */
    public function getPathFromKey(string $key)
    {
        $path = collect(explode('.', $key))
            ->map(fn ($item) => ucfirst(Str::camel($item)))
            ->implode('/');

        return base_path("lit/app/Config/{$path}Config.php");
    }

    /**
     * Get key from path.
     *
     * @param  string $path
     * @return string
     */
    public function getKeyFromPath(string $path)
    {
        return collect($this->explodePath($path))
            ->map(fn ($item) => Str::snake($item))
            ->implode('.');
    }

    /**
     * Explode path.
     *
     * @param  string $path
     * @return array
     */
    protected function explodePath(string $path)
    {
        // Replacing path for windows and unix.
        $modified = str_replace('\\', '/', $path);
        $modified = str_replace(str_replace('\\', '/', base_path('lit/app/Config')).'/', '', $modified);
        $modified = str_replace('Config.php', '', $modified);

        return explode('/', $modified);
    }

    /**
     * Get key from namespace.
     *
     * @param  string $namespace
     * @return string
     */
    public function getKeyFromNamespace(string $namespace)
    {
        return collect(explode('\\', Str::replaceLast('Config', '', str_replace('Lit\\Config\\', '', $namespace))))
            ->map(fn ($item) => Str::snake($item))
            ->implode('.');
    }

    /**
     * Check if config class exists.
     *
     * @param  string $key
     * @return bool
     */
    public function exists(string $key)
    {
        if (class_exists($key)) {
            return true;
        }

        $this->getPathFromKey($key);

        return File::exists(
            $this->getPathFromKey($key),
        );
    }
}
