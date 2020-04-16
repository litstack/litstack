<?php

namespace Fjord\Support;

use ReflectionClass;
use BadMethodCallException;
use Illuminate\Support\Str;

class Config
{
    /**
     * Loaded config attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Load select config attributes.
     *
     * @param string|array ...$keys
     * @return array $attributes
     */
    public function get(...$keys)
    {
        $allowedKeys = $this->getAttributeKeys();

        $attributes = [];
        if (is_array($keys[0])) {
            $keys = $keys[0];
        }

        foreach ($keys as $key) {
            if (!in_array($key, $allowedKeys)) {
                continue;
            }

            $attributes[$key] = $this->getAttribute($key);
        }

        return collect($attributes);
    }

    /**
     * Get all config attribute keys.
     *
     * @return array $keys
     */
    protected function getAttributeKeys()
    {
        $keys = [];
        $reflector = new ReflectionClass(static::class);

        foreach ($reflector->getProperties() as $property) {
            if ($property->class == self::class) {
                continue;
            }

            $keys[] = $property->name;
        }
        foreach ($reflector->getMethods() as $method) {
            if ($method->class == self::class) {
                continue;
            }
            if (!is_callable([static::class, $method->name])) {
                continue;
            }
            if (Str::startsWith($method->name, 'prepare')) {
                continue;
            }
            if (Str::startsWith($method->name, 'resolve')) {
                continue;
            }

            $keys[] = $method->name;
        }

        return $keys;
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

        if (property_exists($this, $name)) {
            return $this->resolveAttribute($name);
        }

        if (is_callable([static::class, $name]) && method_exists($this, $name)) {
            return $this->resolveConfigMethod($name);
        }

        return $this->$name;
    }

    /**
     * Resolve config attribute.
     *
     * @param string $name
     * @return any
     */
    public function resolveAttribute(string $name)
    {
        $attribute = $this->$name;

        // Resolve attribute by calling its resolve methdd.
        $method = Str::camel("resolve_{$name}");
        if (method_exists($this, $method)) {
            $attribute = $this->$method($attribute);
        }

        // Store attribute.
        $this->attributes[$name] = $attribute;

        return $attribute;
    }

    /**
     * Prepare, execute and resolve config method.
     *
     * @param string $method
     * @param array $parameters
     * @return any
     */
    private function resolveConfigMethod(string $method, $parameters = [])
    {
        $prepared = $parameters;

        // Call prepare method to prepare params.
        $prepareMethod = Str::camel("prepare_{$method}");
        if (method_exists($this, $prepareMethod)) {
            $prepared = $this->$prepareMethod(...$parameters);
        }


        if (is_array($prepared)) {
            $attribute = $this->$method(...$prepared);
        } else {
            $attribute = $this->$method($prepared);
        }

        // Store prepared if attribute is not returned.
        if ($attribute === null) {
            $attribute = $prepared;
        }

        // Store attribute.
        $this->attributes[$method] = $attribute;

        // Resolve attribute by calling its resolve methdd.
        $resolveMethod = Str::camel("resolve_{$method}");
        if (method_exists($this, $resolveMethod)) {
            $attribute = $this->$resolveMethod($attribute);
        }

        return $attribute;
    }

    /**
     * Call config class method.
     *
     * @param string $method
     * @param array $parameters
     * @return any
     * 
     * @throws BadMethodCallException
     */
    public function __call(string $method, $parameters = [])
    {
        if (is_callable([static::class, $method]) && method_exists($this, $method)) {
            return $this->resolveConfigMethod($method, $parameters);
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
     * @return any
     */
    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }
}
