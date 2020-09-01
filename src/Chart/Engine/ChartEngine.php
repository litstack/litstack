<?php

namespace Ignite\Chart\Engine;

use Ignite\Chart\Contracts\Engine;

abstract class ChartEngine implements Engine
{
    /**
     * Get component name.
     *
     * @return string
     */
    public function getComponent()
    {
        return $this->component;
    }
}
