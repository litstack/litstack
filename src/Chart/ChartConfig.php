<?php

namespace Fjord\Chart;

abstract class ChartConfig
{
    public $created_at = 'created_at';

    /**
     * Chart engine.
     *
     * @var string
     */
    public $engine = 'apex';
}
