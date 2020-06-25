<?php

namespace Fjord\Chart\Engine;

use Fjord\Chart\ChartSet;

class ApexEngine extends ChartEngine
{
    /**
     * Chart component.
     *
     * @var string
     */
    protected $component = 'fj-chart-apex';

    /**
     * Render chart by type.
     *
     * @param array $names
     * @param ChartSet $set
     * @return array
     */
    public function render(array $names, ChartSet $set)
    {
        $series = [
            'categories' => $set->getLabels(),
            'series' => [],
        ];

        foreach ($set->getValues() as $key => $values) {
            $series['series'][]  = [
                'name' => $names[$key],
                'data' => $values,
                'type' => 'area'
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

    // /**
    //  * Render donut chart.
    //  *
    //  * @param array $names
    //  * @param ChartSet $set
    //  * @return array
    //  */
    // public function renderDonut(array $names, ChartSet $set)
    // {
    //     return [
    //         'categories' => $set->getLabels(),
    //         'labels' => $names,
    //         'series' => $set->getValues()[0],
    //     ];
    // }

    // /**
    //  * Render donut chart.
    //  *
    //  * @param array $names
    //  * @param ChartSet $set
    //  * @return array
    //  */
    // public function renderProgress(array $names, ChartSet $set, $min, $max)
    // {
    //     $value = $set->getValues()[0][0];
    //     $progress = (($value - $min) * 100) / ($max - $min);

    //     return [
    //         'categories' => $set->getLabels(),
    //         'labels' => $names,
    //         'series' => [$progress],
    //     ];
    // }
}
