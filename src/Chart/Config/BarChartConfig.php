<?php

namespace Fjord\Chart\Config;

abstract class BarChartConfig extends ChartConfig
{
    use Concerns\HasResults;

    /**
     * Compare to previous time.
     *
     * @var boolean
     */
    public $compare = true;

    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'apex.bar';

    /**
     * Calculate value.
     *
     * @param Builder $query
     * @return integer
     */
    abstract public function value($query);

    /**
     * Set daily goal.
     *
     * @return void
     */
    public function dailyGoal()
    {
        //
    }
}
