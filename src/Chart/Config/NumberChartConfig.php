<?php

namespace Ignite\Chart\Config;

abstract class NumberChartConfig extends ChartConfig
{
    use Concerns\HasResults;

    /**
     * Chart type.
     *
     * @var string
     */
    public $type = 'number';

    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'number';

    /**
     * Compare to previous time.
     *
     * @var bool
     */
    public $compare = true;

    /**
     * Calculate value.
     *
     * @param Builder $query
     *
     * @return int
     */
    abstract public function value($query);
}
