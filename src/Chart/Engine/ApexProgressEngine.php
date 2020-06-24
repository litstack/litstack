<?php

namespace Fjord\Chart\Engine;

use Fjord\Chart\ChartSet;

class ApexProgressEngine extends ChartEngine
{
    /**
     * Chart component.
     *
     * @var string
     */
    protected $component = 'fj-chart-apex-progress';

    /**
     * Render chart by type.
     *
     * @param array $names
     * @param ChartSet $set
     * @return array
     */
    public function render(array $names, ChartSet $set)
    {
        $value = $set->getValues()[0][0];
        $progress = (($value - $this->min) * 100) / ($this->max - $this->min);

        return [
            'categories' => $set->getLabels(),
            'labels' => $names,
            'series' => [$progress],
        ];
    }

    public function setMinMax($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }
}
