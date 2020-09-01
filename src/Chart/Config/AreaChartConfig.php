<?php

namespace Ignite\Chart\Config;

abstract class AreaChartConfig extends ChartConfig
{
    use Concerns\HasResults;

    /**
     * Compare to previous time.
     *
     * @var bool
     */
    public $compare = true;

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
     *
     * @return int
     */
    abstract public function value($query);
}
