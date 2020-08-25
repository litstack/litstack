<?php

namespace Ignite\Chart\Engine;

use Ignite\Chart\ChartSet;

class ApexEngine extends ChartEngine
{
    /**
     * Chart component.
     *
     * @var string
     */
    protected $component = 'lit-chart-apex';

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

        // if ($goal !== null) {
        //     $series['series'][]  = [
        //         'name' => 'Goal',
        //         'data' => array_fill(0, count($set->getValues()[0]), $goal),
        //         'type' => 'line'
        //     ];
        // }

        return $series;
    }
}
