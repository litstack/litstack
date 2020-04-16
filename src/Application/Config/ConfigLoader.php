<?php

namespace Fjord\Application\Config;

use Illuminate\Support\Str;

class ConfigLoader
{

    protected $namespace = "App\Fjord\Config";

    protected $configs = [];

    public function get(string $key, ...$params)
    {
        if (array_key_exists($key, $this->configs)) {
            return $this->configs[$key];
        }

        $class = $this->getConfigClass($key);
        $instance = new $class(...$params);
        $this->configs[$key] = $instance;

        return $instance;
    }

    protected function getConfigClass(string $key)
    {
        $name = '';
        foreach (explode('.', $key) as $part) {
            $name .= "\\" . ucfirst(Str::camel($part));
        }

        return $this->namespace . $name . "Config";
    }
}
