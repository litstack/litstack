<?php

namespace AwStudio\Fjord\Fjord\Config;

abstract class FjordConfig
{
    protected $defaults = [];

    protected $originals = [];

    protected $attributes = [];

    public function __construct($attributes)
    {
        $this->originals = $attributes;
        $this->attributes = $attributes;

        $this->setDefaults();
    }

    protected function setDefaults()
    {
        foreach($this->defaults as $key => $default) {
            if(! array_key_exists($key, $this->attributes)) {
                $this->attributes[$key] = $default;
            }
        }
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
