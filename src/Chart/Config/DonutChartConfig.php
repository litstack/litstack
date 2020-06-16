<?php

namespace Fjord\Chart\Config;

abstract class DonutChartConfig extends ChartConfig
{
    /**
     * Value.
     *
     * @param Builder $query
     * @return array
     */
    abstract public function value($query): array;

    /**
     * Labels.
     *
     * @return array
     */
    abstract public function labels(): array;
}
