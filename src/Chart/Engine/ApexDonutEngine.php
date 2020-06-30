<?php

namespace Fjord\Chart\Engine;

use Fjord\Chart\ChartSet;

class ApexDonutEngine extends ChartEngine
{
    /**
     * Chart component.
     *
     * @var string
     */
    protected $component = 'fj-chart-apex-donut';

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
