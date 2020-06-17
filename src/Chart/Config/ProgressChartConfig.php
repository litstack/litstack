<?php

namespace Fjord\Chart\Config;

use Illuminate\Support\Collection;

abstract class ProgressChartConfig extends ChartConfig
{
    /**
     * Calculate value.
     *
     * @param Builder $query
     * @return integer
     */
    abstract public function value($query);

    /**
     * Get maximum value.
     *
     * @return integer|float
     */
    public function maxValue()
    {
        return 100;
    }

    /**
     * Get minimum value.
     *
     * @return integer|float
     */
    public function minValue()
    {
        return 0;
    }
}
