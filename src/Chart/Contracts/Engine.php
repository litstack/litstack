<?php

namespace Fjord\Chart\Contracts;

use Fjord\Chart\ChartSet;

interface Engine
{
    /**
     * Create component
     *
     * @return string;
     */
    public function getComponent();

    /**
     * 
     */
    //public function render(array $names, ChartSet $set);
}
