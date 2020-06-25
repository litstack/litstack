<?php

namespace Fjord\Chart\Config;

abstract class NumberChartConfig extends ChartConfig
{
    use Concerns\HasResults;

    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'number';

    /**
     * Compare to previous time.
     *
     * @var boolean
     */
    public $compare = true;

    /**
     * Calculate value.
     *
     * @param Builder $query
     * @return integer
     */
    abstract public function value($query);
}
