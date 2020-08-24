<?php

namespace Lit\Chart\Engine;

use Lit\Chart\ChartSet;

class ApexBarEngine extends ChartEngine
{
    /**
     * Chart component.
     *
     * @var string
     */
    protected $component = 'fj-chart-apex-bar';

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
                'type' => 'bar',
            ];
        }

        return $series;
    }
}
