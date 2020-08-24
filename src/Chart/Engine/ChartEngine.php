<?php

namespace Lit\Chart\Engine;

use Lit\Chart\Contracts\Engine;

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
