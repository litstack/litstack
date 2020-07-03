<?php

namespace Fjord\Vue\Page\Traits;

use Fjord\Chart\Chart;
use Fjord\Support\Facades\Config;
use InvalidArgumentException;

trait HasCharts
{
    /**
     * Add chart.
     *
     * @param  string               $name
     * @return \Fjord\Vue\Component
     */
    public function chart(string $name)
    {
        if (! $config = Config::get($name)) {
            throw new InvalidArgumentException("Chart config [{$name}] not found.");
        }

        $chart = app('fjord.chart.factory')->make($config);

        $this->registerChart($chart);

        return $chart;
    }

    /**
     * Register chart component.
     *
     * @param  Chart $chart
     * @return void
     */
    protected function registerChart(Chart $chart)
    {
        $this->component($chart->component());
    }
}
