<?php

namespace Ignite\Chart\Config;

abstract class ProgressChartConfig extends ChartConfig
{
    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'apex.progress';

    /**
     * Chart type.
     *
     * @var string
     */
    public $type = 'progress';

    /**
     * Calculate value.
     *
     * @param Builder $query
     *
     * @return int
     */
    abstract public function value($query);

    /**
     * Get goal value.
     *
     * @return int|float
     */
    public function goal()
    {
        return 100;
    }

    /**
     * Get start value.
     *
     * @return int|float
     */
    public function start()
    {
        return 0;
    }
}
