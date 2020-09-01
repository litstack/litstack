<?php

namespace Ignite\Chart\Engine;

use Ignite\Chart\ChartSet;

class ApexProgressEngine extends ChartEngine
{
    /**
     * Chart component.
     *
     * @var string
     */
    protected $component = 'lit-chart-apex-progress';

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
        $value = $set->getValues()[0][0];
        $progress = (($value - $this->start) * 100) / ($this->goal - $this->start);

        return [
            'categories' => $set->getLabels(),
            'labels'     => $names,
            'series'     => [$progress],
        ];
    }

    public function setStart($start)
    {
        $this->start = $start;
    }

    public function setGoal($goal)
    {
        $this->goal = $goal;
    }
}
