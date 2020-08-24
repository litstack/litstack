<?php

namespace Lit\Chart\Engine;

use Lit\Chart\ChartSet;

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

    /**
     * Render donut chart.
     *
     * @param array    $names
     * @param ChartSet $set
     *
     * @return array
     */
    public function renderDonut(array $names, ChartSet $set)
    {
        return [
            'categories' => $set->getLabels(),
            'labels'     => $names,
            'series'     => $set->getValues()[0],
        ];
    }

    /**
     * Render donut chart.
     *
     * @param array    $names
     * @param ChartSet $set
     *
     * @return array
     */
    public function renderProgress(array $names, ChartSet $set, $min, $max)
    {
        $value = $set->getValues()[0][0];
        $progress = (($value - $min) * 100) / ($max - $min);

        return [
            'categories' => $set->getLabels(),
            'labels'     => $names,
            'series'     => [$progress],
        ];
    }
}
