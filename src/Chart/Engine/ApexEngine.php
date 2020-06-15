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
     * Render Apex chart.
     *
     * @param array $names
     * @param ChartSet $set
     * @return array
     */
    public function render(array $names, ChartSet $set, string $type)
    {
        $series = [
            'categories' => $set->getLabels(),
            'series' => [],
        ];


        if ($type == 'area') {
            foreach ($set->getValues() as $key => $values) {
                $series['series'][]  = [
                    'name' => $names[$key],
                    'data' => $values
                ];
            }
        } else if ($type == 'donut') {
            $series['labels'] = $names;
            $series['series']  = $set->getValues()[0][0];
        }


        return $series;
    }
}
