<?php

namespace Fjord\Chart\Config;

abstract class AreaChartConfig
{
    /**
     * Created at column.
     *
     * @var string
     */
    public $created_at = 'created_at';

    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'apex';
}
