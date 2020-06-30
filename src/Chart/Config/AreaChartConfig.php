<?php

namespace Fjord\Chart\Config;

abstract class AreaChartConfig extends ChartConfig
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
    public $engine = 'apex.area';

    /**
     * Calculate value.
     *
     * @param Builder $query
     * @return integer
     */
    abstract public function value($query);
}
