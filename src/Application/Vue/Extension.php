<?php

namespace AwStudio\Fjord\Application\Vue;

abstract class Extension
{
    /**
     * Modify props in handle method.
     * 
     * @var array $props
     * @return array $props
     */
    abstract public function handle($props);
}