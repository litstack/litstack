<?php

namespace Ignite\Chart\Contracts;

use Ignite\Chart\ChartSet;

interface Engine
{
    /**
     * Get Vue component name.
     *
     * @return string;
     */
    public function getComponent();

    /**
     * Render chart.
     *
     * @param array    $names
     * @param ChartSet $set
     *
     * @return mixed
     */
    public function render(array $names, ChartSet $set);
}
