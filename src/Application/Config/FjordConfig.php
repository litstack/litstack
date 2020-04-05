<?php

namespace Fjord\Application\Config;

abstract class FjordConfig
{
    /**
     * Default config attributes.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Original config attributes.
     *
     * @var array
     */
    protected $originals = [];

    /**
     * Config attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create new FjordConfig instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->originals = $attributes;
        $this->attributes = $attributes;

        $this->setDefaults();
    }

    /**
     * Set default config attributes.
     *
     * @return void
     */
    protected function setDefaults()
    {
        foreach ($this->defaults as $key => $default) {
            if (!array_key_exists($key, $this->attributes)) {
                $this->attributes[$key] = $default;
            }
        }
    }

    /**
     * Get config attributes
     *
     * @return array $attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
