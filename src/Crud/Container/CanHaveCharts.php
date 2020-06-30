<?php

namespace Fjord\Crud\Container;

use Fjord\Chart\Chart;
use Fjord\Support\Facades\Config;
use InvalidArgumentException;

trait CanHaveCharts
{
    /**
     * Create chart.
     *
     * @param string $name
     *
     * @return \Fjord\Vue\Component
     */
    public function chart(string $name)
    {
        $config = Config::get($name);

        if (!$config) {
            throw new InvalidArgumentException("Config [{$name}] not found.");
        }

        $chart = app('fjord.chart.factory')->make($config);

        $this->registerChart($chart);

        return $chart;
    }

    /**
     * Register chart component.
     *
     * @param Chart $chart
     *
     * @return void
     */
    protected function registerChart(Chart $chart)
    {
        $this->component($chart->component());
    }
}
