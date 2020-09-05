<?php

namespace Ignite\Page\Traits;

use Ignite\Chart\Chart;
use Ignite\Support\Facades\Config;
use InvalidArgumentException;

trait HasCharts
{
    /**
     * Add chart.
     *
     * @param  string                $name
     * @return \Ignite\Vue\Component
     */
    public function chart(string $name)
    {
        if (! $config = Config::get($name)) {
            throw new InvalidArgumentException("Chart config [{$name}] not found.");
        }

        $chart = app('lit.chart.factory')->make($config);

        $this->registerChart($chart);

        $slot = $this->navigationRight();

        if (! $slot->hasComponent('lit-chart-range')) {
            $slot->prependComponent('lit-chart-range');
        }

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
