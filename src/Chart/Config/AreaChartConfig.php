<?php

namespace Fjord\Chart\Config;

use Closure;
use Illuminate\Support\Collection;

abstract class AreaChartConfig extends ChartConfig
{
    use Concerns\HasResults;

    /**
     * Compare to previous time.
     *
     * @var boolean
     */
    public $compare = false;

    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'apex.area';

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
