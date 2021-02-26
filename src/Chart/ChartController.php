<?php

namespace Ignite\Chart;

use Ignite\Chart\Contracts\Engine;
use Ignite\Chart\Loader\AreaLoader;
use Ignite\Chart\Loader\BarLoader;
use Ignite\Chart\Loader\ChartLoader;
use Ignite\Chart\Loader\DonutLoader;
use Ignite\Chart\Loader\NumberLoader;
use Ignite\Chart\Loader\ProgressLoader;
use Ignite\Config\ConfigHandler;
use Ignite\Support\Facades\Config;

class ChartController
{
    /**
     * Get chart data.
     *
     * @param ChartRequest $request
     *
     * @return array
     */
    public function __invoke(ChartRequest $request)
    {
        $config = Config::get($request->key) ?: abort(404);
        $factory = app('lit.chart.factory');
        $chart = $factory->make($config);
        $engine = $factory->getResolver()->resolve($config->engine);

        $loader = $this->makeLoader($chart->getAttribute('type'), $config, $engine);

        return $loader->get($request->type ?: abort(404));
    }

    /**
     * Make chart loader.
     *
     * @param string        $chartType
     * @param ConfigHandler $config
     * @param Engine        $engine
     *
     * @return ChartLoader
     */
    protected function makeLoader(string $chartType, ConfigHandler $config, Engine $engine): ChartLoader
    {
        $loader = [
            'donut'     => fn () => new DonutLoader($config, $engine),
            'area'      => fn () => new AreaLoader($config, $engine),
            'radialBar' => fn () => new ProgressLoader($config, $engine),
            'bar'       => fn () => new BarLoader($config, $engine),
            'number'    => fn () => new NumberLoader($config, $engine),
        ][$chartType] ?? abort(404);

        return $loader();
    }
}
