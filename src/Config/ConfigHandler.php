<?php

namespace Fjord\Config;

use BadMethodCallException;
use Fjord\Support\Facades\Config;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use TypeError;

class ConfigHandler
{
    /**
     * Loaded config attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Instance of config class.
     *
     * @var \Fjord\Config\ConfigFile
     */
    protected $config;

    /**
     * Config factory instances.
     *
     * @var array
     */
    protected $factories = [];

    /**
     * Methods with their associated factories.
     *
     * @var array
     */
    protected $methodFactories = [];

    /**
     * Create new ConfigHandler instance.
     *
     * @param Instance $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        $this->findConfigFactories();
    }

    /**
     * Get namespace of config.
     *
     * @return string
     */
    public function getType()
    {
        return get_class($this->config);
    }

    /**
     * Find factories by config depenecies.
     *
     * @return void
     */
    public function findConfigFactories()
    {
        $reflector = new ReflectionClass($this->config);
        $parent = $reflector->getParentClass();
        $uses = class_uses_recursive($this->config);

        foreach (fjord()->getConfigFactories() as $dependency => $factory) {

            // Matching parent class.
            if ($parent) {
                if ($this->config instanceof $dependency) {
                    $this->registerFactory($factory);
                }
            }

            if (in_array($dependency, $uses)) {
                $this->registerFactory($factory);
            }
        }
    }

    /**
     * Get config key from config class.
     *
     * @return string
     */
    public function getKey()
    {
        return Config::getKey(get_class($this->config));
    }

    /**
     * Register config factory.
     *
     * @param string $factory
     *
     * @throws \TypeError
     *
     * @return void
     */
    public function registerFactory($factory)
    {
        $instance = new $factory($this);

        if (! is_subclass_of($factory, ConfigFactory::class)) {
            throw new TypeError("Config factory {$factory} must extend " . ConfigFactory::class.'.');
        }

        $this->factories[] = $instance;

        $reflector = new ReflectionClass($factory);
        foreach ($reflector->getMethods() as $method) {
            if ($factory != $method->class) {
                continue;
            }

            if ($method->getModifiers() != ReflectionMethod::IS_PUBLIC) {
                continue;
            }

            $this->methodFactories[$method->name] = $instance;
        }
    }

    /**
     * Load select config attributes.
     *
     * @param string|array ...$keys
     *
     * @return array $attributes
     */
    public function get(...$keys)
    {
        if (count($keys) == 1 && trait_exists($keys[0])) {
            $trait = new ReflectionClass($keys[0]);
            $keys = collect(array_merge($trait->getProperties(), $trait->getMethods()))->map(function ($reflect) {
                // Looking for abstract public or public methods or properties.
                if (
                    $reflect->getModifiers() != ReflectionMethod::IS_PUBLIC
                    && $reflect->getModifiers() != ReflectionMethod::IS_PUBLIC + ReflectionMethod::IS_ABSTRACT
                ) {
                    return;
                }

                return $reflect->name;
            })->filter()->toArray();
        }
        $attributes = [];

        foreach ($keys as $key) {
            $attributes[$key] = $this->getAttribute($key);
        }

        return collect($attributes);
    }

    /**
     * Check if config has method.
     *
     * @param string $method
     *
     * @return bool
     */
    public function hasMethod(string $method)
    {
        return method_exists($this->config, $method);
    }

    /**
     * Config has attribute.
     *
     * @return bool
     */
    public function has(string $attribute)
    {
        if ($this->hasMethod($attribute)) {
            return true;
        }

        return property_exists($this->config, $attribute);
    }

    /**
     * Get config attribute from loaded stack or new.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        // Check for existing method.
        $method = $this->getMethodName($name);

        if ($this->hasMethod($method)) {
            return $this->resolveMethod($method);
        }

        return $this->config->$name;
    }

    /**
     * Call config method and store attributes.
     *
     * @param string $method
     * @param array  ...$parameters
     *
     * @return mixed
     */
    public function callMethod($method, $parameters)
    {
        $attributes = $this->config->$method(...$parameters);

        $this->setAttribute($method, $attributes);

        return $attributes;
    }

    /**
     * Set attribute.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[Str::snake($name)] = $value;
    }

    /**
     * Check if a method has a factory.
     *
     * @param string $method
     *
     * @return bool
     */
    public function methodHasFactory(string $method)
    {
        return array_key_exists($method, $this->methodFactories);
    }

    /**
     * Get factory for method.
     *
     * @param string $method
     *
     * @return Instance
     */
    public function getMethodFactory(string $method)
    {
        return $this->methodFactories[$method];
    }

    /**
     * Resolve config method.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return array
     */
    protected function resolveMethod($method, $parameters = [])
    {
        if (! $this->methodHasFactory($method)) {
            return $this->callMethod($method, $parameters);
        }

        $factory = $this->getMethodFactory($method);

        return $factory->handle($method, $parameters);
    }

    /**
     * Get config.
     *
     * @return Instance
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get method name.
     *
     * @param string $method
     *
     * @return string
     */
    public function getMethodName(string $method)
    {
        return Str::camel($method);
    }

    /**
     * Call config class method.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $method = $this->getMethodName($method);

        if ($this->hasMethod($method)) {
            return $this->resolveMethod($method, $parameters);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }

    /**
     * Get config attribute.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }
}
