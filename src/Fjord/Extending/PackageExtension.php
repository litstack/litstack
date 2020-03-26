<?php

namespace AwStudio\Fjord\Fjord\Extending;

class PackageExtension
{
    protected $route;

    protected $props = [];

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function add($prop, $value)
    {
        if(! array_key_exists($prop, $this->props)) {
            $this->props[$prop] = [];
        }

        $this->props[$prop] []= $value;

        return $this;
    }

    public function getProps()
    {
        return $this->props;
    }
}
