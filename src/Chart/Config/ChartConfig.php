<?php

namespace Fjord\Chart\Config;

use Fjord\Chart\Chart;

abstract class ChartConfig
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

    /**
     * Default variant.
     *
     * @var string
     */
    public $variant = 'primary';

    /**
     * Chart title.
     *
     * @return string
     */
    abstract public function title(): string;

    /**
     * Mount.
     *
     * @param Chart $chart
     * @return void
     */
    public function mount(Chart $chart)
    {
        //
    }
}
