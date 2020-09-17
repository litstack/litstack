<?php

namespace Ignite\Chart\Engine;

use Ignite\Chart\ChartSet;

class ApexAreaEngine extends ChartEngine
{
    /**
     * Chart component.
     *
     * @var string
     */
    protected $component = 'lit-chart-apex-area';

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
        $series = [
            'categories' => $set->getLabels(),
            'series'     => [],
        ];

        foreach ($set->getValues() as $key => $values) {
            $series['series'][] = [
                'name' => $names[$key],
                'data' => $values,
                'type' => 'area',
            ];
        }

        return $series;
    }
}
