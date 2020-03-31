<?php

namespace AwStudio\Fjord\Application\Navigation;

use AwStudio\Fjord\Application\Config\FjordConfig;

class TopbarConfig extends FjordConfig
{
    use Traits\NavItemValidation;

    /**
     * Default config attributes.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Get config attributes.
     *
     * @return void
     */
    public function getAttributes()
    {
        $this->attributes = $this->checkItems($this->attributes);

        return $this->attributes;
    }
}
