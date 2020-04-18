<?php

namespace Fjord\Configuration;

use ReflectionClass;
use BadMethodCallException;
use Illuminate\Support\Str;

abstract class ConfigFile
{
    /**
     * Loaded config attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Find config handler for matching this config.
     *
     * @return void
     */
    protected function findMatchingConfigHandler()
    {
        $reflector = new ReflectionClass(static::class);
        foreach (fjord()->getConfigHandler() as $dependency => $handler) {
            if ($reflector->getParentClass()->name == $dependency) {
                return $handler;
            }
        }
    }

    /**
     * Load select config attributes.
     *
     * @param string|array ...$keys
     * @return array $attributes
     */
    public function get(...$keys)
    {
        $attributes = [];

        foreach ($keys as $key) {
            $attributes[$key] = $this->getAttribute($key);
        }

        return collect($attributes);
    }

    /**
     * Get config attribute from loaded stack or new.
     *
     * @param string $name
     * @return any
     */
    public function getAttribute(string $name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        if (is_callable([static::class, $name]) && method_exists($this, $name)) {
            return $this->resolveMethod($name);
        }

        return $this->$name;
    }

    /**
     * Call config method and store attributes.
     *
     * @param string $method
     * @param array ...$parameters
     * @return mixed
     */
    public function callMethod($method, ...$parameters)
    {
        $attributes = $this->$method(...$parameters);

        $this->attributes[$method] = $attributes;

        return $attributes;
    }

    /**
     * Resolve config method.
     *
     * @param string $name
     * @param array $parameters
     * @return void
     */
    protected function resolveMethod($method, ...$parameters)
    {
        $handler = $this->findMatchingConfigHandler();
        if (!$handler) {
            return $this->callMethod($method, ...$parameters);
        }

        $instance = new $handler;

        if (!method_exists($instance, $method)) {
            return $this->callMethod($method, ...$parameters);
        }

        // Resolve config method in handler.
        $attributes = $instance->$method($this, function (...$params) use ($method, $parameters) {
            $params = array_merge($params, $parameters);

            return $this->$method(...$params);
        });

        $this->attributes[$method] = $attributes;

        return $attributes;
    }

    /**
     * Call config class method.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     * 
     * @throws BadMethodCallException
     */
    public function __call(string $method, $parameters = [])
    {
        if (is_callable([static::class, $method]) && method_exists($this, $method)) {
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
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }
}
