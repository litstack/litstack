<?php

namespace Fjord\Chart\Engine;

use Fjord\Chart\Contracts\Engine;

class ChartEngine implements Engine
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
