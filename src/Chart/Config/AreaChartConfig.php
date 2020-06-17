<?php

namespace Fjord\Chart\Config;

use Illuminate\Support\Collection;

abstract class AreaChartConfig extends ChartConfig
{
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

    /**
     * Number that is displayed at the bottom left corner.
     *
     * @param \Illuminate\Support\Collection
     * @return integer
     */
    abstract public function result(Collection $values): int;
}
