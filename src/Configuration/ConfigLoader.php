<?php

namespace Fjord\Configuration;

use Illuminate\Support\Str;

class ConfigLoader
{
    /**
     * Namespace to config files.
     *
     * @var string
     */
    protected $namespace = "App\Fjord\Config";

    /**
     * Stack of loaded instances.
     *
     * @var array
     */
    protected $loaded = [];

    /**
     * Get config by key.
     *
     * @param string $key
     * @param array ...$params
     * @return mixed
     */
    public function get(string $key, ...$params)
    {
        // Return from stack if already loaded.
        if (array_key_exists($key, $this->loaded)) {
            return $this->loaded[$key];
        }

        // Get classname by key.
        $class = $this->getClassName($key);

        // Passing params to new instance.
        $instance = new $class(...$params);

        // Add config to stack.
        $this->loaded[$key] = $instance;

        return $instance;
    }

    /**
     * Get classname of config by key.
     *
     * @param string $key
     * @return string
     */
    protected function getClassName(string $key)
    {
        $name = '';
        foreach (explode('.', $key) as $part) {
            $name .= "\\" . ucfirst(Str::camel($part));
        }

        return $this->namespace . $name . "Config";
    }
}
