<?php

namespace AwStudio\Fjord\Fjord\Extending;

class PackageExtension
{
    protected $name;

    protected $packageName;

    protected $props = [];

    protected $pattern = [];

    public function __construct($name, $packageName, $pattern = [])
    {
        $this->name = $name;
        $this->packageName = $packageName;
        $this->pattern = $pattern;
    }

    public function add($prop, $value)
    {
        $this->validate($prop, $value);

        if(! array_key_exists($prop, $this->props)) {
            $this->props[$prop] = [];
        }

        if(in_array($value, $this->props[$prop])) {
            return $this;
        }

        $this->props[$prop] []= $value;

        return $this;
    }

    public function getProps()
    {
        return $this->props;
    }

    /**
     * Check if input is allowed.
     *
     */
    protected function validate($prop, $value)
    {
        if(empty($this->pattern)) {
            return;
        }

        if(is_array($this->pattern)) {
            if(! in_array($prop, $this->pattern)) {
                throw new \Exception("There is no extension named \"{$prop}\" for {$this->packageName}:{$this->name}. Possible extensions are: " . implode(", ", $this->pattern));
            }

            return;
        }

        return;
        // TODO: Add pattern with options like type => array..
        if(! array_key_exists()) {

        }
    }
}
