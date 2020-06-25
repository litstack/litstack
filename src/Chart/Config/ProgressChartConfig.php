<?php

namespace Fjord\Chart\Config;

use Illuminate\Support\Collection;

abstract class ProgressChartConfig extends ChartConfig
{
    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'apex.progress';

    /**
     * Calculate value.
     *
     * @param Builder $query
     * @return integer
     */
    abstract public function value($query);

    /**
     * Get goal value.
     *
     * @return integer|float
     */
    public function goal()
    {
        return 100;
    }

    /**
     * Get start value.
     *
     * @return integer|float
     */
    public function start()
    {
        return 0;
    }
}
