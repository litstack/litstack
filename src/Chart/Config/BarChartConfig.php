<?php

namespace Ignite\Chart\Config;

abstract class BarChartConfig extends ChartConfig
{
    use Concerns\HasResults;

    /**
     * Chart type.
     *
     * @var string
     */
    public $type = 'bar';

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
    public $engine = 'apex.bar';

    /**
     * Calculate value.
     *
     * @param Builder $query
     *
     * @return int
     */
    abstract public function value($query);
}
