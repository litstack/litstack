<?php

namespace Ignite\Chart\Config;

abstract class DonutChartConfig extends ChartConfig
{
    /**
     * Chart type.
     *
     * @var string
     */
    public $type = 'donut';

    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'apex.donut';

    /**
     * Value.
     *
     * @param Builder $query
     *
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
