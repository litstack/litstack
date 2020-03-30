<?php

namespace AwStudio\Fjord\Application\Vue;

abstract class VueProp
{
    /**
     * Original prop value.
     * 
     * @var
     */
    protected $original;

    /**
     * Prop value.
     * 
     * @var
     */
    protected $value;

    abstract protected function handle($value);

    public function __construct($value)
    {
        $this->original = $value;
        $this->value = $this->handle($value);
    }

    /**
     * Returns prop value.
     * 
     * @return $value
     */
    public function getValue()
    {
        return $this->value;
    }
}

