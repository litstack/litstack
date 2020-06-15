<?php

namespace Fjord\Chart\Contracts;

use Fjord\Chart\Chart;

interface Engine
{
    /**
     * Create component
     *
     * @return string;
     */
    public function getComponent();
}
