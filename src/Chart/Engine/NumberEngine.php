<?php

namespace Ignite\Chart\Engine;

use Ignite\Chart\ChartSet;

class NumberEngine extends ChartEngine
{
    /**
     * Chart component.
     *
     * @var string
     */
    protected $component = 'lit-chart-number';

    /**
     * Render chart by type.
     *
     * @param array    $names
     * @param ChartSet $set
     *
     * @return array
     */
    public function render(array $names, ChartSet $set)
    {
        return [
            'categories' => $set->getLabels(),
            'labels'     => $names,
            'series'     => $set->getValues()[0],
        ];
    }
}
